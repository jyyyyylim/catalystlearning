<?php

session_start();
error_reporting(0);
//simple lightweight solution: only check isset on fields
//update accordingly from then
$conf= $_POST['confpwd'];
$user= $_SESSION["user"];


if ($_POST['password'] != $conf){
    $_SESSION["message"]= "Your password does not match.";
    $redirect= "settings.php";
    
} else {

    $fields= array('email','password');
    $alterations= array();
    
    //forloop, checking POST array for differences
    foreach ($fields as $postdata){if (!empty($_POST[$postdata])) {array_push($alterations, $postdata);}}

    if(in_array('password', $alterations)){
        $_POST['password']= password_hash($_POST['password'], PASSWORD_BCRYPT);
    }
    
    //forloop, now building single query depending on forloop
    $operation= "update account set ";
    foreach ($alterations as $amendments){
        $operation.= "$amendments= '$_POST[$amendments]' ";
    }
    $operation.= "where handle='$user'";
    //query built, now executing
    require '../src/db_socket.php';
    $query= mysqli_query($sock, $operation);

    if ($sock->query($operation) === true) {
        $_SESSION["message"]= "Successfully changed your credentials.";
    } else {
        $_SESSION["message"]= "Error when attempting to commit your changes.";
    }
}

$redirect= "homepage.php";

//trigger url redirect
require '../src/bounce.php';

?>