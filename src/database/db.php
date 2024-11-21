<?php

declare(strict_types=1);

class Db
{

    protected object $connection_provider;
    protected static $connection;
    protected array $connection_params;
    protected string $lib;

    public function __construct()
    {
        $this->setConnectionParams();

        $this->checkConnectionParams();

        $this->setConnectionProviderLibrary();

        $this->initConnectionProvider();

        $provider = $this->connection_provider;

        if (!$provider) {
            throw new \Exception("Connection provider not set");
        }

        $provider->connect(
            $this->connection_params['host'],
            $this->connection_params['user'],
            $this->connection_params['pwd'],
            $this->connection_params['db']
        );

        if (!$provider->connected()) {
            throw new \Exception("Not connected to database");
        }
    }

    private function getConnectionParamsDefinition(): array
    {
        return [
            'host' => [
                'empty' => false,
            ],
            'user' => [
                'empty' => false,
            ],
            'pwd' => [
                'empty' => true,
            ],
            'db' => [
                'empty' => false,
            ]
        ];
    }

    private function setConnectionParams()
    {
        require_once(DOCROOT.'configs'.DS.'db_conf.php');

        if(!defined('DB_PARAMS')) {
            throw new \Exception("Database connection parameters not set");
        }

        $this->connection_params = DB_PARAMS;
    }

    private function checkConnectionParams()
    {
        if (empty($this->connection_params)) {
            throw new \Exception("Connection parameters not set");
        }

        $required_params = $this->getConnectionParamsDefinition();

        $missing_params = [];

        foreach ($required_params as $param => $options) {
            if (!isset($this->connection_params[$param]) || ($options['empty'] === false && empty($this->connection_params[$param]))) {
                $missing_params[] = $param;
            }
        }

        if (!empty($missing_params)) {
            throw new \Exception("Connection parameters not set: " . implode(", ", $missing_params) . "");
        }
    }

    private function setConnectionProviderLibrary()
    {
        $this->lib = $this->connection_params['lib'] ?? 'mysqli';
    }

    private function availableConnectionProviders(): array
    {
        require_once(DB_FOLDER.'providers'.DS.'mysqliconnectionprovider.php');
        return [
            'mysqli' => MySqliConnectionProvider::class,
        ];
    }

    private function initConnectionProvider()
    {
        $lib = $this->lib;

        $available_providers = $this->availableConnectionProviders();

        if (!isset($available_providers[$lib])) {
            throw new \Exception("Connection provider for library " . $lib . " not found");
        }

        $provider_class = $available_providers[$lib];

        if (empty($provider_class)) {
            throw new \Exception("Connection provider class not found");
        }

        if (!class_exists($provider_class)) {
            throw new \Exception("Connection provider class not found: " . $provider_class . "");
        }

        $this->connection_provider = new $provider_class();
    }

    protected static function connectionExists($id): bool
    {
        return isset(self::$connection[$id]);
    }

    protected function getProviderConnection(): object
    {
        return $this->connection_provider->getConnection();
    }

    protected static function get()
    {

    }

    protected function getConnectionParams()
    {
        return $this->connection_params; 
    }

}
