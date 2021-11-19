<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel= 'stylesheet' href='./style.css'>    
    <title>Account settings</title>
</head>
<style>
    .central-ctnr {display: block; transform: translate(-50%,100%);}
    .backdrop {padding: 3rem; background-color: var(--container-col);}
    input:not([type=radio]) {margin: 0.6em; padding: 12px; width: 70%; height: 1rem; color: white; background: rgb(40,40,40); border: none;}


    .pagediv-item {height: 23rem; padding-left: 10%; padding-right: 10%; max-width: 80%; border-bottom: 2px dotted grey;}
    .pagediv-item:nth-child(even) {background-color: var(--container-col);}
    .pagediv-item:nth-child(odd) {background-color: var(--container-col2);}

    div.eotl {text-align: center; padding: 2em;}

    .encapsulation {overflow: auto; height: 70vh; padding: 6rem; background-color: var(--background-col)}
    .scrollctnt {font-size: 80px;}

    body > hr:first-of-type {margin: 0px;}

    h1 {text-align: center; padding: 1rem;}
    h2 {padding-top: 1.5rem; font-family: 'Open Sans', sans-serif;}

    .pagediv-item > .y-center {font-family: 'Open Sans', sans-serif; font-size: initial;  text-shadow: none;}
</style>
<body>

        
    <?php
    //backlog: profile, accesscontrol, settings

    session_start();
    //suppressing session notifs
    error_reporting(0);
    //read from get headers
    //sql command update
    //!!!!! dashboard.php
    if (!isset($_SESSION['isSudo'])) {
        header('Location: ./credentials/reauth.php');

    } elseif ($_SESSION['usrtype'] === '2'){
        require './src/db_socket.php';

        $operation= "select * from account where acctype = '1'";
        $query= mysqli_query($sock, $operation);
        $results= array();

        while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
        $accounttype= array('student','teacher','admin');
        foreach ($results as $content){
            $usertype= $accounttype[$content['acctype']];

            echo ("<form action='./credentials/escalate.php' method='POST' class='pagediv-item'><div class='y-center'><u><h2>{$content['handle']}</u>");
            echo (" ({$usertype})</h2><hr>");
            echo ("<input type='hidden' name='handle' value='{$content['handle']}'>");
            echo ("<br><button>Escalate permissions</button></div></form>");}


    } else {
        echo ("<div class='central-ctnr backdrop'>");
        echo ("<h1>Populate the fields you wish to amend.</h1>");
        echo ("<form action='./credentials/amend.php' method='POST'>
        <input type='text' name='email' placeholder='e-mail address'><br>
        <input type='password' name='password' placeholder='change current password' pattern='(pass)|.{8,}' title='Your entry must exceed 8 characters'><br>
        <input type='password' name='confpwd' placeholder='confirm change' pattern='(pass)|.{8,}' title='Your password probably doesn't match.'><br>
        <button>Submit</button></form></div>");
    }




    ?>
</body>
</html>

