<?php
// Header and redirections
require_once '../resources/includes/header.php';
if (!isset($_SESSION['id'])){
    header('Location: ../index.php');
} elseif ($_SESSION['type'] != 'admin') {
    header('Location: ../profile/profile.php');
}

// Fetch the active users from the database
$usersList = $bdd->prepare('SELECT * FROM `users`');
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
        // Display a list of the active users as well as links to edit their information
        foreach ($usersList as $user) {
            echo '<tr>
                <td>' . $user['user_login'] . '</td>
                <td>' . $user['user_email'] . '</td>
                <td>' . $user['user_type'] . '</td>
                <td><a href="userView.php?id=' . $user['user_id'] . '&deleted=0">Manage user</a></td>
            </tr>';
        }
        ?>
    </tbody>
</table>