<?php

$request = Request::url();


// check for all routes
Route::set('/', function (content $content){
    $content->set('home')->title('Home screen');
});

Route::auth(function (content $content){
    Route::set('/logout/', function (content $content){
        $content->set('logout')->title('Logout screen');
    });
});


// show only if there is no user loggedin
Route::noAuth(function (){
    Route::set('/login/', function (content $content){
        $content->set('login')->title('Login screen');
    });

    Route::set('/register/', function (content $content){
        $content->set('register')->title('Register screen');
    });
});

// group routes inside to can only be access if the user is an admin
// show error/404 page if there are no matches to the current url
Route::notFound();

 ?>
