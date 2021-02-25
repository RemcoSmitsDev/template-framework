<?php
require_once(__dir__."/Database.php");
/**
 * Login class
 */
class Login
{
    private $db;
    private $token;
    private $user;
    private $email;
    private $password;

    function __construct()
    {
        $this->db = new Database();
    }

    private function createToken(int $str_length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./|}{+=#@!$%^&*()-[]?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i <= $str_length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function updateToken(){
        $this->token = $this->createToken(60);

        $this->db->query("UPDATE users SET Token = :token WHERE Email = :email LIMIT 1");
        $this->db->bind(':token',$this->token);
        $this->db->bind(':email',$this->email);
        $this->db->execute();

        $this->setAutoLoginCookie();

        return $this->token;
    }

    public function getUserByEmail(string $email){
        // get user by email
        $this->db->query("SELECT * FROM users WHERE Email = :email LIMIT 1");
        $this->db->bind(':email',$email);

        if($this->db->rowCount() > 0){
            http_response_code(200);
            return $this->db->single();
        }else{
            http_response_code(400);
            return false;
        }
    }

    public function login(string $email, string $password){
        // check if user is not loggedin already
        if(isset($_SESSION['_user'])){
            return 'You are already loggedin!';
        }
        // check if the emails is valid
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            http_response_code(400);
            return 'Not a valid email';
        }

        // password must be longer then 6 characters
        if(strlen($password) < 6){
            http_response_code(400);
            return 'Password length';
        }

        // check if user exist by email
        if(!$this->user = $this->getUserByEmail($email)){
            http_response_code(400);
            return 'No user found';
        }

        $this->email = $email;
        $this->password = $password;

        // set session logged in succesfully
        if($this->validatePassword()){
            $this->updateUserToSession();
            $this->updateToken();
            http_response_code(200);
            return 'loggedin';
        }else{
            http_response_code(404);
            return 'wrong password by email';
        }
    }

    private function validatePassword(){
        if(password_verify($this->password . $this->user->Salt, $this->user->Password)){
            return true;
        }else{
            return false;
        }
    }

    private function checkUserWithToken(){

        $this->db->query("SELECT * FROM users WHERE Email = :email AND Token = :token LIMIT 1");
        $this->db->bind(':email',$this->email);
        $this->db->bind(':token',$this->token);

        if($this->db->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    protected function loginUsingCookieToken() {
        // check if there exist an user with this email
        if(!$this->user = $this->getUserByEmail($this->email)){
            http_response_code(404);
            return 'no user found';
        }

        if($this->checkUserWithToken()){
            $this->updateUserToSession();
            $this->updateToken();
            return true;
        }else{
            return false;
        }
    }

    public function updateUserToSession(){
        $_SESSION['_user'] = $this->user;
    }

    public function setAutoLoginCookie(){
        setcookie("email", $this->email,time() + (10 * 365 * 24 * 60 * 60), "/");
        setcookie("token", $this->token,time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    public function autologin(){
        // check if there is a user logged in
        if(isset($_SESSION['_user'])){
            return false;
        }
        // check if the user hash auto login checked
        if(!isset($_COOKIE['email'],$_COOKIE['token']) && empty($_COOKIE['email']) || empty($_COOKIE['token'])){
            return false;
        }

        // login user using the token and email in hit cookie
        $this->token = $_COOKIE['token'];
        $this->email = $_COOKIE['email'];

        if($this->loginUsingCookieToken()){
            // logged in
            return true;
        }else{
            // wrong credentials
            return false;
        }
    }
}


 ?>
