<?php
// Header and redirections
require_once('../resources/includes/header.php');
if(!isset($_SESSION['id'])) {
    header('Location: ../index.php');
} elseif($_SESSION['type'] != 'admin'){
    header('Location: ../profile/profile.php');
} elseif($_SESSION['login'] != 'admin'){
    header('Location: portal.php');
}

// Fetch the deleted users list from the database
$deletedUsers = $bdd->prepare("SELECT * FROM deleted_users");
$deletedUsers->execute();

?>
<br>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Deleted by</th>
            <th>Deleted on</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Display each deleted user's info as well as links to edit them individually
        foreach ($deletedUsers as $user) {
            echo "<tr>
                <td>" . $user["user_login"] . "</td>
                <td>" . $user["user_email"] . "</td>
                <td>" . $user["deleted_by"] . "</td>
                <td>" . $user["deleted_on"] . "</td>
                <td><a href='userView.php?id=".$user["user_id"]."&deleted=1'>Manage user</a></td>
            </tr>";
        }
        ?>
    </tbody>
</table>

