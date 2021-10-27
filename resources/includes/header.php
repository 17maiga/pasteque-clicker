<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=pasteque;charset=utf8', 'root', '');
?>
<link rel="stylesheet" type="text/css" href="/resources/styles/global.css">
<nav>
    <div class="nav-element"><a href="/">Home</a></div>
    <?php
    if(isset($_SESSION["id"])){
        echo '
                <div class="nav-element"><a href="/profile/profile.php">Profile</a></div>
                <div class="nav-element"><a href="/profile/logout.php">Logout</a></div>
                <div class="nav-element"><a href="/game/game.php">Game</a></div>';
        if ($_SESSION["type"] == "admin"){
            echo  '<div class="nav-element"><a href="/admin/portal.php">Admin portal</a></div>';
        }
    } else {
        echo '
                <div class="nav-element"><a href="/profile/login.php">Login</a></div>
                <div class="nav-element"><a href="/profile/register.php">Register</a></div>';
    }
    ?>
</nav>
