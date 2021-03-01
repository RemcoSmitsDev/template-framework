<?php


/**
 * Routes
 */
class Route
{
    public $route;

    function __construct()
    {
        $GLOBALS['check'] = false;
    }

    public static function notFound(){
        if(Request::check('global',['check']) && $GLOBALS['check'] === false){
            (new Content)->set('404')->title('404');
            return;
        }
        return false;
    }

    public static function set(string $route, $func){
        // check if the url is passed in is the same as the route
        if(Request::url() === $route){
            $GLOBALS['check'] = true;
            return $func(new Content);
        }
        return false;
    }

    public static function auth($func){
        if(User::is_loggedin() === true){
            return $func(new Content);
        }else{
            return false;
        }
    }

    public static function noAuth($func){
        if(User::is_loggedin() === false){
            return $func(new Content);
        }else{
            return false;
        }
    }

    public static function admin(){
        if(User::is_loggedin() && $_SESSION['_user']->Is_Admin ===  true){
            return $func(new Content);
        }else{
            return false;
        }
    }

    public static function redirect(string $to){
        header('Location: '.$to);
        exit;
    }
}


 ?>
