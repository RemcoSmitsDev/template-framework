<?php

/**
 * checkes all the request
 */
class Request
{
    public static function validate(){
        $args = func_get_args();
        foreach ($args as $key => $value) {
            // if there is a value empty return false
            if(!Validate::min_lenght($value)){
                return Response::return(false,400);
            }
        }
        return Response::return(true,200);
    }

    public static function check(){
        $options = func_get_args();
        if(empty($options)){
            return Response::return(false);
        }
        if(is_string($options)){
            $options = [$options];
        }
        switch (strtolower($_SERVER['REQUEST_METHOD'])) {
            case 'post':
                foreach ($options as $value) {
                    if(!isset($_POST[$value])){
                        return Response::return(false);
                    }
                }
                break;
            case 'get':
                foreach ($options as $value) {
                    if(!isset($_GET[$value])){
                        return Response::return(false);
                    }
                }
                break;
            case 'global':
                foreach ($options as $value) {
                    if(!isset($GLOBALS[$value])){
                        return Response::return(false);
                    }
                }
                break;
            case 'session':
                foreach ($options as $value) {
                    if(!isset($_SESSION[$value])){
                        return Response::return(false);
                    }
                }
                break;
            default:
                return Response::return(false);
                break;
        }
        return Response::return(true);
    }

    public static function get(string $type, string $name){
        switch ($type) {
            case 'post':
                return $_POST[$name];
                break;
            case 'get':
                return $_GET[$name];
                break;
            case 'global':
                return $GLOBALS[$name];
                break;
            default:
                return '';
                break;
        }
    }

    public static function url(){
        return urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
}


 ?>
