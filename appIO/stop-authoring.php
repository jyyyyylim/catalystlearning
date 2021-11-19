<?php
//scenario3: button exit is called
session_start();

$_SESSION['editmode']= false;
$_SESSION["message"]= "Exited authoring mode.";

$redirect= "homepage.php";
require '../src/bounce.php';

?>