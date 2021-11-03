<?php
require_once "../resources/includes/header.php";
if (isset($_SESSION["id"])){
    header("Location: profile.php");
}

echo '<br>';

if (isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['login']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $check_user = $bdd->prepare("SELECT * FROM `users` WHERE `user_login` = ?");
    $check_user->execute(array($username));

    if ($check_user->rowCount()==0){

        $check_email = $bdd->prepare("SELECT * FROM `users` WHERE `user_email` = ?");
        $check_email->execute(array($email));

        if ($check_email->rowCount()==0){
            if(password_verify($_POST["conf_password"], $password)){

                $insertUserRequest = $bdd->prepare("INSERT INTO `users` (`user_login`, `user_password`, `user_email`, `user_type`) VALUES (?, ?, ?, 'user')");
                $insertUserRequest->execute(array($username, $password, $email));

                $newUser = $bdd->prepare("SELECT `user_id`, `user_login`, `user_type` FROM `users` WHERE `user_login` = ?");
                $newUser->execute(array($username));
                $newUser = $newUser->fetch();

                $_SESSION["id"] = $newUser["user_id"];
                $_SESSION["login"] = $newUser["user_login"];
                $_SESSION["type"] = $newUser["user_type"];

                $insertDataRequest = $bdd->prepare("INSERT INTO `user_data` (`user_id`,`user_score`,`user_cursors`) VALUES (?, 0, 0)");
                $insertDataRequest->execute(array($_SESSION["id"]));

                header("Location: profile.php");
            }
        } else {
            echo '<div>This email is already in use</div>';
        }
    } else {
        echo '<div>This username is already in use. Are you trying to <a href="login.php">login</a> ?</div>';
    }
}
?>
<div>
    <form method="post">
        <input type="text" name="login" placeholder="Username" required/>
        <input type="email" name="email" placeholder="Email address"/><br/>
        <input type="password" name="password" placeholder="Password" required/>
        <input type="password" name="conf_password" placeholder="Confirm password" required/><br/>
        <input type="submit" name="register" value="Create Account"/>
    </form>
</div>
