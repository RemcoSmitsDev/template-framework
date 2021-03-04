<?php

// check for all routes

Route::set('/', function (content $content){
    $content->set('home')->title('Home screen');
});

Route::set('/remco/{age}', function (content $content){
    $content->set('home')->title('Remco screen');
})->urls(['/remco/1','/remco/4'])->name('remco');

// middelware for routes that you need to be authenticated
Route::auth(function (){
    Route::set('/logout/', function (content $content){
        $content->set('logout')->title('Logout screen');
    });
});

// view a dynamic url using params
// Route::view('remco',["age"=>1]);

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

// init all routes and choose content by url
Route::dispatch();
 ?>
