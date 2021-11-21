<?php
// Header and redirections
require_once "../resources/includes/header.php";
if (isset($_SESSION["id"])){
    header("Location: profile.php");
}

echo '<br>';

// Triggers when the 'register' button is pressed
if (isset($_POST['register'])) {
    // Checks the user's information for any special characters to avoid attacks. Also hashes the password
    $username = htmlspecialchars($_POST['login']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // Checks the database for any users that share the same username
    $checkUser = $bdd->prepare("SELECT * FROM `users` WHERE `user_login` = ?");
    $checkUser->execute(array($username));

    if ($checkUser->rowCount()==0){
        // If no users with the same username are found, check for emails
        $checkEmail = $bdd->prepare("SELECT * FROM `users` WHERE `user_email` = ?");
        $checkEmail->execute(array($email));

        if ($checkEmail->rowCount()==0){
            // If no users with the same email are found, check whether the password and confirm_password are equal
            if(password_verify($_POST["confirmPassword"], $password)){
                // If both passwords match, insert the user's info into the database
                $insertUserRequest = $bdd->prepare("INSERT INTO users (user_login, user_password, user_email, user_type, user_creation_date) VALUES (?, ?, ?, 'user', NOW())");
                $insertUserRequest->execute(array($username, $password, $email));

                // Fetch the new user's info from the database (their new id, login, and type)
                $newUser = $bdd->prepare("SELECT `user_id`, `user_login`, `user_type` FROM `users` WHERE `user_login` = ?");
                $newUser->execute(array($username));
                $newUser = $newUser->fetch();

                // Stores this information in the session
                $_SESSION["id"] = $newUser["user_id"];
                $_SESSION["login"] = $newUser["user_login"];
                $_SESSION["type"] = $newUser["user_type"];

                // Creates a new row in the user_data table and sets the new user's in-game possessions 
                $insertDataRequest = $bdd->prepare("INSERT INTO `user_data` (`user_id`,`user_score`,`user_cursors`) VALUES (?, 0, 0)");
                $insertDataRequest->execute(array($_SESSION["id"]));

                // Redirect to the new user's profile
                header("Location: profile.php");
            } else {
                // If the passwords don't match, tell the user they don't and then do nothing
                echo '<div>The passwords don\'t match.</div>';
            }
        } else {
            // If the email is already used, tell the user the email is already in use and then do nothing
            echo '<div>This email is already in use.</div>';
        }
    } else {
        // If the username already exists, notify the user and propose a link to the login page
        echo '<div>This username is already in use. Are you trying to <a href="login.php">login</a> ?</div>';
    }
}
?>
<!--The HTML form-->
<div>
    <form method="post">
        <input type="text" name="login" placeholder="Username" required/>
        <input type="email" name="email" placeholder="Email address"/><br/>
        <input type="password" name="password" placeholder="Password" required/>
        <input type="password" name="confirmPassword" placeholder="Confirm password" required/><br/>
        <input type="submit" name="register" value="Create Account"/>
    </form>
</div>
