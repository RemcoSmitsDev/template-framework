<?php


/**
 * hash
 */
class Hash
{
    public static function password(string $string, string $salt = ''){
        return hash('sha256', $string . $salt);
    }

    public static function salt(int $length = 60){
        return random_bytes($length);
    }

    public static function unique(){
        return self::password(uniqid());
    }
}
 ?>
