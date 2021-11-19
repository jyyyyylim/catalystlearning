<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">   
    <title>CatalystLearning homepage</title>
</head>

<style>
    /*OVERRIDING STYLESHEET*/ 
    img {max-width: 80%;}
    .subtext {font-size: 24px; margin: 1em;}
    button {margin: 0.5em; width: 13rem; height: 2.6rem;}
    .mainpage-ctnr {width: 60vw;}
</style>


<body>
    <div class="central-ctnr mainpage-ctnr encapsulation">
        <div class="left">
            <div class="splashpara">A <span class="emphasis">modern</span> solution to education.</div>
            <img src="src/avast-cdn.webp" alt="rack of servers">
        </div>
        <form class="right" action="./registerpage.php">
            <div class="subtext">Get started</div><br>
            <button>Register</button>
            <button>Login</button>                
        </form>
    </div>
</body>

<script>
    document.getElementById("reg").addEventListener("click", function(){window.location.href= "./registerpage.php"});
    document.getElementById("login").addEventListener("click", function(){window.location.href= "./registerpage.php"});

</script>

</html>