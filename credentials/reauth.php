<?php
    if (isset($_POST['confpwd'])){
        require '../src/db_socket.php';

        $name= $_SESSION['user'];
        $password= $_POST['confpwd'];
        $operation= "select * from account where handle='$name'";

        $query= mysqli_query($sock, $operation);

        //does query with info pieces stated above
        if(mysqli_num_rows($query)==1) {
            $query_result= mysqli_fetch_assoc($query);
    
            $hash= $query_result['password'];
            //function returns true if correct
            if (password_verify($password, $hash)){
                $_SESSION['isSudo']= true;
                $_SESSION["message"]= "Successful verification.<br> You will not be prompted again for your password for the rest of this session.";
            } else {
                $_SESSION["message"]= "Verification failed.";
            }

        $redirect= "homepage.php";

        require '../src/bounce.php';
    }}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="../style.css">    
    <title>Document</title>
</head>
<style>
    .central-ctnr {display: block; transform: translate(-50%,100%);}
    .backdrop {padding: 3rem; background-color: var(--container-col);}
    input:not([type=radio]) {margin: 0.6em; padding: 12px; width: 70%; height: 1rem; color: white; background: rgb(40,40,40); border: none;}
</style>
<body>
    <div class='central-ctnr backdrop'>
        <h2>Now entering sudo mode, you are required to re-verify your password.</h2><hr>
        <form action='' method='POST'>
        <input type='hidden' name='reauth' value='yes'>
        <input type='password' name='confpwd' placeholder='password' pattern='(pass)|.{8,}' title="Your password probably doesn't match."><br>
        <button>Submit</button></form></div>
</body>
</html>

