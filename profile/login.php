<?php
require_once "../resources/includes/header.php";
if(isset($_SESSION["id"])){
    header("Location: profile.php");
}
if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);

    $getUser = $bdd->prepare("SELECT * FROM `users` WHERE `user_login` = ?");
    $getUser->execute(array($username));

    if ($getUser->rowCount()==1){
        $getUser = $getUser->fetch();

        if (password_verify($_POST['password'], $getUser['user_password'])){
            $_SESSION["id"] = $getUser["user_id"];
            $_SESSION["login"] = $getUser["user_login"];
            $_SESSION["type"] = $getUser["user_type"];
            header("Location: profile.php");
        }
    } else {
        echo '<br>This account does not exist. Do you wish to <a href="register.php">create an account</a> ?';
    }
}
?>
<br>
<div>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required/>
        <input type="password" name="password" placeholder="Password" required/><br/>
        <input type="submit" name="login" value="Login"/>
    </form>
</div>
