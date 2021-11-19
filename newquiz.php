<!DOCTYPE html>
<html lang="en">
<?php session_start();
error_reporting(0);
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>Create quiz</title>
    <?php include "./credentials/content-restrict.php";?>


</head>
<style>
    input[type=text], textarea {margin: 0.7em; padding: 12px; width: 60vw; height: 1rem; color: white; background: rgb(40,40,40); border: none;}
    input[type=text] {width: 30vw}    
    input[type=checkbox] {width: 1.2rem; height: 1.2rem; margin-bottom: 0.5rem; }
    textarea {height: 6em; resize: vertical;}
    button {margin: 1.6em; width: 12rem; height: 3.5rem;}
    div.central-ctnr {flex-direction: column; transform: translate(-50%,24%);}
    .pagediv {width: 100%; height: 100%; max-width: 100%;}
</style>
<body>
    <div class="pagediv">
        <div class="encapsulation central-ctnr">
        <?php 
            if(!isset($_SESSION['editmode'])) {$_SESSION['editmode']= false;}
            if ($_SESSION['editmode']=== true){
                $quizname= $_SESSION['quiztitle'];
                $questionnum= $_SESSION['quizamt'];
                echo("<h3>Now editing:</h3> Question $questionnum of quiz <u>$quizname</u>"); 
                echo file_get_contents('./src/quiz-question.html');
            } else {
                require './appIO/return-topics.php';
                echo ('<form action="./appIO/qn-handler.php" method="POST"><h1>Add new quiz...</h1><br><label for="topic">Choose a topic: </label><select name="topic" id="topic">');
                foreach ($results as $topics){echo("<option value=".$topics.">".$topics."</option>");}
                echo ('</select>');
                echo file_get_contents('./src/quiz-details.html');
                echo ('</form>');
            }
        ?>
        </div>
    </div>
</body>
</html>

