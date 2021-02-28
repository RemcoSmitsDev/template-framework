<?php

// load all class files when someone calls a class
spl_autoload_register(function ($class_name) {
    include __dir__.'/class/'.$class_name . '.php';
});

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'remco_test');
// debug mode
define('DEBUG_MODE', true);


// show all errors if debug mode is enabled
if(DEBUG_MODE === true){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
