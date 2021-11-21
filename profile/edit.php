<?php
// Header and redirections
require_once '../resources/includes/header.php';
if (!isset($_SESSION['id'])){
    header('Location: login.php');
} elseif ($_SESSION['login'] == 'admin') {
    header('Location: profile.php');
}


// Triggers when the 'save changes' button is clicked. If a new username has been entered that isn't already taken, updates the user's username in the database and in session variables
if(isset($_POST['save'])) {
    if ($_POST['login'] != $_SESSION['login']) {
        // Check if any changes have been made

        // Get all users with the same username
        $userCheckRequest = $bdd->prepare('SELECT user_login FROM users WHERE user_login = ?');
        $userCheckRequest->execute(array($_POST['login']));

        if($userCheckRequest->rowCount() == 0) {
            // Check if any other users have the same username
            $saveRequest = $bdd->prepare('UPDATE users SET user_login = ? WHERE user_id = ?');
            $saveRequest->execute(array($_POST['login'], $_SESSION['id']));
        
            $_SESSION['login'] = $_POST['login'];
        
            header('Location: profile.php');
        } else {
            // Warn the user that this username is taken
            echo '<div>This username is already taken</div>';
        }
    } else {
        // If no changes have been made, redirect to the user's profile
        header('Location: profile.php');
    }
}

// Triggers when the 'discard changes' button is clicked. Simply redirects to the user's profile
if(isset($_POST['discard'])){
    header('Location: profile.php');
}

// Triggers when the 'reset progress' button is clicked. Resets the user's data in the database
if(isset($_POST['reset'])){
    $resetRequest = $bdd->prepare('UPDATE user_data SET user_score = 0, user_cursors = 0 WHERE user_id = ?');
    $resetRequest->execute(array($_SESSION['id']));

    header('Location: profile.php');
}

// Triggers when the 'delete profile' button is clicked. Deletes the user's info from the 'user_data', 'user_settings' and 'users' tables in the database
if(isset($_POST['delete']) && $_SESSION['login'] != 'admin'){
    $deleteDataRequest = $bdd->prepare('DELETE FROM user_data WHERE user_id = ?');
    $deleteDataRequest->execute(array($_SESSION['id']));

    $deleteSettingsRequest = $bdd->prepare('DELETE FROM user_settings WHERE user_id = ?');
    $deleteSettingsRequest->execute(array($_SESSION['id']));

    $deleteUserRequest = $bdd->prepare('DELETE FROM users WHERE user_id = ?');
    $deleteUserRequest->execute(array($_SESSION['id']));

    header('Location: logout.php');
}
?>
<br>
<form method='POST'>
    <div>Username: 
        <input type='text' name='login' value='<?= $_SESSION['login'] ?>'>
    </div>
    <div>
        <input type='submit' name='save' value='Save changes'>
        <input type="submit" name="discard" value="Discard changes">
        <input type='submit' name='reset' value='Reset save data'>
        <input type='submit' name='delete' value='Delete profile'>
    </div>
</form>