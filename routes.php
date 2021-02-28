<?php

$request = Request::url();

$check =  false;

// check for all routes
if($request == '/'){
    Content::set('home');
}else if($request == '/login/'){
    Content::set('login');
}else if($request == '/register/'){
    Content::set('register');
}else{
    Content::set('404');
}


 ?>
