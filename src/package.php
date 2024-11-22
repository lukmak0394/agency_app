<?php

declare(strict_types=1);

require_once(SYSROOT.'app.php');

class Package
{
    private object $db;
    private int $id;

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

    public function get(int $id = 0): array
    {
        if (!$this->checkId() && !$id) {
            return [];
        }

        $id = $id ? $id : $this->id;

        return $this->db->select(['*'])->from('agency_packages')->where("id = ?", [$id])->getRow();
    }

    public static function getPackages(int $currency = 0): array
    {
        App::get();

        if(!$currency) {
            return DbQuery::get()->select(['*'])->from('agency_packages')->getRows();
        }

        return DbQuery::get()->select(['*'])->from('agency_packages')->where("currency = ?", [$currency])->getRows();
    }

}