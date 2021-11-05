<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"]) or $_SESSION["type"] != "admin"){
    header("Location: ../index.php");
}
?>

<br>
<div>
    <a href="globalSettings.php">Manage game settings</a><br>
    <a href="userList.php">Manage users</a><br>
    <?php
    if ($_SESSION["login"] == "admin"){
        echo '<a href="deletedUsers.php">Deleted users</a><br>';
    }
    ?>
</div>
