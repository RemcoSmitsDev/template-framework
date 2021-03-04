<?php
if(Request::check('email','password')){
    $login = new Login;
    echo $login->login($_POST['email'],$_POST['password']);
}
?>

 <div class="login">
     <form class="" method="post">
         <input type="username" name="email" value="" required>
         <input type="password" name="password" value="" required>
         <button type="submit" name="button">Login</button>
         <a href="/register/">Register</a>
     </form>
 </div>
