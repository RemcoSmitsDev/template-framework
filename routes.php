<?php

$request = Request::url();
$content = new Content;

$check =  false;


// check for all routes
if($request == '/'){
    $content->set('home')->title('Home screen');
}else if($request == '/login/'){
    $content->set('login')->title('Login screen');
}else if($request == '/register/'){
    $content->set('register')->title('Register screen');
}else{
    $content->set('404')->title('404 NOT FOUND');
}


 ?>
