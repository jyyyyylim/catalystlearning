<!DOCTYPE html>
<html lang="en">
    <?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>Status page</title>
</head>
<style>.central-ctnr {display: inline;}</style>
<body>
    <div class="central-ctnr">
        <?php
        //spits out sessVar 'message' set in various random functions, making this single line reusable from everywhere
        echo $_SESSION["message"];
        header("refresh: 2; url={$_GET['redirect']}");
        ?>

        <br>You will be automatically redirected in 2 seconds.
    </div>

</body>
</html>
<!--scrapped- scriptless solution found.
    <script>
    //somewhat aided by https://stackoverflow.com/questions/12049620/how-to-get-get-variables-value-in-javascript
    //parses GET headers in URL
    var $_GET=[];
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(a,name,value){$_GET[name]=value;});
    
    //executes a redirect in 2000ms.
    setTimeout(function () {window.location.href = $_GET['redirect'];}, 2000);
</script>-->