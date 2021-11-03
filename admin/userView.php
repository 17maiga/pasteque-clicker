<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"]) or $_SESSION["type"] != "admin"){
    header("Location: ../index.php");
} elseif (!isset($_GET['id'])) {
    header("Location: userList.php");
}

if (isset($_POST["save"])){
    $userSave = $bdd->prepare("UPDATE `users` SET `user_type` = ? WHERE user_id = ?");
    $userSave->execute(array($_POST["role"], $_GET["id"]));
    header("Location: userList.php");
}

$userInfo = $bdd->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
$userInfo->execute(array($_GET["id"]));
$userData = $bdd->prepare("SELECT * FROM `user_data` WHERE `user_id` = ?");
$userData->execute(array($_GET['id']));
?>

<br>
<form method="post">
<div class="admin-menu">
    <a href="userList.php">Back</a>
    <?php foreach ($userInfo as $user) {
        if ($user["user_login"] != "admin"){
            echo '
                <input type = "submit" name = "save" value = "Save modifications" >
                <input type = "submit" name = "delete" value = "Delete user" >
            ';
        }
    ?>
</div>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
    <?php
        echo "<tr><td>".$user["user_login"]."</td><td>".$user["user_email"]."</td><td>";
    if ($user["user_login"] != 'admin'){
            echo "<select name='role'><option value='admin' ";
        if($user["user_type"] == 'admin'){ echo "selected"; }
            echo ">Admin</option><option value='user'";
        if($user["user_type"] == 'user'){ echo "selected"; }
            echo ">User</option></select>";
    } else {
        echo "admin";
    }
    echo "</td></tr>";?>
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th>Score</th>
            <th>Cursors</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($userData as $data){
        echo "<tr>
            <td>".$data["user_score"]."</td>
            <td>".$data["user_cursors"]."</td>
        </tr>";?>
    </tbody>
</table>
</form>

<?php
        if (isset($_POST["delete"])){
            $userBackup = $bdd->prepare("INSERT INTO `deleted_users`(`user_id`, `user_login`, `user_email`, `user_password`, `user_score`, `user_cursors`, `deleted_by`, `deleted_on`) VALUES (?,?,?,?,?,?,?, NOW())");
            $userBackup->execute(array($user["user_id"], $user["user_login"], $user["user_email"], $user["user_password"], $data["user_score"], $data["user_cursors"], $_SESSION["login"]));
            $userDataDelete = $bdd->prepare("DELETE FROM `user_data` WHERE `user_id` = ?");
            $userDataDelete->execute(array($user["user_id"]));
            $userDelete = $bdd->prepare("DELETE FROM `users` WHERE `user_id` = ?");
            $userDelete->execute(array($user["user_id"]));
            header("Location: userList.php");
        }
    }
}