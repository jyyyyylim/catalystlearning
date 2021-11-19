<?php
//documentation and concept is similar to qn-handler so theres no elaboration needed
//fair bit of recycling
require '../src/db_socket.php';

$topic= $_POST['topic'];
$title= $_POST['title'];

$parsedcontent= str_replace(PHP_EOL, "<br>", $_POST['content']);

//due to longtext, $content may contain illegal chars that escape the query string and cause errors
$content= mysqli_real_escape_string($sock, $parsedcontent);

$author= $_SESSION['user'];

$operation= "insert article values(DEFAULT, '$topic', '$author', '$title', '$content')";

if ($sock->query($operation) === true) {
    $_SESSION["message"]= "You have successfully published a note with title '{$title}'.";
} else {
    $_SESSION["message"]= "An error occurred. Please retry the operation.";
}

$redirect= "homepage.php";

//trigger url redirect
require '../src/bounce.php';

?>