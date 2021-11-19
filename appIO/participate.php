
<!DOCTYPE html>
<html lang="en">
<?php session_start();
error_reporting(1);
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="../style.css">    
    <?php include "../credentials/content-restrict.php";?>


    <title>Quiz: <?php ?></title>
    <style>
        /*cheat: for strict fullscreen pages set overflow: hidden for hassle free scrollbar removal*/
        :root {overflow: hidden;}

        /*styling stack that polishes up an otherwise bland bunch of pagedivs*/
        .pagediv-item {height: 40vh; padding-left: 8%; padding-right: 12%; max-width: 80%; border-bottom: 2px dotted grey;}
        .pagediv-item:nth-child(even) {background-color: var(--container-col);}
        .pagediv-item:nth-child(odd) {background-color: var(--container-col2);}
    
        div.eotl {text-align: center; padding: 2em;}

        .encapsulation {overflow: auto; height: 70vh; padding: 6rem; background-color: var(--background-col)}
        .scrollctnt {font-size: 80px;}

        body > hr:first-of-type {margin: 0px;}

        h1 {text-align: center; padding: 0.5rem;}
        h2 {padding-top: 2rem; font-family: 'Open Sans', sans-serif;}

        input[type=checkbox] {width: 1.2rem; height: 1.2rem; margin: 0.5rem; }

    </style>
</head>
<body>
    <?php echo("<h1>Now completing quiz with ID '{$_GET['quiz']}'.</h1><hr>");   ?>

    
    <form class= "encapsulation" action="./scorecard.php" method="POST">
        <?php        
        //grabs headers (MUST be GET for bookmarking and sharability) 
        //checks headers, significant info: trgt and id

        //returns a dynamic page after quiz content has been processed
        //inputs: get header quiz (id)

        //package quiz data with matching id into arrays
            require '../src/db_socket.php';
            $results= array();

            $operation= "select * from quiz where quizuid='{$_GET['quiz']}'";
            $query= mysqli_query($sock, $operation);
            while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
            $_SESSION['quizid']= $results[0]['quizuid']; 

            //problem: figure out how to safely store "correct" answers
            //the easiest way might be to store the answer array as is but append the amount of correct answers behind
            //simplest way to hook into the existing answer storage method (correct answers first and first n correct answers)
            //in every foreach push this array into a main to be then checked (multidim array)
            
            //predec answer index
            $ans_indx= (int)0;

            //predeclare answer cache ($schema) to be sent off to ./scorecard.php later
            //enforcing sessvar to prevent clientside snooping
            $_SESSION['schema']= array();
            $_SESSION['current_sequence']= array();
            $_SESSION['questions']= array();


            //iter through every match and process further
            foreach ($results as $content){
                $ans_indx++;
                //entering iterations of every question piece
                //step1: retrieve ans content and append ansamt to last, then append to $schema
                //question sequence shuffling is neglected, due to consideration of certain question types relying on the preceding one

                //schemacache has ansnum appended and buffered to $schema, while $options is the array used in the quiz
                //there is no need to declare another var but its only distinguished for maintainability purposes (extensions to option count etc)
                $schema_cache= explode('￼', $content['answers']);
                $options= explode('￼', $content['answers']);

                array_push($schema_cache, $content['ans_qty']);
                array_push($_SESSION['schema'], $schema_cache);
                
                //step2: shuffle answers, buffer into session
                shuffle($options);
                array_push($_SESSION['current_sequence'], $options);
                array_push($_SESSION['questions'], $content['question']);


                //step3: print content (entire content is a form- only concern here is inputs)
                echo("<div class='pagediv-item'>");
                echo("<h2>{$ans_indx}</h2>");
                echo("<h2>{$content['question']}</h2><hr>");


                //st3.1: subroutine: print all questions
                //predec indx for ans checkboxes
                $chk_indx= (int)0;
                foreach($options as $option_indiv){
                    $chk_indx++;
                    echo("<div><input type='checkbox' name='{$ans_indx}-{$chk_indx}' value='{$option_indiv}'>{$option_indiv}</div><br>");
                }
                echo("</div>");
                }
        ?>




        <p>Re-check question progress before submission to prevent careless errors.</p>
        <button>Submit quiz</button>
    </form>


</body>
</html>