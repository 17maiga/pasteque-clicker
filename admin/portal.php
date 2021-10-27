<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"]) or $_SESSION["type"] != "admin"){
    header("Location: ../index.php");
}
?>

<br>
<div>
    <a href="settings.php">Manage game settings</a><br>
    <a href="users.php">Manage users</a>
</div>
