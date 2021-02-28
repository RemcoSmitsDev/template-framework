<?php
require_once(__DIR__.'/includes/php/functions.php');

// auto login function if there is no user logged in and check if there is an for email and token
if(!User::is_loggedin() && Cookie::check('token','email')){
    (new Login)->Autologin();
}

// check all routes
require_once(__DIR__.'/routes.php');

// check if there is a user loggedin then show the right layout
if(User::is_loggedin()){
    require_once(__DIR__ . '/templates/roles/user/page.php');
}else{
    require_once(__DIR__ . '/templates/roles/guest/page.php');
}
