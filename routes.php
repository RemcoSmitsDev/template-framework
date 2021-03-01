<?php

$request = Request::url();


// check for all routes
Route::set('/', function (content $content){
    $content->set('home')->title('Home screen');
});

Route::set('/login/', function (content $content){
    $content->set('login')->title('Login screen');
});

Route::set('/register/', function (content $content){
    $content->set('register')->title('Register screen');
});

// group routes to can only be access if the user is an admin
// Route::group('auth', function (){
//
// });
// show error/404 page if there are no matches to the current url
Route::notFound();

 ?>
