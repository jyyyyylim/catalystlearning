<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>Create a new note</title>
    <?php include "./credentials/content-restrict.php"; error_reporting(0);?>
</head>
<style>
    textarea.content {width: 60vw; height: 20vh;}
    input, textarea {margin: 0.6em; padding: 12px; width: 40%; height: 1rem; color: white; background: rgb(40,40,40); border: none;}
</style>
<body>
    <div class="encapsulation central-ctnr">
        <form action="./appIO/add-memo.php" method="POST">
            <h1>Add new memo</h1><br>
            <?php
                require './appIO/return-topics.php';
                echo ('<label for="topic">Choose a topic: </label><select name="topic" id="topic">');
                foreach ($results as $topics){echo("<option value=".$topics.">".$topics."</option>");}
                echo ('</select>');
            ?>
            <input type="text" placeholder="Memo title..." name="title" required="required"><br><hr>
            <textarea class="content" name="content" placeholder="Memo content (HTML styling supported)" required="required"></textarea><br><br>
            <button>Create</button>
            <!--optional: custom checkbox confirmation styling with display none-->
        </form>
    </div>
</body>
</html>