<?php
    require '../src/db_socket.php';
    $targetuser= $_POST['handle'];

    $operation= "update account set acctype= '2' where handle = '$targetuser'";

    if ($sock->query($operation) === true) {
        $_SESSION["message"]= "User $targetuser has been elevated. Exercise privileges with caution.";
    } else {
        $_SESSION["message"]= "An error occurred when trying to elevate the user. Verify database connectivity.";
    }
    
    $redirect= "settings.php";
    
    //trigger url redirect
    require '../src/bounce.php';

?>