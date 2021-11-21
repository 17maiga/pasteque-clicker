<?php
// Header and redirections
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: ../index.php");
} elseif ($_SESSION["type"] != "admin") {
    header("Location: ../profile/profile.php");
}

?>

<br>
<div>
    <!--Admin menu-->
    <a href="globalSettings.php">Manage game settings</a><br>
    <a href="userList.php">Manage users</a><br>
    <?php
    if ($_SESSION["login"] == "admin"){
        // If we are the main admin, display a link to the deleted users list
        echo '<a href="deletedUsers.php">Deleted users</a><br>';
    }
    ?>
</div>
