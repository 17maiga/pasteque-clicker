<?php
// Header and redirections
require_once '../resources/includes/header.php';
if (!isset($_SESSION['id'])){
    header('Location: login.php');
} elseif ($_SESSION['login'] == 'admin') {
    header('Location: profile.php');
}


// Fetch the user's info from the database
$userInfo = $bdd->prepare('SELECT * FROM `users` WHERE `user_id` = ?');
$userInfo->execute(array($_SESSION['id']));
$userInfo = $userInfo->fetch();

$userData = $bdd->prepare('SELECT * FROM `user_data` WHERE `user_id` = ?');
$userData->execute(array($_SESSION['id']));
$userData = $userData->fetch();


// Triggers when the 'save changes' button is clicked. Updates the user's username in the database and in session variables
if(isset($_POST['save'])) {
    $saveRequest = $bdd->prepare('UPDATE users SET user_login = ? WHERE user_id = ?');
    $saveRequest->execute(array($_POST['login'], $_SESSION['id']));

    $_SESSION['login'] = $_POST['login'];

    header('Location: profile.php');
}

// Triggers when the 'reset progress' button is clicked. Resets the user's data in the database
if(isset($_POST['reset'])){
    $resetRequest = $bdd->prepare('UPDATE user_data SET user_score = 0, user_cursors = 0 WHERE user_id = ?');
    $resetRequest->execute(array($_SESSION['id']));

    header('Location: profile.php');
}

// Triggers when the 'delete profile' button is clicked. Deletes the user's info from the database
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
        <input type='submit' name='reset' value='Reset save data'>
        <input type='submit' name='delete' value='Delete profile'>
    </div>
</form>