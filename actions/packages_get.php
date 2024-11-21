<?php

require_once("./../configs/globalconst.php");
require_once(SYSROOT.'app.php');

function transformPackagesData(&$data)
{
    $currencies = App::getConf('currencies');

    foreach ($data as &$package) {
        $package['currency'] = $currencies[$package['currency']];
    }
}

function processRequest() {

    header("Content-Type: application/json");   

    App::loadClass('package', SYSROOT);
    
    $data = Package::getPackages();

    transformPackagesData($data);

    if(!$data) {
        echo json_encode(['status' => 'error', 'msg' => 'No data']);
        return;
    }

    echo json_encode(['status' => 'ok', "data" => $data]);
}

processRequest();
