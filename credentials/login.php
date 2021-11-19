<?php
//reuse socket code from file
require '../src/db_socket.php';

//define aliases from POST req content
$name= $_POST['username'];
$password= $_POST['password'];

$operation= "select * from account where handle='$name'";
$query= mysqli_query($sock, $operation);

//does query with info pieces stated above
if(mysqli_num_rows($query)==1) {
    $query_result= mysqli_fetch_assoc($query);
    $hash= $query_result['password'];

    //function returns true if correct
    if (password_verify($password, $hash)){

        //array vars are casted to respective sessvars, for login verification
        $_SESSION["usrtype"]= $query_result["acctype"];
        $_SESSION["user"]= $query_result["handle"];
        //set sessvar message and var redirect, to be passed to holdingpage
        $_SESSION["message"]= "Success!<br>Successful login.";
        $redirect= "homepage.php";
    }} 
    else {
        $_SESSION["message"]= "Incorrect username or password.";
        $redirect= "registerpage.php";
    }


//call bounce.php, creating the http header and triggering the reroute

require '../src/bounce.php';
?>