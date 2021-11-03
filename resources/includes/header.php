<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=pasteque;charset=utf8', 'root', '');
?>
<link rel="stylesheet" type="text/css" href="/resources/styles/global.css">
<header>
<nav>
    <?php
    if(isset($_SESSION["id"])){
        echo '
            <div class="nav-element"><a class="nav-link" href="/profile/profile.php">Profile</a></div>
            <div class="nav-element"><a class="nav-link" href="/profile/logout.php">Logout</a></div>
            <div class="nav-element"><a class="nav-link" href="/game/game.php">Game</a></div>';
        if ($_SESSION["type"] == "admin"){
            echo  '<div class="nav-element"><a class="nav-link" href="/admin/portal.php">Admin portal</a></div>';
        }
    } else {
        echo '
            <div class="nav-element"><a class="nav-link" href="/profile/login.php">Login</a></div>
            <div class="nav-element"><a class="nav-link" href="/profile/register.php">Create an account</a></div>';
    }
    ?>
</nav>
<div class="header-right">
    <?php
    if (isset($_SESSION['id'])) {
        echo '<div class="head-info">Logged in as '.$_SESSION['login'].'</div>';
    }
    ?>
</div>
</header>