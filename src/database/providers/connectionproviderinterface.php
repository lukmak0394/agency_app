<?php

declare(strict_types=1);

interface ConnectionProviderInterface
{
    public function connect($host, $user, $password, $database);
    public function close();
    public function connected(): bool;
    public function getConnection();
}
