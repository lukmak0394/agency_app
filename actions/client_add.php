<?php

require_once("./../configs/globalconst.php");
require_once(SYSROOT.'app.php');

function processRequest() {

    header("Content-Type: application/json");   

    $expected_post_values = App::getConf('client_form_expected_post');
    $data_to_insert = [];
    
    foreach ($expected_post_values as $key => $name) {
        if (!isset($_POST[$key]) || empty($_POST[$key])) {
            App::handleJsonResponse($name . ' is required');
        }
        $data_to_insert[$key] = trim($_POST[$key]);
    }

    $valid_countries = App::getConf('countries');
    $valid_currencies = App::getConf('currencies');

    if (!in_array($data_to_insert['country'], array_keys($valid_countries))) {
        App::handleJsonResponse('Invalid country selected');
    }

    if (!in_array($data_to_insert['currency'], array_keys($valid_currencies))) {
        App::handleJsonResponse('Invalid currency selected');
    }

    $contact_persons = [];
    if (isset($_POST['contactPersons']) && is_array($_POST['contactPersons'])) {
        foreach ($_POST['contactPersons'] as $contact) {
            if (
                isset($contact['firstname'], $contact['lastname'], $contact['email'], $contact['phone']) &&
                !empty($contact['firstname']) &&
                !empty($contact['lastname']) &&
                !empty($contact['email']) &&
                !empty($contact['phone'])
            ) {
                $contact_persons[] = $contact;
            } else {
                App::handleJsonResponse('All fields for each contact person are required');
            }
        }
    }

    App::loadClass('client', SYSROOT);

    $client = new Client();

    $company_name = $data_to_insert['company_name'];

    $exists = $client->checkIfClientExistsByCompanyName($company_name);

    if($exists) {
        App::handleJsonResponse('Client with company name ' . $company_name . ' already exists');
    }

    $created_id = $client->createClient($data_to_insert, $contact_persons);

    if (!$created_id) {
        App::handleJsonResponse('Error creating client');
        return;
    }

    App::handleJsonResponse('Client and contacts created successfully', 'ok');
}

processRequest();
