<?php
//stateful script keeping track of the "stages" of quiz post transactions, aided by sessvars
//'cyclic' in nature- dynamic html updating prompting topic and subject (and desc?)
//executes on every page load- every page load is a "submission" of a quiz question/answers pairing into db 
//this layout effectively divides POST requests dedicated to every added "segment" of the quiz...
//...bypassing the need for HTTP modification, and by extension, frameworks

//stage 1: prompt basic information 
//stage 2 ad infinitum: question/answer POST loop
//stage 3: set the var to null
//null: effectively a dropout, redirect to s1
//since sessvars are per-user by nature, this prevents more basic forms of simultaneous access... maybe. (determined- not a concern)

//!!! for a dramatic simplification of implementation this 3 states can be simply represented in bool
//editmode, its either a yes or no
//following this plan, this is a bistable mechanism

//2 buttons, 1 triggering an exit and the other 'continuing' the loop 

//sessvars involved:
//user handle (already set)
//stage (1 - 2)


//special notes:
//blocking system can be implemented, where 1 user only can create 1 quiz at a time 

require '../src/db_socket.php';


if ($_SESSION['editmode'] === true){
    //first phase: total "correct" answers present

    //marked answer check: preload array
    $marked= array('ans1', 'ans2', 'ans3', 'ans4');
    $answers= array();
    $buffer= array();

    //...foreach the defined array against every possible field with isset
    $ans_amt= 0;
    foreach ($marked as $position=> $check) {
        if (isset($_POST[$check])) {
            //print ("{$check} is a correct answer <br>");
            
    //second phase: package answers in an array, and reorder "correct" answers to the first $ans_amt index 
            array_push($answers, $_POST['a'.$position]);
            //print_r($answers);
            //print("<br>");
            ++$ans_amt;
        } else {
            array_push($buffer, $_POST['a'.$position]);
        }}

    $answerstring= array_merge($answers, $buffer);
    //print_r($answerstring);


    //third phase: construct the query 
    //array needs a implode//explode before storing as str
    //use a conventionally unusual character to prevent input fouling
    //needs pairing with userend regex for doubled security
    $db_ans= mysqli_real_escape_string($sock, implode('￼', $answerstring));

    $handle= $_SESSION['user'];
    $quizuid= $_SESSION['quizindex'];
    $question= mysqli_real_escape_string($sock, $_POST['question']);
    $topic= $_SESSION['quiztopic'];


    //topic 	handle 	quizuid 	question 	ans_qty 	answers 	
    //quiz-id 	quizname 	quizdescription 	
    $operation= "insert quiz values('$topic', '$handle', '$quizuid', '$question', '$ans_amt', '$db_ans')";
    print($operation);

    if ($sock->query($operation) === true) {
        ++$_SESSION['quizamt'];
        $_SESSION["message"]= "Question added. Please wait...";
    } else {
        $_SESSION["message"]= "An error occurred. Please retry the operation.";
    }
    $redirect= "newquiz.php";

}

//checks directly control submit-qn and dynamic html returns


//post data comes in, and answers marked as correct by nature are unpredictable

//solution:
//check for marked answers, sum the marked options 
//sort marked answers to first n index of values, using array for maintainability
//build the query
//submit it (executes on every new question added)

//sessvar editmode false- this is executed when quiz is in stage 1
if ($_SESSION['editmode'] === false){
    
    //this sessvar is a bool, so just flip the state
    $_SESSION['editmode']= !$_SESSION['editmode'];
    
    $quizname= mysqli_real_escape_string($sock, $_POST['quiztopic']);
    $quizdesc= mysqli_real_escape_string($sock, $_POST['quizdesc']);
    $_SESSION['quiztopic']= $_POST['topic'];
    $_SESSION['quiztitle']= $quizname;

    $operation= "insert quizdetails values(DEFAULT, '$quizname', '$quizdesc')";
    
    if ($sock->query($operation) === true) {
        $operation= "select * from quizdetails where quizname='$quizname'";

        $query= mysqli_query($sock, $operation);
        $row = mysqli_fetch_assoc($query);

        $_SESSION['quizindex']= $row['quizidentifier'];
        $_SESSION["message"]= "Quiz created successfully. <br>Please wait...";
        $_SESSION['quizamt']= 1;
    } else {
        $_SESSION["message"]= "An error occurred. Retry the operation.";
    }


    $redirect= "newquiz.php";

    //echo($_SESSION['editmode']);
}

//trigger url redirect
//print(mysqli_error($sock));
require '../src/bounce.php';

?>