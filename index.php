<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('includes/php/functions.php');
//Get request URI
$request = $_SERVER['REQUEST_URI'];

//Enable get
$arr = explode('?', $request, 2);
$request = $arr[0];

$GLOBALS['request'] = $request;

global $CONTENT;

// auto login function if there is no user logged in
if(!isset($_SESSION['_user'])){
    $login = new Login();
    $login->Autologin();
}

if($request == '/'){
    $CONTENT = 'home';
}else if($request == '/login/'){
    $CONTENT = 'login';
}else if($request == '/register/'){
    $CONTENT = 'register';
}

if(isset($_SESSION['_user'])){
    require __DIR__ . '/templates/roles/user/page.php';
}else{
    require __DIR__ . '/templates/roles/guest/page.php';
}
