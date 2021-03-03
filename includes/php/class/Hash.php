<?php


/**
 * hash
 */
class Hash
{
    public static function password($string, $salt = ''){
        return hash('sha256', $string . $salt);
    }

    public static function salt($length = 60){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function unique(){
        return self::password(uniqid());
    }
}
 ?>
