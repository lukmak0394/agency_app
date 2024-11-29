<?php

require_once("./../configs/globalconst.php");
require_once(SYSROOT.'app.php');


function processRequest() {

    header("Content-Type: application/json");   

    App::loadClass('clientcontact', SYSROOT);

    $data = ClientContact::getAllContactPersons();

    if(!$data) {
        App::handleJsonResponse('Error getting clients');
        return;
    }
  
    App::handleJsonResponse('Got clients data', 'ok', $data);
}

processRequest();
