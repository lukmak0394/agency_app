<?php

declare(strict_types=1);

require_once('db.php');

class DbQuery extends Db
{

    private $select = [];
    private $from = [];
    private $where = [];
    private $join = [];
    private $order_by = [];
    private $limit = [];
    private $update = [];
    private $update_fields = [];
    private $delete = [];
    private $query_params = [];
    private $raw_query = '';

    private object $query_handler;

    public function __construct()
    {
        parent::__construct();
      
        $this->initQueryHandler();

        $connection_object = $this->getProviderConnection();

        $this->query_handler->setConnection($connection_object);
    }

    private function availableQueryHandlers(): array
    {
        require_once(DB_FOLDER.'handlers'.DS.'mysqliqueryhandler.php');
        return [
            'mysqli' => MySqliQueryHandler::class,
        ];
    }

    private function initQueryHandler()
    {
        $lib = $this->lib;

        $available_providers = $this->availableQueryHandlers();

        if (!isset($available_providers[$lib])) {
            throw new \Exception("Query provider not found for library: " . $lib . "");
        }

        $provider_class = $available_providers[$lib];

        if (empty($provider_class)) {
            throw new \Exception("Query provider class not set");
        }

        if (!class_exists($provider_class)) {
            throw new \Exception("Query provider class not found: " . $provider_class . "");
        }

        $this->query_handler = new $provider_class();
    }

    public static function get(): DbQuery
    {
        $db_instance = new self();

        $params = $db_instance->connection_params;

        if (!isset($params['id']) || empty($params['id'])) {
            throw new \Exception("Database ID not found");
        }

        $id = $params['id'];

        if (self::connectionExists($id)) {
            $instance = self::$connection[$id];
            $instance->resetQueryParameters();
            return $instance;
        }

        $instance = new self();
        self::$connection[$id] = $instance;
        return $instance;
    }

    private function resetQueryParameters() 
    {
        $this->select = [];
        $this->from = [];
        $this->where = [];
        $this->join = [];
        $this->order_by = [];
        $this->limit = [];
        $this->query_params = [];
        $this->raw_query = '';
    }

    public function select(array $fields): DbQuery
    {
        $this->select[] = "SELECT " . implode(", ", $fields);
        return $this;
    }

    public function from(string $table): DbQuery
    {
        $this->from[] = " FROM " . $table;
        return $this;
    }

    public function where(string $conditions, array $params = []): DbQuery
    {
        $this->where[] = " WHERE " . $conditions;
        $this->query_params = array_merge($this->query_params, $params);
        return $this;
    }

    public function andWhere(string $conditions, array $params = []): DbQuery
    {
        $this->where[] = " AND " . $conditions;
        $this->query_params = array_merge($this->query_params, $params);
        return $this;
    }

    public function orWhere(string $conditions, array $params = []): DbQuery
    {
        $this->where[] = " OR " . $conditions;
        $this->query_params = array_merge($this->query_params, $params); 
        return $this;
    }

    public function join(string $table, string $condition): DbQuery
    {
        $this->join[] = " JOIN " . $table . " ON " . $condition;
        return $this;
    }

    public function leftJoin(string $table, string $condition): DbQuery
    {
        $this->join[] = " LEFT JOIN " . $table . " ON " . $condition;
        return $this;
    }

    public function rightJoin(string $table, string $condition): DbQuery
    {
        $this->join[] = " RIGHT JOIN " . $table . " ON " . $condition;
        return $this;
    }

    public function limit(int $limit, int $offset = 0): DbQuery
    {
        $query = " LIMIT ";
        if(!empty($offset)) {
            $query .= $offset . ", ";
        }
        $query .= $limit;
        $this->limit[] = $query;
        return $this;
    }

    public function orderBy(string $field, string $order): DbQuery
    {
        $this->order_by[] = " ORDER BY " . $field . " " . $order;
        return $this;
    }

    public function insert(string $table, array $data)
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));

        $query = "INSERT INTO ".$table." (".$fields.") VALUES (".$placeholders.")";

        return $this->query_handler->query($query, array_values($data));
    }

    public function update(string $table): DbQuery
    {
        $this->update[] = " UPDATE " . $table . " ";
        return $this;
    }

    public function set(array $fields, array $params): DbQuery
    {
        $this->update_fields[] = " SET " . implode(", ", $fields);
        $this->query_params = array_merge($this->query_params, $params);
        return $this;
    }

    public function delete(string $table)
    {
        $this->delete[] = " DELETE FROM ".$table."";
        return $this;
    }

    public function setQuery(string $query): DbQuery
    {
        $this->raw_query = $query;
        return $this;
    }   

    private function checkQueryArrays(array $query_arrays): bool
    {
        foreach($query_arrays as $key => $query_array) {
            if(empty($query_array)) {
                unset($query_arrays[$key]);
            }
        }

        return !empty($query_arrays);
    }

    private function isRawQueryValid(): bool
    {
        return !empty($this->raw_query);
    }

    private function buildQuery(array $query_arrays): string
    {
        return implode(' ', array_merge(...$query_arrays));
    }

    private function validateAndBuildQuery(array $query_arrays): array
    {
        if ($this->checkQueryArrays($query_arrays) && !$this->isRawQueryValid()) {
            $query = $this->buildQuery($query_arrays);
            $query_params = $this->query_params;
        } elseif (!$this->checkQueryArrays($query_arrays) && $this->isRawQueryValid()) {
            $query = $this->raw_query;
            $query_params = []; 
        } else {
            throw new \Exception("Invalid query");
            return [null, []];
        }

        return [$query, $query_params];
    }


    // Get all results as array
    public function getRows()
    {
        $query_arrays = [
            $this->select,
            $this->from,
            $this->join,
            $this->where,
            $this->order_by,
            $this->limit
        ];

        list($query, $query_params) = $this->validateAndBuildQuery($query_arrays);
     
        if (empty($query)) {
            return [];
        }
        
        $stmt = $this->query_handler->query($query, $query_params);
  
        return $this->query_handler->fetchAssoc($stmt);
    }

    // Get one result as array
    public function getRow()
    {
        $query_arrays = [
            $this->select,
            $this->from,
            $this->join,
            $this->where
        ];

        list($query, $query_params) = $this->validateAndBuildQuery($query_arrays);
      
        if (empty($query)) {
            return null;
        }

        $stmt = $this->query_handler->query($query, $query_params);

        return $this->query_handler->fetchOneAssoc($stmt);
    }

    // Get one field value specified by field name
    public function getField(string $field)
    {
        $query_arrays = [
            $this->select,
            $this->from,
            $this->join,
            $this->where
        ];

        list($query, $query_params) = $this->validateAndBuildQuery($query_arrays);

        if (empty($query)) {
            return null;
        }

        $stmt = $this->query_handler->query($query, $query_params);

        $result = $this->query_handler->fetchOneAssoc($stmt);

        return $result[$field] ?? null;
    }

    public function executeUpdate()
    {
        $query_arrays = [
            $this->update,
            $this->update_fields,
            $this->where
        ];

        list($query, $query_params) = $this->validateAndBuildQuery($query_arrays);

        if (empty($query)) {
            return false;
        }

        return $this->query_handler->query($query, $query_params);
    }

    public function executeDelete()
    {
        $query_arrays = [
            $this->delete,
            $this->where
        ];

        list($query, $query_params) = $this->validateAndBuildQuery($query_arrays);

        if (empty($query)) {
            return false;
        }

        return $this->query_handler->query($query, $query_params);
    }

    public function execute()
    {
        if(empty($this->raw_query)) {
           return false;
        }

        return $this->query_handler->query($this->raw_query, $this->query_params);
    }

    public function beginTransaction()
    {
        $this->query_handler->beginTransaction();
    }

    public function commit()
    {
        $this->query_handler->commit();
    }

    public function rollBack()
    {
        $this->query_handler->rollBack();
    }
    
}
