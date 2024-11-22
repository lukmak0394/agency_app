<?php

$cfg = [];

$cfg['client_form_expected_post'] = [
    'name' => 'Name',
    'company_name' => 'Company Name',
    'country' => 'Country',
    'vat_number' => 'Vat Number',
    'currency' => 'Currency',
    'package' => 'Package'
];

$cfg['currencies'] = [
    1 => 'EUR',
    2 => 'USD',
    3 => 'PLN'
];

$cfg['countries'] = [
    1 => 'Germany',
    2 => 'USA',
    3 => 'Poland'
];

$cfg['pages'] = [
    'client_form' => APP_URL.'client-form.php',
    'clients_list' => APP_URL.'clients-list.php',
    'packages_list' => APP_URL.'packages-list.php',
    'contacts_list' => APP_URL.'contacts-list.php',
    'acc_managers_clients' => APP_URL.'acc-managers-clients.php'
];

define("HELPER_ARRAYS", $cfg);