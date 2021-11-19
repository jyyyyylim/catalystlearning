<?php
    //final: commit the record to db
    require '../src/db_socket.php';
    $handle= $_SESSION['user'];
    $quizid= $_SESSION['quizid'];
    $score= $_SESSION['quizscore'];

    $operation= "insert scorecard values('$handle', '$quizid', DEFAULT, '$score')";
    if ($sock->query($operation) === true) {
        $_SESSION['message']= "Your results have been recorded.";
    } else {
        $_SESSION['message']= "There is an unknown issue with the database. Your results were not recorded.";
    }

    //end of script: dump sessvars 
    foreach ($_SESSION as $key=>$val){
        if ($key!='user' or $key!='usrtype'){unset($key);}
    }

    $redirect= "homepage.php";

    //trigger url redirect
    require '../src/bounce.php';
?>