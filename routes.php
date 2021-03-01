<?php

$request = Request::url();


// check for all routes
Route::set('/', function (content $content){
    $content->set('home')->title('Home screen');
});

// show only if there is no user loggedin
Route::auth(function (){
    Route::set('/login/', function (content $content){
        $content->set('login')->title('Login screen');
    });

    Route::set('/register/', function (content $content){
        $content->set('register')->title('Register screen');
    });
},false);

// group routes inside to can only be access if the user is an admin
// show error/404 page if there are no matches to the current url
Route::notFound();

 ?>
