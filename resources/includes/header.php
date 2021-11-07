<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=pasteque;charset=utf8', 'phpmyadmin', 'root');
?>
<link rel="stylesheet" type="text/css" href="/resources/styles/global.css">
<header>
<nav>
    <?php
    if(isset($_SESSION["id"])){
        echo '
            <div class="navElement"><a class="navLink" href="/profile/profile.php">Profile</a></div>
            <div class="navElement"><a class="navLink" href="/profile/logout.php">Logout</a></div>
            <div class="navElement"><a class="navLink" href="/game/game.php">Game</a></div>
            <div class="navElement"><a class="navLink" href="/profile/settings.php">Settings</a> </div>';
        if ($_SESSION["type"] == "admin"){
            echo '<div class="navElement"><a class="navLink" href="/admin/portal.php">Admin portal</a></div>';
        }
    } else {
        echo '
            <div class="navElement"><a class="navLink" href="/profile/login.php">Login</a></div>
            <div class="navElement"><a class="navLink" href="/profile/register.php">Create an account</a></div>';
    }
    ?>
</nav>
<div class="headerRight">
    <?php
    if (isset($_SESSION['id'])) {
        echo '<div class="headInfo">Logged in as ' .$_SESSION['login'].'</div>';
    }
    ?>
</div>
</header>