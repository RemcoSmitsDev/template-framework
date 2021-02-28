<?php

/**
 * response
 */
class response
{
    public static function return($value,int $code = 200){
        http_response_code($code);
        return $value;
    }
}


 ?>
