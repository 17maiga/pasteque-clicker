<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: profile/login.php");
}

$user_info = $bdd->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
$user_info->execute(array($_SESSION["id"]));
$user_info = $user_info->fetch();

$user_data = $bdd->prepare("SELECT * FROM `user_data` WHERE `user_id` = ?");
$user_data->execute(array($_SESSION["id"]));
$user_data = $user_data->fetch();

?>

<div>Welcome, <?= $_SESSION["login"] ?>.</div>
<div>You currently have <?= $user_data["user_score"] ?> pasteques.</div>