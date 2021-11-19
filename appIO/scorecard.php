<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel= "stylesheet" href="../style.css">    


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

        h1 {padding-top: 2rem;}
        h2 {padding-top: 2rem; font-family: 'Open Sans', sans-serif;}

        input[type=checkbox] {width: 1.2rem; height: 1.2rem; margin: 0.5rem; }

        .correct {background-color: rgba(64 255 0 / 0.2)}
        .wrong {background-color: rgba(255 0 0 / 0.2)}

        .floatbar {text-align: center; position: fixed; bottom
            : 0; width: 100%; font-size: 32px; font-family: 'Open Sans', sans-serif;}

    </style>
</head>
<body>
    <div class="encapsulation">
        <?php
        session_start();
        error_reporting(1);
        //nature of scoring: a "right" for total array match
        //ZERO partiality.

        //scenarios:
        //"correct" response (all returned answers found in ans array)
        //"wrong" response (less/no returned answers found in array)
        //"wrong" response (more answers returned than what is in array)

        //array_diff() could be used except it only checks against 1 direction
        //w3: This function compares the values of two (or more) arrays, and return an array that contains the entries from array1 that are not present in array2 or array3, etc.
        //a significant challenge introduced: the answers marked as correct can be 0 to 4
        //so arrays need to be handled in an order that does not compromise accuracy depending on which array has a greater amount of checks
        //this issue seems to be unavoidable, without defining explicitly a bidirectional array_diff check to account for the "overselection" scenario

        //predefine score and "pointer" vars for user return
        $score= (int)0;
        $ans_indx= (int)1;

        //enter check loop
        //FOREACHing the sessvar $schema array loops for every question
        //no indexing required then, much simpler

        //conveniently (due to sessvar $schema being an array), a foreach makes the logic loop run on a per-question basis
        //original question set from database saved to session to skip a query
        foreach ($_SESSION['schema'] as $schemabuffer){

            //for simplicity normal array manip could be used
            //grabs answer amount parameter (last object in the array regardless of choice amount)
            $answer_amt= end($schemabuffer);
            //and slices the schema partition in a way that only leaves in the correct answers
            $correct_answers= array_slice($schemabuffer, 0, $answer_amt);

            //now move to processing returned answers
            //answers >1 for every question, so divide based on leading number (n-m) key on POST array    
            //define search pattern
            $searchstr= $ans_indx.'-';

            //do an arrayfilter
            //callback func: function USE searchpattern to prevent inheritance issues, stristr to get all keys matching search pattern
            //credits: zimmer, n. in thread https://stackoverflow.com/questions/4260086/php-how-to-use-array-filter-to-filter-array-keys for the idea
            $current_buffer= array_filter(array_keys($_POST),
                function($key) use ($searchstr){
                    return stristr($key, $searchstr);
                });
            
            //intersect op pulling original POST array with the "filter"(matched key), effectively returning per-question answers(checked fields) distinguished/keyed by array
            $current_ans= array_intersect_key($_POST, array_flip($current_buffer));
            
            //now entering answer checking subroutine
            //isemptying an array_diff of $current_ans against $correct_answers
            //this is as a complete match (a "correct") would return an empty array
            //AND op ensuring full accuracy on explicit bidirectional check
            $overselection= array();
            $underselection= array();

            if (empty(array_diff($current_ans, $correct_answers)) and empty(array_diff($correct_answers, $current_ans))){
                $score++; //print("<br>{$ans_indx} is correct<br>");

            } else {
                //on wrong answer: print wrong questions and apply styling accordingly.
                //correct answers determined by arraydiff (keyvalue check/arrayintersect is unviable due to scrambling)
                
                //enter processing to sieve the wrongs
                $overselection= array_diff($current_ans, $correct_answers);
                $underselection= array_diff($correct_answers, $current_ans);

                //now that the 2 states of "wrongs" are distinguished, further set ops/comps can be done to determine the nature of error

                //if array overselection is empty: answer has been underselected
                //if array underselection is empty: answer has been overselected
                //if both arrays are populated, no intersection: "unusual" scenario, can imply both a partial or total wrong
                //in that case if               array len < ans len: partial
                //and if                        array len = ans len: total


                //issue 2.1: how to derive the exact errors then?
                //in the underselected scenario: mismatches are whatever is in the $overselect array
                //in the overselected scenario: mismatches are whatever is in the $underselect array
                
                //hence...
                //move down into yet another subroutine, this time to iterate through the answer-layer

            }
                echo("<div class='pagediv-item'>");
                echo("<h1>{$ans_indx}</h1>");
                echo("<h2>{$_SESSION['questions'][$ans_indx-1]}</h2>");
                if ($answer_amt == 0){
                    echo("<p>(No correct answer specified by author)</p><hr>");
                } else {echo("<hr>");}


                //context: still within the ELSE structure, so only iterated through on a wrong answer
                //still iterating through answerset for the current question, now in a loop for answersequence  
                $ans_option_indx=(int)1;
                foreach ($_SESSION['current_sequence'][$ans_indx-1] as $ans_sequence){

                    //we need to keep states for answer returns (retain checked attr if user checks)
                    //currentans is an array with unconventional keys
                    $checked='';
                    if (isset($current_ans[$ans_indx."-".$ans_option_indx])) {$checked= 'checked';}

                    if (empty($overselection)){
                        //in the event of underselection
                        if (in_array($ans_sequence, $underselection)){
                            echo("<div class='wrong'><input type='checkbox' $checked disabled='disabled' >$ans_sequence</div>");
                    
                        } elseif (in_array($ans_sequence, $correct_answers)){
                            echo("<div class='correct'><input type='checkbox' $checked disabled='disabled'>$ans_sequence</div>");
                    
                        } else {echo("<div><input type='checkbox' disabled='disabled'>$ans_sequence</div>");}
                    } else {
                        //in the event of overselection and edgecases
                        if (in_array($ans_sequence, $overselection)){
                            echo("<div class='wrong'><input type='checkbox' $checked disabled='disabled'>$ans_sequence</div>");
                    
                        } elseif (in_array($ans_sequence, $correct_answers)){
                            echo("<div class='correct'><input type='checkbox' $checked disabled='disabled'>$ans_sequence</div>");
                    
                        } else {echo("<div><input type='checkbox' disabled='disabled'>$ans_sequence</div>");}
                    } 
                    //end of subloop- increment question option pointer
                    ++$ans_option_indx;
                }

                echo("</div>");
                //end of loop- increment question pointer
                $ans_indx++;
        }

        //return computed score
        $_SESSION['quizscore']= $score;
        print("<form class='floatbar' action='./commitscore.php'>The quiz has been marked. Your scored $score out of ".sizeof($_SESSION['schema'])."<button>Return</button></div>");

        //optimization: do the exact error checking inside the loop
        //with defined vars to be reused by the subroutine= no need for recalculation

        ?>
    </div>
</body>
</html>