<?php

declare(strict_types=1);

require_once(SYSROOT.'app.php');
require_once(SYSROOT.'employee.php');

class AccountManager extends Employee 
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function getClients(): array
    {
        App::get();
        return Dbquery::get()->select(['*'])->from('agency_account_managers_clients')->getRows();
    }

}