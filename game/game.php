<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"])){
    header("Location: profile/login.php");
}

$data = $bdd->prepare("SELECT * FROM user_data WHERE user_id = ?");
$data->execute(array($_SESSION["id"]));
$data = $data->fetch();

$globalSettings = $bdd->prepare("SELECT * FROM `global_settings` WHERE `id` = ?");
$globalSettings->execute(array(0));
$globalSettings  = $globalSettings->fetch();

$userSettings = $bdd->prepare("SELECT * FROM `user_settings` WHERE user_id = ?");
$userSettings->execute(array($_SESSION["id"]));

if ($userSettings->rowCount() != 0){
    $userSettings = $userSettings->fetch();
    $saveFrequency = $userSettings["save_frequency"];
    $updateFrequency = $userSettings["update_frequency"];
} else {
    $saveFrequency = $globalSettings["save_frequency"];
    $updateFrequency = $globalSettings["update_frequency"];
}
?>

<div hidden id="settings">
    <span id="saveFrequency"><?= $saveFrequency ?></span>
    <span id="updateFrequency"><?= $updateFrequency ?></span>
    <span id="priceIncreaseFactor"><?= $globalSettings["price_increase_factor"] ?></span>
    <span id="cursorPrice"><?= $globalSettings["cursor_price"] ?></span>
</div>

<script src="../resources/scripts/game.js"></script>

<br>
<div>Pasteques :
    <span id="pastequeCount"><?= $data["user_score"] ?></span>
    <input type="button" value="Add a pasteque" id="pastequeAdd" onclick="buy('score');">
</div><br>

<div>Cursors :
    <span id="cursorCount"><?= $data["user_cursors"] ?></span><br>
    <span>Price : <span id="cursorPrice"></span></span>
    <input type="button" value="Buy a cursor" id="cursorAdd" onclick="buy('cursor');">
</div><br>

<input type="button" value="Save" onclick="saveGame()">

<script>
    displayPrices();
    startGameLoop();
</script>