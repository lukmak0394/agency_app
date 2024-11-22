<?php

require_once("./../configs/globalconst.php");
require_once(SYSROOT.'app.php');

function processRequest() {

    header("Content-Type: application/json");   

    App::loadClass('accountmanager', SYSROOT);

    $data = AccountManager::getClients();

    if(!$data) {
        App::handleJsonResponse('Error getting data');
        return;
    }
  
    App::handleJsonResponse('Got data', 'ok', $data);
}

processRequest();
