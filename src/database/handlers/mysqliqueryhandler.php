<?php

declare(strict_types=1);

require_once('queryhandlerinterface.php');

class MySqliQueryHandler implements QueryHandlerInterface 
{
    private $connection;
    
    public function query(string $query, array $params = [])
    {
        $stmt = null;

        try {
            $stmt = $this->connection->prepare($query);
        } catch (\Exception $e) {
            throw new \Exception("Error preparing query: " . $e->getMessage());
        }

        if (!empty($params)) {
            $this->bindParams($stmt, $params);
        }

        try{
            $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Error executing query: " . $e->getMessage());
        }

        return $stmt;
    }

    private function bindParams($stmt, array $params)
    {   
        $types = "";

        foreach($params as $param) {
            if(is_int($param)) {
                $types .= "i";
            } else if (is_double($param)) {
                $types .= "d";
            } else if (is_string($param)) {
                $types .= "s";
            } else {
                $types .= "b";
            }
        }

        $stmt->bind_param($types, ...$params);
    }

    public function fetchAssoc($stmt)
    {
        $data = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function fetchOneAssoc($stmt)
    {
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function setConnection($conn)
    {
        $this->connection = $conn;
    }

    public function beginTransaction(string $name = null)
    {
        $this->connection->begin_transaction($name);
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollBack()
    {
        $this->connection->rollback();
    }

}
