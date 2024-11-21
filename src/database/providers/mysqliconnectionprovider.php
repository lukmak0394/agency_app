<?php

declare(strict_types=1);

require_once('connectionproviderinterface.php');

class MySqliConnectionProvider implements ConnectionProviderInterface
{

    protected $connection;

    public function connect($host, $user, $password, $database)
    {
        $this->connection = new mysqli($host, $user, $password, $database);

        try {
            $this->connection = new mysqli($host, $user, $password, $database);
        } catch(Exception $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function close()
    {
        $this->connection->close();
    }

    public function connected(): bool
    {
        return $this->connection->ping();
    }

    public function getConnection()
    {
        return $this->connection;
    }

}
