<?php
//reuse socket code 
require '../src/db_socket.php';

//creds and op type
$handle= $_POST['handle'];
$fullname= $_POST['fullname'];
$email= $_POST['email'];
$conf= $_POST['confpwd'];
$acctype= $_POST['usertype'];

if ($_POST['password'] != $conf){$_SESSION["message"]= "Your password does not match.";} 
else {
    //only do the hash if the password confirmation check passes
    $password= password_hash($_POST['password'], PASSWORD_BCRYPT);
    $operation= "insert account values('$handle','$fullname','$email','$password','$acctype')";

    if ($sock->query($operation) === true) {$_SESSION["message"]= "Success!<br>Account successfully created. <br>Re-attempt the login now.";} 
    else {$_SESSION["message"]= "An error occurred. Please retry the operation.";}
}

$redirect= "registerpage.php";

//trigger url redirect
require '../src/bounce.php';
?>