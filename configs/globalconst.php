<?php

define("APP_URL", "https://lmdev.pl/");

define("DS",DIRECTORY_SEPARATOR);
define('DOCROOT', str_replace(DS.DS,DS,realpath(dirname(dirname(__FILE__))).DS));
define('SYSROOT', str_replace(DS.DS,DS,realpath(dirname(dirname(__FILE__))).DS."src".DS));

define("DB_FOLDER", SYSROOT."database".DS);
define("VIEWS_FOLDER", DOCROOT."views".DS);


