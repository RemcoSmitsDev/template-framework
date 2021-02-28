<?php

/**
 *
 */
class User
{

    private $db;


    function __construct()
    {
        $this->db = new Database;
    }

    public function checkIfUserExist(string $email){
        $this->db->query("SELECT Id FROM users WHERE Email = :email");
        $this->db->bind(":email",$email);

        if($this->db->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function logout(){
        session_destroy();
        unset($_SESSION['_user']);
        self::removeUserToken();
        header("Location: /login/");
        exit;
    }

    public static function removeUserToken(){
        unset($_COOKIE['email']);
        unset($_COOKIE['token']);
        setcookie('email', null, -1, '/');
        setcookie('token', null, -1, '/');
    }

    public static function updateUserSession($user){
        $_SESSION['_user'] = $user;
        self::updateTokenSession($user->Email,$user->Password);
    }

    public static function updateTokenSession(string $email, string $token){
        setcookie("email", $email,time() + (10 * 365 * 24 * 60 * 60), "/");
        setcookie("token", $token,time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    public function getUserByEmail(string $email){
        $this->db->query("SELECT * FROM users WHERE Email = :email LIMIT 1");
        $this->db->bind(':email',$email);

        if($res = $this->db->single()){
            http_response_code(200);
            return $res;
        }else{
            http_response_code(400);
            return false;
        }
    }

    public static function is_loggedin(){
        if(isset($_SESSION['_user'])){
            return true;
        }else{
            return false;
        }
    }

    public function removeAccount(){
        // check if there is a user loggedin
        if(!isset($_SESSION['_user'])){
            return Response::return('You must be loggedin!ß',400);
        }

        // check if there are some changes
        $this->db->query('DELETE users WHERE Id = :user_id');
        $this->db->bind(':user_id',$_SESSION['_user']->Id);

        // exec query
        if($this->db->execute()){
            self::removeUserToken();
            self::logout();
            return Response::return('Account verwijderd!',200);
        }else{
            return Response::return('Something went wrong when deleting your account!',400);
        }
    }
}


 ?>
