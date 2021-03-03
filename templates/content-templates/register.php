<?php
if(Request::check('post',['name','email','password','repeat_password'])){
    $register = new Register;
    echo $register->registerNewUser($_POST['name'],$_POST['email'],$_POST['password'],$_POST['repeat_password']);
}
?>
<form class="" method="post">
    <input type="text" name="name" value="Remco Smits" placeholder="Name">
    <input type="text" name="email" value="djsmits12@gmail.com" placeholder="Email">
    <input type="password" name="password" value="remcosmits" placeholder="password">
    <input type="password" name="repeat_password" value="remcosmits" placeholder="repeat_password">
    <button type="submit" name="button">registeren</button>
</form>
