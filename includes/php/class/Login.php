<?php
/**
 * Login class
 */
class Login
{
    private $db;
    private $_token;
    private $_user;
    private $_email;
    private $_password;
    private $user;

    function __construct()
    {
        $this->db = new Database;
        $this->user = new User;
    }

    private function updateToken(){
        $new_token = Hash::unique();

        $this->db->query("UPDATE users SET Token = :token WHERE Email = :email LIMIT 1");
        $this->db->bind(':token',$new_token);
        $this->db->bind(':email',$this->_email);
        $this->db->execute();

        User::updateTokenSession($this->_email,$new_token);

        return $new_token;
    }

    public function login(string $email, string $password){
        // check if user is not loggedin already
        if(User::is_loggedin()){
            return Response::return('You are already loggedin!',200);
        }

        // check if the emails is valid
        if(!Validate::is_email($email)){
            return Response::return('Not a valid email!',400);
        }

        // password must be longer then 6 characters
        if(!Validate::min_lenght($password,6)){
            return Response::return('Password length must be longer then 6 characters!',400);
        }

        // check if user exist by email

        if(!$this->_user = $this->user->getUserByEmail($email)){
            return Response::return('No user found!',400);
        }

        $this->_email = $email;
        $this->_password = $password;

        // set session logged in succesfully
        if(Validate::password($this->_user->Password,$password,$this->_user->Salt)){
            User::updateUserSession($this->_user);
            $this->updateToken();
            return Response::return('loggedin');
        }else{
            return Response::return('Wrong password by email!',404);
        }
    }

    public function checkUserWithToken(){

        $this->db->query("SELECT * FROM users WHERE Email = :email AND Token = :token LIMIT 1");
        $this->db->bind(':email',$this->_email);
        $this->db->bind(':token',$this->_token);

        if($this->db->rowCount() > 0){
            return Response::return(true);
        }else{
            return Response::return(false);
        }
    }

    protected function loginUsingCookieToken() {
        // check if there exist an user with this email
        if(!$this->_user = $this->user->getUserByEmail($this->_email)){
            return Response::return('No user found!',404);
        }

        if($this->checkUserWithToken()){
            User::updateUserSession($this->_user);
            return Response::return(true);
        }else{
            return Response::return(false,400);
        }
    }

    public function autologin(){
        // check if there is a user logged in
        if(User::is_loggedin()){
            return Response::return('You are already loggedin!');
        }
        // check if the user hash auto login checked from cookie
        if(!Cookie::check('email','token')){
            return Response::return('');
        }

        $this->_email = Cookie::get('email');
        $this->_token = Cookie::get('token');

        // logged in
        if($this->loginUsingCookieToken()){
            $this->updateToken();
            User::updateUserSession($this->_user);
            return Response::return('loggedin');
        }else{
            // wrong credentials
            return Response::return('failed with your token!',400);
        }
    }
}


 ?>
