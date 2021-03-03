<?php

/**
 *
 */
class Register
{

    private $db;
    private $user;

    function __construct()
    {
        $this->user = new User();
        $this->db = new Database();
    }

    public function registerNewUser(string $name, string $email, string $password, string $repeat_password){
        // validate inputs
        // trim passwords
        $password = trim($password,' ');
        $repeat_password = trim($repeat_password,' ');
        // check if there is an user loggedin
        if(User::is_loggedin()){
            return Response::return('You cant make an account when you are loggedin!',400);
        }
        // check if name is not empty
        if(!Validate::min_lenght($name)){
            return Response::return('Your must passed in an name!',411);
        }
        // check if password is valid
        if(!Validate::min_lenght($password,6)){
            return Response::return('Your password must be longer then 6 characters!',411);
        }
        // check if password is valid
        if(!Validate::min_lenght($repeat_password,6)){
            return Response::return('Your password must be longer then 6 characters!',411);
        }
        // check if the passwords are the same
        if($password !== $repeat_password){
            return Response::return('The password are not the same',411);
        }
        // check if email is valid
        if(!Validate::is_email($email)){
            return Response::return('Your must enter an valid email!',400);
        }
        // check if there exist an user with the email that is passed in
        if($this->user->checkIfUserExist($email)){
            return Response::return('There already exist an user with the passed in email!',409);
        }

        // hash password
        $salt = Hash::salt();
        $this->db->query("INSERT INTO users (Name,Email,Password,Salt) VALUES (:name,:email,:password,:salt)");
        $this->db->bind(":name",$name);
        $this->db->bind(":email",$email);
        $this->db->bind(":password",Hash::password($password,$salt));
        $this->db->bind(":salt",$salt);

        if($this->db->execute()){
            (new Login())->login($email, $password);
            return Response::return('Account created!',202);
        }else{
            return Response::return('Something went wrong when making an account!',400);
        }
    }
}



 ?>
