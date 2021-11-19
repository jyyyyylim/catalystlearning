<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href="style.css">    
    <title>Register account</title>
</head>
<body>
    <?php
        session_start();
        if (isset($_SESSION["user"])) {
            $_SESSION["message"]= "You are already logged in. <br>Now redirecting you to the homepage.";
            $redirect= "homepage.php";
            header("Location:holdingpage.php?redirect={$redirect}");
        }
    ?>


    <div class="central-ctnr encapsulation">         
        <div class="login-ctnr">
            <u class="authtoggle">Already have an account?</u>
            <h2>Login</h2>
                <form action="./credentials/login.php" method="POST">
                <input type="text" name="username" placeholder="username" required="required"><br>
                <input type="password" name="password" pattern="(pass)|.{8,}" title="Your entry must exceed 8 characters" placeholder="password" required="required"><br>
                <button>Login</button>
            </form>
        </div>

        <div class="reg-ctnr">
            <u class="authtoggle">Don't have an account?</u>
            <h2>Signup</h2>
            <form action="./credentials/register.php" method="POST">
                <input type="text" name="handle" placeholder="preferred handle" required="required"><br>
                <input type="text" name="fullname" placeholder="your full name" required="required"><br>
                <input type="text" name="email" placeholder="e-mail address" required="required"><br>
                <input type="password" name="password" placeholder="password" pattern=".{8,}" title="Your entry must exceed 8 characters" required="required"><br>
                <input type="password" name="confpwd" placeholder="confirm your password" pattern=".{8,}" title="Your password probably doesn't match." required="required"><br>
                <div class="usertype">
                    <input type="radio" id="student" name="usertype" value="0" checked><label for="student">Student</label><br>
                    <input type="radio" id="teacher" name="usertype" value="1"><label for="teacher">Educator</label><br>
                </div>
                <button>Submit</button>

            </form>
        </div>
    </div>
</body>
</html>
