<?php
require_once('includes/php/functions.php');
//Get request URI
$request = $_SERVER['REQUEST_URI'];

//Enable get
$arr = explode('?', $request, 2);
$request = $GLOBALS['request'] = $arr[0];

global $CONTENT;
$check =  false;

// auto login function if there is no user logged in
if(!User::is_loggedin()){
    Login::Autologin();
}else{
    echo "loggedin";
}

if($request == '/'){
    $CONTENT = 'home';
}else if($request == '/login/'){
    $CONTENT = 'login';
}else if($request == '/register/'){
    $CONTENT = 'register';
}else{
    $CONTENT = '404';
}

if(isset($_SESSION['_user'])){
    require __DIR__ . '/templates/roles/user/page.php';
}else{
    require __DIR__ . '/templates/roles/guest/page.php';
}
