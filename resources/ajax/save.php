<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=pasteque;charset=utf8', 'phpmyadmin', 'root');

$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

$saveRequest = $bdd->prepare("UPDATE user_data SET user_score = ?, user_cursors = ? WHERE user_id = ?");
$saveRequest->execute(array($data["score"], $data["cursor"], $_SESSION["id"]));