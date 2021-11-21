<?php
session_start(); // Required for session variables. Since almost all files include the header, we only need to start the session once for all of these
$dbUsername = 'phpmyadmin';
$dbPassword = 'root';
$bdd = new PDO('mysql:host=localhost;dbname=pasteque;charset=utf8', $dbUsername, $dbPassword); // The database connection. Similarly to the session, it is used everywhere
?>
<!--Link to the CSS, favicon, and the page title-->
<link rel="stylesheet" type="text/css" href="/resources/styles/global.css">
<link rel="icon" href="/favicon.ico">
<title>Pasteque Clicker</title>
<header>
    <nav>
    <?php
        if(isset($_SESSION["id"])){ // When a user is logged in, display links to their profile, the game page, their personal settings, and a logout option
            echo '
                <div class="navElement"><a class="navLink" href="/profile/profile.php">Profile</a></div>
                <div class="navElement"><a class="navLink" href="/profile/logout.php">Logout</a></div>
                <div class="navElement"><a class="navLink" href="/game/game.php">Game</a></div>
                <div class="navElement"><a class="navLink" href="/profile/settings.php">Settings</a> </div>
            ';
            if ($_SESSION["type"] == "admin"){ // If the user is an administrator, also display a link to the admin portal
                echo '<div class="navElement"><a class="navLink" href="/admin/portal.php">Admin portal</a></div>';
            }
        } else { // When no user is logged in, display links to login/register
            echo '
                <div class="navElement"><a class="navLink" href="/profile/login.php">Login</a></div>
                <div class="navElement"><a class="navLink" href="/profile/register.php">Create an account</a></div>
            ';
        }
    ?>
    </nav>
    <div class="headerRight">
    <?php
        if (isset($_SESSION['id'])) { // When a user is logged in, display their username in the top right at all times
            echo '<div class="headInfo">Logged in as ' .$_SESSION['login'].'</div>';
        }
    ?>
    </div>
</header>