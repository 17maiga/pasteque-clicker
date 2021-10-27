<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: profile/login.php");
}
echo "<br><div>Welcome, " . $_SESSION["login"] . "</div>";
