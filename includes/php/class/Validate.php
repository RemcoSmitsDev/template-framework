<?php
/**
 *
 */
class Validate
{
    public static function password(string $userps, string $password, string $salt){
        if($userps === Hash::password($password, $salt)){
            return true;
        }else{
            return false;
        }
    }

    public static function is_email(string $email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
    }

    public static function min_lenght(string $string, int $length = 1){
        if(strlen($string) < $length || empty($string)){
            return false;
        }else{
            return true;
        }
    }

    public static function request(array $requests){
        foreach ($requests as $name => $value) {
            if(!self::min_lenght($value)){
                return false;
            }
        }
        return true;
    }
}


 ?>
