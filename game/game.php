<?php
// Header and redirections
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: ../profile/login.php");
}

// Fetch the user's game data
$data = $bdd->prepare("SELECT * FROM user_data WHERE user_id = ?");
$data->execute(array($_SESSION["id"]));
$data = $data->fetch();

// Fetch the global settings
$globalSettings = $bdd->prepare("SELECT * FROM `global_settings` WHERE `id` = ?");
$globalSettings->execute(array(0));
$globalSettings  = $globalSettings->fetch();

// Fetch the user settings if they exist
$userSettings = $bdd->prepare("SELECT * FROM `user_settings` WHERE user_id = ?");
$userSettings->execute(array($_SESSION["id"]));

if ($userSettings->rowCount() != 0){
    // If the user settings are set, set the $saveFrequency and $updateFrequency variables with the user settings
    $userSettings = $userSettings->fetch();
    $saveFrequency = $userSettings["save_frequency"];
    $updateFrequency = $userSettings["update_frequency"];
} else {
    // If the user settings aren't set, set the $saveFrequency and $updateFrequency variables with the global settings
    $saveFrequency = $globalSettings["save_frequency"];
    $updateFrequency = $globalSettings["update_frequency"];
}
?>

<div hidden id="settings"><!--A hidden div that stores the settings for the js to fetch afterwards-->
    <span id="saveFrequency"><?= strval($saveFrequency) ?></span>
    <span id="updateFrequency"><?= strval($updateFrequency) ?></span>
    <span id="priceIncreaseFactor"><?= $globalSettings["price_increase_factor"] ?></span>
    <span id="cursorPrice"><?= $globalSettings["cursor_price"] ?></span>
</div>

<!--Include the script to the page-->
<script src="../resources/scripts/game.js"></script>

<!--A div that stores the information related to pasteques-->
<br>
<div>Pasteques :
    <span id="pastequeCount"><?= $data["user_score"] ?></span>
    <input type="button" value="Add a pasteque" id="pastequeAdd" onclick="buy('score');">
</div><br>

<!--A div that stores the information related to cursors-->
<div>Cursors (+1/s) :
    <span id="cursorCount"><?= $data["user_cursors"] ?></span><br>
    <span>Price : <span id="cursorPrice"></span></span>
    <input type="button" value="Buy a cursor" id="cursorAdd" onclick="buy('cursor');">
</div><br>

<!--A button to save the game manually-->
<input type="button" value="Save" onclick="saveGame()">

<!--A script that starts the game loop from the JS file after all the HTML has been loaded-->
<script>
    displayPrices();
    startGameLoop();
</script>