<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>Welcome to CatalystLearning</title>
</head>

<style>
    :root {scroll-snap-type: y mandatory; -ms-scroll-snap-type: y mandatory; scroll-snap-points-y: repeat(75vh); font-size: 40px;}
    body {margin: 0 auto;}
    /*additional styling rule that polishes up an otherwise bland stack of pagedivs*/
    .pagediv-item:nth-child(1) {height: 100vh; background-color: var(--background-col)}
    .pagediv-item:nth-child(even) {background-color: var(--container-col);}
    .pagediv-item:nth-child(odd) {background-color: var(--container-col2);}
    .pagediv-item:not(:nth-child(1)) {padding-left: 20%; max-width: 80%;}

    .pagediv-item > .flipup_ctnt {text-align: right; float: right;
        /*100% transform, to nudge the "menu" beyond the viewport*/
        transform: translate(100vh, 0%); transition: transform 0.6s cubic-bezier(0, 0, 0.745, 0);
    }

    .pagediv-item:hover > .flipup_ctnt {transform: translate(0vh, 0%); transition: transform 0.6s cubic-bezier(0.77, 0, 0.175, 1);}
</style>
<?php include "./credentials/content-restrict.php";?>

<body>
    <div class="pagediv-item"> 
        <div class="y-center welcomectnr">Welcome back, <br><?php echo $_SESSION["user"];?></div> 
        <?php
            if($_SESSION["usrtype"] == 2) {
                echo '<img src="./svgs/sword.svg" alt="sword svg" class="tone">';
            }elseif($_SESSION["usrtype"] == 1) {
                echo '<img src="./svgs/school.svg" alt="school svg" class="tone">';
            } else {
                echo '<img src="./svgs/graduation-cap.svg" alt="cap svg" class="tone">';
            }
        ?>
    </div>
    <?php
        if($_SESSION["usrtype"] == 2) {
            echo file_get_contents('./src/admin.html');
        }elseif($_SESSION["usrtype"] == 1) {
            echo file_get_contents('./src/teacher.html');
        } else {
            echo file_get_contents('./src/student.html');
        }
    ?>
</body>
</html>

