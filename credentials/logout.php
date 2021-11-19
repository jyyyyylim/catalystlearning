<?php
session_start();

foreach ($_SESSION as $key => $value){
    if ($key != 'message'){unset($_SESSION["$key"]);}
}

//due to use of session for message on holdingpage, session_destroy is unviable 


$_SESSION["message"]= "Success. You are now logged out.";
$redirect= "registerpage.php";

require '../src/bounce.php';

?>