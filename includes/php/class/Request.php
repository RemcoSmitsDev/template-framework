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

    public static function check($type = 'post', $options = []){
        if(empty($options)){
            return Response::return(false,400);
        }
        switch ($type) {
            case 'post':
                foreach ($options as $value) {
                    if(!isset($_POST[$value])){
                        return Response::return(false,400);
                    }
                }
                break;
            case 'get':
                foreach ($options as $value) {
                    if(!isset($_GET[$value])){
                        return Response::return(false,400);
                    }
                }
                break;
            case 'cookie':
                foreach ($options as $value) {
                    if(!isset($_COOKIE[$value])){
                        return Response::return(false,400);
                    }
                }
                break;
            default:
                return Response::return(false,400);
                break;
        }
        return Response::return(true,200);
    }
}


 ?>
