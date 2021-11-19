<?php
require '../credentials/content-restrict.php';
//adds a new topic to the db.
//reusing database socket
require '../src/db_socket.php';


$topic=$_POST['topic'];
$description=mysqli_real_escape_string($sock, $_POST['description']);

$operation= "insert topic values('$topic','$description')";

if ($sock->query($operation) === true) {
    $_SESSION["message"]= "Topic '{$topic}' has been successfully created.";
} else {
    $_SESSION["message"]= "An error occurred. Please retry the operation.";
}

$redirect= "homepage.php";

//print(mysqli_error($sock));
//trigger url redirect
require '../src/bounce.php';
?>