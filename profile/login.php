<?php
require_once "../resources/includes/header.php";
if(isset($_SESSION["id"])){
    header("Location: profile.php");
}
if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);

    $get_user = $bdd->prepare("SELECT * FROM `users` WHERE `user_login` = ?");
    $get_user->execute(array($username));

    if ($get_user->rowCount()==1){
        $get_user = $get_user->fetch();

        if (password_verify($_POST['password'], $get_user['user_password'])){
            $_SESSION["id"] = $get_user["user_id"];
            $_SESSION["login"] = $get_user["user_login"];
            $_SESSION["type"] = $get_user["user_type"];
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
