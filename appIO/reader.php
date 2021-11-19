<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    error_reporting(0);
    require '../src/db_socket.php';

    $article= $_GET['memos'];
    $operation= "select * from article where id = '$article'";
    
    $query= mysqli_query($sock, $operation);
    $row = mysqli_fetch_assoc($query);

    if(mysqli_num_rows($query)==0){
        $_SESSION["message"]= "No article corresponding to ID found.";
        $redirect= "./view.php?trgt=memos";
        require '../src/bounce.php';
    }
    
    ?>
    <title>Writeup: <?php echo($row['title']);?></title>
    <link rel= "stylesheet" href="../style.css">    
    <?php include "../credentials/content-restrict.php";?>
</head>

<style>
    .central-ctnr {display: block; transform: translate(-50%,20%);}
    .encapsulation {padding: 3rem;}
    aside {background: var(--container-col2); padding: 2rem; text-align: left; overflow-y: scroll; height: 50vh;}
    aside::after {color: rgba(64 255 0 / 0.6) ;content: "(You have reached the end of this writeup.)"}

    .deletenotice {position: absolute; z-index: 4;}
</style>

<body>
    <?php
        if (isset($_GET['delete'])){
            if ($_SESSION['usrtype'] === '2'){
                if (!isset($_SESSION['isSudo'])) {
                    header('Location: ../credentials/reauth.php');
                } else {
                    echo ("<form class='deletenotice' action='./deletememo.php' method='POST'><h2>Do you wish to delete this article?</h2><i>This action cannot be undone.</i>");
                    echo ("<input type='hidden' name='memos' value='{$article}'>");
                    echo ("<br><button>Yes</button></form>");

                }
            } 
        }
    
    ?>
    <div class="central-ctnr encapsulation"><?php echo('<h1><u>'.$row['title'].'</u></h1>Authored by: '.$row['handle'])?><hr><br>
        <aside class="">
            <?php echo($row['content'])?>
            <br><br>
        </aside>
    </div>
</body>
</html>