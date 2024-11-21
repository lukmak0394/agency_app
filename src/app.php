<?php

declare(strict_types=1);

class App
{
    private static $instance = null;

    public function __construct()
    {
        $this->disableErrorReporting();
        $this->run();
    }

    private function run()
    {
        require_once(DOCROOT.'configs'.DS.'globalconst.php');
        self::loadClass('db', SYSROOT.'database'.DS);
        self::loadClass('dbquery', SYSROOT.'database'.DS); 
    }
    
    public static function get() 
    {
        return is_null(self::$instance) ? self::$instance = new self() : self::$instance;
    }

    public static function loadClass(string $name, string $folder)
    {
        if(empty($name)) {
            throw new \Exception("Class name not set");
        }   

        if (empty($folder)) {
            $folder = SYSROOT;
        }

        
        if (!file_exists($folder.$name.".php")) {
            throw new \Exception("Class file not found: " . $folder.$name.".php");
        }

        require_once($folder.$name.".php");
    }

    private function disableErrorReporting()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
    }

    public static function getConf(string $key, string $key2 = "")
    {   
        require_once(DOCROOT.'configs'.DS.'helper_arrays.php');
        
        $helper_arrays = HELPER_ARRAYS;

        if(!isset($helper_arrays[$key])) {
            throw new \Exception("Config key not found: " . $key);
        }

        $result = $helper_arrays[$key];

        if ($key2 !== "") {
            if (!isset($result[$key2])) {
                throw new \Exception("Config sub-key not found: " . $key2);
            }
            $result = $result[$key2];
        }

        return $result;
    }

    public static function handleJsonResponse(string $msg = "", string $status = 'error', array $data = [])
    {
        header("Content-Type: application/json");

        if($status == 'error' && empty($msg)) {
            $msg = 'An error occurred';
        } else if($status == 'ok' && empty($msg)) {
            $msg = 'Success';
        }

        echo json_encode(['status' => $status, 'msg' => $msg, 'data' => $data]);

        exit;
    }
    
}
