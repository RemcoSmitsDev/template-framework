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
        unset($_SESSION['_user']);
        session_destroy();
        Cookie::remove('email','token');
        Route::redirect("/login/");
        exit;
    }

    public static function updateUserSession($user){
        $_SESSION['_user'] = $user;
    }

    public static function updateTokenSession(string $email, string $token){
        Cookie::set('email',$email);
        Cookie::set('token',$token);
    }

    public function getUserByEmail(string $email){
        $this->db->query("SELECT * FROM users WHERE Email = :email LIMIT 1");
        $this->db->bind(':email',$email);

        if($res = $this->db->single()){
            return Response::return($res,200);;
        }else{
            return Response::return(false,400);
        }
    }

    public static function is_loggedin(){
        if(Request::check('session',['_user'])){
            return Response::return(true);
        }else{
            return Response::return(false);
        }
    }

    public function removeAccount(){
        // check if there is a user loggedin
        if(!self::is_loggedin()){
            return Response::return('You must be loggedin!',400);
        }

        // check if there are some changes
        $this->db->query('DELETE users WHERE Id = :user_id');
        $this->db->bind(':user_id',$_SESSION['_user']->Id);

        // exec query
        if($this->db->execute()){
            Cookie::remove('email','token');
            self::logout();
            return Response::return('Account verwijderd!',200);
        }else{
            return Response::return('Something went wrong when deleting your account!',400);
        }
    }
}


 ?>
