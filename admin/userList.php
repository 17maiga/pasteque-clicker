<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"]) or $_SESSION["type"] != "admin") {
    header("Location: ../index.php");
}
$usersList = $bdd->prepare("SELECT * FROM `users`");
$usersList->execute();
?>
<br>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($usersList as $user) {
            echo "<tr><td>" . $user["user_login"] . "</td><td>" . $user["user_email"] . "</td><td>" . $user["user_type"] . "</td><td><a href='userView.php?id=".$user["user_id"]."'>Manage user</a></td></tr>";
        }
        ?>
    </tbody>>
</table>