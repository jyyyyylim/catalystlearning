<?php
require '../src/db_socket.php';

$victim= $_POST['memos'];
//print_r($_POST);
$operation= "delete from article where id='$victim'";

if ($sock->query($operation) === true) {
    $_SESSION["message"]= "Successfully deleted article with ID $victim.";
} else {
    $_SESSION["message"]= "Error when attempting to delete article.";
}

$redirect= "homepage.php";

//trigger url redirect
require '../src/bounce.php';
?>