<?php

// check for all routes
Route::set('/', function (content $content){
    $content->set('home')->title('Home screen');
})->name('home pagina');

// default denamic route
// Route::set('/remco/{age}', function (content $content){
//     $content->set('home')->title('Remco screen');
// })->name('remco pagina');


// check for specifik routs and urls that can be used but still dynamic
Route::set('/remco', function (content $content){
    $content->set('home')->title('Remco screen');
})->name('remco pagina');

Route::set('/remco/{age}', function (content $content){
    $content->set('home')->title('Remco screen');
})->name('remco pagina')->urls(['/remco/1','/remco/3','/remco/20']);

// routes with dynamic parameters
Route::set('/{uitgaven}/{book}/{chapter}/', function (content $content){
    $content->set('home')->title('Content screen');
});

// middelware for routes that you need to be authenticated
Route::auth(function (){
    Route::set('/logout/', function (content $content){
        $content->set('logout')->title('Logout screen');
    });
});


// redirect to page with route name you can set parameters when your route has dynamic parameters
// Route::view('remco pagina');
// Route::view('remco pagina',[20,'remco']);

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
Route::admin(function(){

});
// show error/404 page if there are no matches to the current url
Route::notFound();
 ?>
