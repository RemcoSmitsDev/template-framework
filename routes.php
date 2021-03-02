<?php

$request = Request::url();

// check for all routes
Route::set('/', function (content $content){
    $content->set('home')->title('Home screen');
});

// routes with dynamic parameters
// Route::set('/{uitgaven}/{book}/{chapter}', function (content $content){
//     $content->set('home')->title('Content screen');
// });

Route::auth(function (){
    Route::set('/logout/', function (content $content){
        $content->set('logout')->title('Logout screen');
    });
});

// middelware for routes that can only be accessed when there is no user loggedin
Route::noAuth(function (){
    Route::set('/login/', function (content $content){
        $content->set('login')->title('Login screen');
    });

    Route::set('/register/', function (content $content){
        $content->set('register')->title('Register screen');
    });
});

// middelware for routes that can only be accessed if the user is an admin
// Route::admin(function(){
//
// });
// show error/404 page if there are no matches to the current url
Route::notFound();

 ?>
