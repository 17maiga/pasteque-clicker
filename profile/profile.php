<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: profile/login.php");
}
if(isset($_POST["login"])){

}
