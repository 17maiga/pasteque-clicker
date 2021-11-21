<?php
require_once "../resources/includes/header.php";
// Redirections
if (!isset($_SESSION['id'])){ 
    // Redirects to login page if no session is present
    header('Location: ../index.php');
} elseif ($_SESSION['type'] != 'admin'){ 
    // Redirects to the user's profile if they are not an administrator
    header('Location: ../profile/profile.php');
} elseif (!isset($_GET['id'])){
    if (!isset($_GET['deleted']) or $_GET['deleted'] == 0){ 
        // Redirects to the existing users list if no id is provided in the url and if the 'deleted' flag is false/unset
        header('Location: userList.php');
    } else { 
        // Redirects to the deleted users list if no id is provided in the url and if the 'deleted' flag is true
        header('Location: deletedUsers.php');
    }
} elseif (!isset($_GET['deleted'])){ 
    // Redirects to the existing users list if an id is provided, but the 'deleted' flag is unset
    header('Location: userList.php');
} elseif ($_GET['deleted'] == 1 and $_SESSION['login'] != 'admin') { 
    // Redirects to the existing users list if another user besides the main admin profile tries to access a deleted user's page
    header('Location: userList.php');
}

// User information
if ($_GET['deleted'] == 0) {
    // Triggered when dealing with an active user. Fetches the user's info from the 'users' and 'user_data' tables and stores it in the $user and $data variables
    $userInfo = $bdd->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
    $userInfo->execute(array($_GET["id"]));
    $userData = $bdd->prepare("SELECT * FROM `user_data` WHERE `user_id` = ?");
    $userData->execute(array($_GET['id']));
    $data = $userData->fetch();
} else {
    // Triggered when dealing with a deleted user. Fetches the user's info from the 'deleted_users' table and stores it in the $user variable
    $userInfo = $bdd->prepare('SELECT * FROM deleted_users WHERE user_id = ?');
    $userInfo->execute(array($_GET['id']));
}
$user = $userInfo->fetch();


// Form actions

// Triggered when an update has been applied to an existing user's role (either promoted or demoted from an admin role). Updates said user's role in the database.
if (isset($_POST["save"])){ 
    $userSave = $bdd->prepare("UPDATE `users` SET `user_type` = ? WHERE user_id = ?");
    $userSave->execute(array($_POST["role"], $_GET["id"]));
    header("Location: userList.php");
}

// Triggered when a deleted user is being restored. Inserts the user's information back into the active users table in the database, deletes it from the backup table, and then redirects to the deleted users page
if (isset($_POST['restore'])){ 
    $userRestore = $bdd->prepare('INSERT INTO users(user_id, user_login, user_email, user_password, user_type, user_creation_date) VALUES (?,?,?,?,?,?)');
    $dataRestore = $bdd->prepare('INSERT INTO user_data(user_id, user_score, user_cursors) VALUES (?,?,?)');

    $userRestore->execute(array($user['user_id'], $user['user_login'], $user['user_email'], $user['user_password'], $user['user_type'], $user['user_creation_date']));
    $dataRestore->execute(array($user['user_id'], $user['user_score'], $user['user_cursors']));

    $backupDelete = $bdd->prepare('DELETE FROM deleted_users WHERE user_id = ?');
    $backupDelete->execute(array($user['user_id']));

    header('Location: deletedUsers.php');
}

// Triggered when a user is being deleted.
if (isset($_POST["delete"])){
    if ($_GET['deleted'] == 0) {
        // If dealing with an active user, inserts the user's info into the 'deleted_users' table and then deletes them from the 'users', 'user_data' and 'user_settings' tables, then redirects to the active users list.
        $userBackup = $bdd->prepare("INSERT INTO `deleted_users`(`user_id`, `user_login`, `user_email`, `user_password`, `user_type`, `user_creation_date`, `user_score`, `user_cursors`, `deleted_by`, `deleted_on`) VALUES (?,?,?,?,?,?,?,?,?, NOW())");
        $userBackup->execute(array($user["user_id"], $user["user_login"], $user["user_email"], $user["user_password"], $user['user_type'], $user['user_creation_date'], $data["user_score"], $data["user_cursors"], $_SESSION["login"]));
        
        $userDataDelete = $bdd->prepare("DELETE FROM `user_data` WHERE `user_id` = ?");
        $userDataDelete->execute(array($user["user_id"]));

        $userSettingsDelete = $bdd->prepare('DELETE FROM user_settings WHERE user_id = ?');
        $userSettingsDelete->execute(array($user['user_id']));

        $userDelete = $bdd->prepare("DELETE FROM `users` WHERE `user_id` = ?");
        $userDelete->execute(array($user["user_id"]));

        header("Location: userList.php");
    } else {
        // If dealing with an already deleted user, simply deletes the user's info from the 'deleted_users' table and redirects to the deleted users list.
        $userDelete = $bdd->prepare("DELETE FROM deleted_users WHERE user_id = ?");
        $userDelete->execute(array($user["user_id"]));

        header("Location: deletedUsers.php");
    }
}

?>

<br>
<form method="post">
<div class="admin-menu">
    <a href="userList.php">Back</a>
    <?php 
        if ($_GET['deleted'] == 0) {
            if($user['user_type'] != 'admin' or $_SESSION['login'] == 'admin') {
                // If the user is still active and isn't an admin, allow for modifications to their rank
                // If we are logged in as the main admin, allow for modifications regardless of the user's rank
                echo '<input type = "submit" name = "save" value = "Save modifications">';
            }
        } else {
            // If the user is a deleted user, allow for the user to be restored
            echo '<input type = "submit" name = "restore" value = "Restore this user">';
        }
        if ($user['user_type'] != 'admin') {
            // If the user isn't an admin, allow for the user to be deleted
            echo '<input type = "submit" name = "delete" value = "Delete user">';
        }
    ?>
</div>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <?php 
                if ($_GET['deleted'] == 0) {
                    // If the user isn't deleted, display the 'Type' column
                    echo '<th>Type</th>';
                } else {
                    // If the user isn't deleted, display the 'Deleted by' and 'Deleted on' columns
                    echo '<th>Deleted by</th><th>Deleted on</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody>
    <?php
        // Display the username and email in the table
        echo "<tr><td>".$user["user_login"]."</td><td>".$user["user_email"]."</td><td>";
        if ($_GET['deleted'] == 0) {
            // If the user is active
            if ($_SESSION["login"] == 'admin' && $user["user_login"] != 'admin') {
                // If we are the main admin and we are not editing the main admin, display a dropdown menu to change the user's role, set to the user's type
                echo "<select name='role'><option value='admin' "; 
                if ($user["user_type"] == 'admin') echo "selected";
                echo ">Admin</option><option value='user' ";
                if ($user["user_type"] == 'user') echo "selected";
                echo ">User</option></select>";
            } else {
                // If we aren't the main admin or are editing the main admin, simply display the user's type
                if ($user["user_type"] == 'admin') {
                    echo "admin";
                } else {
                    echo "user";
                }
            }
        } else {
            // If the user is deleted, display who deleted them and when
            echo $user['deleted_by']."</td><td>".$user['deleted_on'];
        }
        echo "</td></tr>";
    ?>
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
        <?php 
        if ($_GET['deleted'] == 0) {
            // If the user is active, display their game info from the $data variable
            echo "<tr>
                <td>".$data["user_score"]."</td>
                <td>".$data["user_cursors"]."</td>
            </tr>";
        } else {
            // If the user is deleted, display their game info from the $user variable
            echo "<tr>
                <td>".$user['user_score']."</td>
                <td>".$user['user_cursors']."</td>
            </tr>";
        }
        ?>
    </tbody>
</table>
</form>