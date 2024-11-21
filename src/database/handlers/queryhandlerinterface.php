<?php

declare(strict_types=1);

interface QueryHandlerInterface
{
    public function setConnection($connection);
    public function query(string $query, array $params);
    public function fetchAssoc($stmt);
    public function fetchOneAssoc($stmt);
    public function beginTransaction();
    public function commit();
    public function rollBack();
}
