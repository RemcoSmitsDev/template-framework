<?php

/**
 * Cookie
 */
class Cookie
{
    public static function set(string $name,string $value){
        self::remove($name);
        setcookie($name, $value,time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    public static function check(){
        $cookies = func_get_args();
        foreach($cookies as $cookie){
            if((!isset($_COOKIE[$cookie])) || empty($_COOKIE[$cookie])){
                return Response::return(false);
            }
        }
        return Response::return(true);
    }

    public static function remove(){
        $cookies = func_get_args();
        foreach($cookies as $cookie){
            unset($_COOKIE[$cookie]);
            setcookie($cookie, null, -1, '/');
        }
    }

    public static function get(string $cookie){
        if(self::check($cookie)){
            return Response::return($_COOKIE[$cookie]);
        }
        return Response::return('');
    }
}



 ?>
