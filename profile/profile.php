<?php
// Header and redirections
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: login.php");
}

// Fetch the user's info from the database
$userInfo = $bdd->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
$userInfo->execute(array($_SESSION["id"]));
$userInfo = $userInfo->fetch();

$userData = $bdd->prepare("SELECT * FROM `user_data` WHERE `user_id` = ?");
$userData->execute(array($_SESSION["id"]));
$userData = $userData->fetch();

if(isset($_POST['edit'])){
    header('Location: edit.php');
}

?>
<br>
<!--Display the user's info-->
<div>Welcome, <?= $_SESSION["login"] ?>.</div>
<div>You currently have <?= $userData["user_score"] ?> pasteques.</div>
<?php
// If the user isn't the main admin, display a button to edit their profile
if ($_SESSION['login'] != 'admin') {
    echo '
    <form method="POST">
        <input type="submit" name="edit" value="Edit profile">
    </form>
    ';
}