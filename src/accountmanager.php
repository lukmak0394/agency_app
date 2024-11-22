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

        $ids = Dbquery::get()->select(['*'])->from('agency_account_managers_clients')->getRows();

        if(empty($ids)) {
            return [];
        }

        $res = [];

        foreach($ids as $item) {

            $employee_id = (int) $item['employee_id'];
            
            $employye_data = Dbquery::get()->select(['*'])->from('agency_employees')->where('id = ?', [$employee_id])->getRow();;

            if(empty($employye_data)) {
                continue;
            }

            $client_id = (int) $item['client_id'];

            $client_data = Dbquery::get()->select(['*'])->from('agency_clients')->where('id = ?', [$client_id])->getRow();

            if(empty($client_data)) {
                continue;
            }

            $res[] = [
                'employee_id' => $employee_id,
                'employee_firstname' => $employye_data['firstname'],
                'employee_lastname' => $employye_data['lastname'],
                'employee_email' => $employye_data['email'],
                'employee_phone' => $employye_data['phone'],
                'client_id' => $client_id,
                'client_name' => $client_data['name'],
                'client_company' => $client_data['company_name'],
            ];

        }

        return $res;
    }

}