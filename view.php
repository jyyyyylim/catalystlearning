<!DOCTYPE html>
<html lang="en">
<?php session_start();
//error reporting suppressed only to ignore session init notices
error_reporting(0);
//added header: backlog for score history
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>View...</title>
    <?php include "./credentials/content-restrict.php";?>
    <style>
        /*cheat: for strict fullscreen pages set overflow: hidden for hassle free scrollbar removal*/
        :root {overflow: hidden;}

        /*styling stack that polishes up an otherwise bland bunch of pagedivs*/
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
</head>

<body>
<?php echo("<h1>Now viewing content of category '{$_GET['trgt']}'.</h1><hr>");   ?>

    <div class="encapsulation">
        <?php
            //call a buffering of existing topics to be used in both cases (is and isnt querytarget topic)
            require './appIO/return-topics.php';

            //simplify GET vars
            $querytarget= $_GET['trgt'];
            $subject= $_GET['topic'];

            //init array for conditional topic-header
            $topic_header= array('memos', 'quiz', 'backlog');

            //block handling topic selection in the event $_get['subject'] is unspecified
            //conditional, define pages that require this selection on array $topic_header
            //this layout allows easy control of topic selection bar display
            if (in_array($querytarget, $topic_header)){
                //dynamic topic selection governing topic selection menu
                echo ('<form action="" method="GET"><input type="hidden" id="trgt" name="trgt" value="'.$querytarget.'"><label for="topic">Choose a topic: </label>');
                echo ('<select name="topic" id="topic" onchange="this.form.submit()"><option disabled selected>(undefined)</option><option value="all">all</option>');
                foreach ($results as $topics){echo("<option value=".$topics.">".$topics."</option>");}

                //noscript option fro when js is offlimits
                echo ('</select><noscript><button>Submit</button></noscript></form><hr><br>');
            }

            //this checking block (if querytarget) will be skipped if an "effective query" is built
            //"effective query": when GET headers trgt and topic are populated
            //in this scenario there sould be a query result array which then populates the page by executing display code
            if ($querytarget!= 'topic'){
                //by default no selection defaults to $_GET['trgt']= all
                //no selection is functionally equivalent to unsetted $subject
                //so query target by relevance but return all

                //block handles content array depending on $subject
                if (!isset($subject) or $subject=== 'all'){
                    require './src/db_socket.php';
                    $results= array();
                    if ($querytarget=== 'memos'){
                        $operation= 'select * from article';
                        $query= mysqli_query($sock, $operation);
                        while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
                    }
                    elseif ($querytarget=== 'quiz'){
                        $operation= 'select quiz.handle, quiz.quizuid, quiz.topic, count(quiz.quizuid) as sum, quizname, quizdescription from quiz join quizdetails on quiz.quizuid = quizdetails.quizidentifier group by quiz.quizuid';
                        $query= mysqli_query($sock, $operation);
                        while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
                    }

                    //backlog: quiz history
                    elseif ($querytarget=== 'backlog'){
                        //first phase returns every ques sum
                        $operation= "select quiz.quizuid, count(quiz.quizuid) as sum from quiz group by quiz.quizuid";
                        $query= mysqli_query($sock, $operation);
                        //push quiz amount data into roq $quesamt
                        $quesamt= array();
                        while ($row = mysqli_fetch_assoc($query)) {array_push($quesamt, $row);}

                        //second phase: full query of quiz data (quizdetails) and log, allowing filtering specific user
                        //query returning backlog of current user: $log_data
                        $user= $_SESSION["user"];
                        if ($_SESSION['usrtype']!= '1'){
                            $operation= "select scorecard.handle, scorecard.quizid, scorecard.score, scorecard.record_indx, quiz.topic, quiz.handle as author, quizname, quizdescription from quizdetails inner join scorecard on quizdetails.quizidentifier = scorecard.quizid inner join quiz on quizdetails.quizidentifier = quiz.quizuid where scorecard.handle = '$user' group by scorecard.record_indx";
                        } else {
                            $operation= "select scorecard.handle, scorecard.quizid, scorecard.score, scorecard.record_indx, quiz.topic, quiz.handle as author, quizname, quizdescription from quizdetails inner join scorecard on quizdetails.quizidentifier = scorecard.quizid inner join quiz on quizdetails.quizidentifier = quiz.quizuid group by scorecard.record_indx";
                        }
                        $query= mysqli_query($sock, $operation);
                        $log_data= array();
                        while ($row = mysqli_fetch_assoc($query)) {array_push($log_data, $row);}
                        
                        //third: append when key quizid matches
                        //iter through $log_data, append $quesamt['sum'] where quizuid === $log_data['quizid']
                        //for every entry in %logdata...
                        foreach ($log_data as $logkey => $logcontent){
                            //search through $quesamt
                            foreach ($quesamt as $quesdata){
                                if ($quesdata['quizuid'] === $logcontent['quizid']){
                                    $log_data[$logkey]['ques_amt']= $quesdata['sum'];
                                }}}}
                    
                    elseif ($querytarget=== 'people'){
                        $operation= 'select * from account';
                        $query= mysqli_query($sock, $operation);
                        while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
                    }

                } else {
                    if ($querytarget=== 'memos'){
                        $operation= "select * from article where topic='$subject'";
                        $query= mysqli_query($sock, $operation);
                        //predec $results, due to some unusual issue with inheritances
                        $results= array();
                        while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
                    }
                    elseif ($querytarget=== 'quiz'){
                        $operation= "select quiz.handle, quiz.quizuid, quiz.topic, count(quiz.quizuid) as sum, quizname, quizdescription from quiz join quizdetails on quiz.quizuid = quizdetails.quizidentifier where quiz.topic='$subject' group by quiz.quizuid";
                        $query= mysqli_query($sock, $operation);
                        $results= array();
                        while ($row = mysqli_fetch_assoc($query)) {array_push($results, $row);}
                    }
                    

                    
                    
                    elseif ($querytarget=== 'backlog'){
                        //first phase returns every ques sum
                        $operation= "select quiz.quizuid, count(quiz.quizuid) as sum from quiz group by quiz.quizuid";
                        $query= mysqli_query($sock, $operation);
                        //push quiz amount data into roq $quesamt
                        $quesamt= array();
                        while ($row = mysqli_fetch_assoc($query)) {array_push($quesamt, $row);}

                        //second phase: full query of quiz data (quizdetails) and log, this time with a topic filter
                        //query returning backlog of current user: $log_data
                        $user= $_SESSION["user"];
                        $operation= "select scorecard.handle, scorecard.quizid, scorecard.score, scorecard.record_indx, quiz.topic, quiz.handle as author, quizname, quizdescription from quizdetails inner join scorecard on quizdetails.quizidentifier = scorecard.quizid inner join quiz on quizdetails.quizidentifier = quiz.quizuid where scorecard.handle = '$user' and quiz.topic = '$subject' group by scorecard.record_indx";
                        $query= mysqli_query($sock, $operation);
                        $log_data= array();
                        while ($row = mysqli_fetch_assoc($query)) {array_push($log_data, $row);}
                        
                        //third: append when key quizid matches
                        //iter through $log_data, append $quesamt['sum'] where quizuid === $log_data['quizid']
                        //for every entry in %logdata...
                        foreach ($log_data as $logkey => $logcontent){
                            //search through $quesamt
                            foreach ($quesamt as $quesdata){
                                if ($quesdata['quizuid'] === $logcontent['quizid']){
                                    $log_data[$logkey]['ques_amt']= $quesdata['sum'];
                                }}}}






                    elseif ($querytarget=== 'people'){
                    
                    }}
                
            } else {
                //block of code for topics listing
                echo ('<div class="scrollctnt y-center">');
                foreach ($results as $topics){echo ('<div><input type="radio" value="'.$topics.'" name="topicSel" id="'.$topics.'"/>');echo ('<label for="'.$topics.'">'.$topics.'</label></div>');}echo ('</div>');
            }
            
            //block handling dynamic content, inheriting data from block above
            //forms only for clickables (memos, quiz, profile)
            if ($querytarget=== 'memos'){
                foreach ($results as $content){
                    echo ("<form action='./appIO/reader.php' method='GET' class='pagediv-item'><u><h2>{$content['title']}</h2></u>");
                    echo ("<p>Topic: {$content['topic']}</p><hr><br>memo ID: {$content['id']}");
                    //hiddeninput, so on formsubmit the relevant content will fire
                    echo ("<input type='hidden' name='{$querytarget}' value='{$content['id']}'>");
                    echo ("<br><p>Author: {$content['handle']}</p><button>Read</button>");
                    
                    if ($_SESSION['usrtype'] === '2'){echo ("<button name='delete' value='true'>Delete memo</button>");}
                    echo ("</form>");
                }}

            elseif ($querytarget=== 'quiz'){
                foreach ($results as $content){
                    echo ("<form action='./appIO/participate.php' method='GET' class='pagediv-item'><u><h2>{$content['quizname']}</h2></u>");
                    echo ("<p>Description: {$content['quizdescription']}</p><p>Topic: {$content['topic']}</p><hr><br>Number of questions: {$content['sum']}");
                    echo ("<input type='hidden' name='{$querytarget}' value='{$content['quizuid']}'>");
                    echo ("<br><p>Author: {$content['handle']}</p><button>Join quiz</button></form>");
                }}

            elseif ($querytarget=== 'backlog'){
                //for students to view personal stats
                foreach ($log_data as $content){
                    echo ("<div class='pagediv-item'><u><h2>{$content['quizname']}</h2></u>");
                    echo ("<p>Description: {$content['quizdescription']}</p><p>Topic: {$content['topic']}</p><hr><br><p>Author: {$content['author']}</p>");
                    echo ("<input type='hidden' name='{$querytarget}' value='{$content['quizuid']}'>");
                    echo ("<br><h3>User {$content['handle']} scored: {$content['score']} / {$content['ques_amt']}</h3></div>");
                }}

            elseif ($querytarget=== 'people'){
                //define array where keyvalue corresponds to acctype
                $accounttype= array('student','teacher','admin');
                foreach ($results as $content){

                    $usertype= $accounttype[$content['acctype']];

                    echo ("<form action='./dashboard.php' method='GET' class='pagediv-item'><div class='y-center'><u><h2>{$content['handle']}</u>");
                    echo (" ({$usertype})</h2><hr>");
                    echo ("<input type='hidden' name='handle' value='{$content['handle']}'>");
                    echo ("<br><button>View profile</button></div></form>");
                }}
        
        
        ?>
        <hr><div class="eotl">No more relevant content.</div>
        
    </div>

</body>
</html>

