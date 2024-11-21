<?php

declare(strict_types=1);

require_once(SYSROOT.'app.php');

class Employee 
{
    
    protected object $db;
    protected int $id;

    public function __construct()
    {
        App::get();
        $this->db = DbQuery::get();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    protected function checkId(): int
    {
        return $this->id ?? 0;
    }

    public static function getEmployees(): array
    {
        App::get();
        return DbQuery::get()->select(['*'])->from('agency_employees')->getRows();
    }

    public function get(int $id = 0): array
    {
        if (!$this->checkId() || !$id) {
            return [];
        }

        $id = $id ? $id : $this->id;

        return $this->db->select(['*'])->from('agency_employees')->where('id = ?', [$id])->getRow();
    }

}