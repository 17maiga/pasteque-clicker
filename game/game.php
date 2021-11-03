<?php
require_once "../resources/includes/header.php";
$data = $bdd->prepare("SELECT * FROM user_data WHERE user_id = ?");
$data->execute(array($_SESSION["id"]));
$data = $data->fetch();
?>
<script src="../resources/scripts/game.js"></script>
<br>
<span id="pastequeDisplay"><?= $data["user_score"]?></span><br>
<input type="button" value="Add a pasteque" id="pastequeAdd" onclick="buy('score');">
<br><br>
<span>Cursors : </span><input type="button" value="<?= $data["user_cursors"] ?>" id="cursorAdd" onclick="buy('cursor');"><br>
<input type="button" value="Save" onclick="saveGame()">

<script>startGameLoop();</script>