<?php
//placed on "locked content", in order to block content access from unregistered users.
//included into "locked pages" (dashboard, etc) and operations (quiz/post creation) to provide usertype segmentation.
//classic componentization approach enforced. (setting $redirect var on GET, setting sessVar 'Message' for failure info)

//mechanism: store accounttype/page associations in arrays.
//regex away $_serv['php_self'] to get the dir name, then compare against the check arrays.
//trigger a bounce on a mismatch.

//second half of this cont restriction:
//higher privileges can access low priv pages, lower privileges cannot.
//this makes the check relatively light, needing much fewer rules as one privilege level can be simply ignored
session_start();

//usrtype associations:
//0: student 
//1: teacher/educator
//2: admin

//set array assocs for specially enforced pages (filename only, no dirs, no ext). needs to be manually updated as site is expanded on
//variable variable ($$session_acctype) points back to here
//serves as a whitelist of sorts
$student= array('newmemo', 'newquiz','newtopic', 'content-restrict', 'add-topic','add-memo','qn-handler','stop-authoring','db-socket','escalate');
$teacher= array('newtopic','add-topic','db-socket','escalate', 'content-restrict');
$admin= array();

//set a fixed redir loc for any redirect events
$redirloc= './homepage.php';

//phase 1 check: check if sessVar 'user' is NOT present. lightweight option before segmentation check 
//completely blocks out parts of the site not meant for unregistered users
//this entire script is optional, so this check is a simple #require on pages that only need it
if (!isset($_SESSION["user"])) {
    $_SESSION["message"]= "Restricted content. <br>You have to log-in before you could proceed.";
    $redirect= "registerpage.php";
    header("Location: ./holdingpage.php?redirect={$redirect}");
}

//phase 2 check: enforce user privilege segmentation

//regexp to pick out the filename (no extension) to prepare for the filter comparison
//php_self when #required, returns filename of the current page
//this can be took advantage of
preg_match('/[ \w-]+?(?=\.)/', $_SERVER['PHP_SELF'], $currentpage);


//switches var session_acctype according to id
if($_SESSION["usrtype"]== 0) {$session_acctype= 'student';}
    elseif ($_SESSION["usrtype"]== 1) {$session_acctype= 'teacher';}
    else {$session_acctype= 'admin';}


//admin has absolute privilege, so checks for usrtype 2 is skipped.
//if current session user isnt an admin...
//and if page name is NOT in array $$session_acctype...
//redirect.
if($_SESSION['usrtype'] != 2) {
    //why on earth is currentpage casted as an array
    //at least the index is always 0- the regex only returns 1
    if(in_array($currentpage[0], $$session_acctype)) {
        $_SESSION["message"]= "Insufficient privileges to access the page requested.";
        $redirect= $redirloc;
        header("Location: ./holdingpage.php?redirect={$redirect}");
    } 
}
?>



