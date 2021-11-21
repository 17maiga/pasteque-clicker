<?php
// This code is only executed when an ajax request is sent from the game.js file to save the user's data to the server

session_start(); // Required for session variables
$bdd = new PDO('mysql:host=localhost;dbname=pasteque;charset=utf8', 'phpmyadmin', 'root'); // Connection to the database (since we don't include the header)

// Json data sent by the game.js file is decoded and stored in $data
$jsonData = file_get_contents("php://input"); 
$data = json_decode($jsonData, true);

// Database query to update the values for the user
$saveRequest = $bdd->prepare("UPDATE user_data SET user_score = ?, user_cursors = ? WHERE user_id = ?");
$saveRequest->execute(array($data["score"], $data["cursor"], $_SESSION["id"]));