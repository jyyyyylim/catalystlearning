<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>Add a new topic</title>
    <?php include "./credentials/content-restrict.php";?>
</head>
<style>
    textarea.topicdesc {width: 60vw; height: 20vh;}
    input, textarea {margin: 0.6em; padding: 12px; width: 40%; height: 1rem; color: white; background: rgb(40,40,40); border: none; resize: vertical;}
</style>
<body>
    <div class="encapsulation central-ctnr">
        <form action="./appIO/add-topic.php" method="POST">
            <h1>Add new topic...</h1><br>
            <input type="text" placeholder="Topic title..." name="topic" required="required"><br><hr><br>
            <textarea class="topicdesc" name="description" placeholder="Compulsory description of the topic" required="required"></textarea><br><br>
            <button>Create</button>
            <!--optional: custom checkbox confirmation styling with display none-->
        </form>
    </div>
</body>
</html>