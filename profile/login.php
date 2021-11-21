<?php
// Header and redirections
require_once "../resources/includes/header.php";
if(isset($_SESSION["id"])){
    header("Location: profile.php");
}

// Triggers when the 'login' button is clicked
if (isset($_POST['login'])) {
    // Checks for special characters to protect from attacks
    $username = htmlspecialchars($_POST['username']);

    // Fetch the user's info from the database, if they exist
    $getUser = $bdd->prepare("SELECT * FROM `users` WHERE `user_login` = ?");
    $getUser->execute(array($username));

    if ($getUser->rowCount()==1){
        // If the user exists, check if the password submitted matches the one in the database
        $getUser = $getUser->fetch();

        if (password_verify($_POST['password'], $getUser['user_password'])){
            // If the passwords match, save the user's info to the database and redirect them to their profile 
            $_SESSION["id"] = $getUser["user_id"];
            $_SESSION["login"] = $getUser["user_login"];
            $_SESSION["type"] = $getUser["user_type"];
            header("Location: profile.php");
        }

    } else {
        // If the user doesn't exist in the database, notify the user and propose a link to the register page 
        echo '<br>This account does not exist. Do you wish to <a href="register.php">create an account</a> ?';
    }
}
?>
<!--The HTML form-->
<br>
<div>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required/>
        <input type="password" name="password" placeholder="Password" required/><br/>
        <input type="submit" name="login" value="Login"/>
    </form>
</div>
