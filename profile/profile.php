<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: profile/login.php");
}

$userInfo = $bdd->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
$userInfo->execute(array($_SESSION["id"]));
$userInfo = $userInfo->fetch();

$userData = $bdd->prepare("SELECT * FROM `user_data` WHERE `user_id` = ?");
$userData->execute(array($_SESSION["id"]));
$userData = $userData->fetch();

?>

<div>Welcome, <?= $_SESSION["login"] ?>.</div>
<div>You currently have <?= $userData["user_score"] ?> pasteques.</div>