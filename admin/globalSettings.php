<?php
require_once "../resources/includes/header.php";
if (!isset($_SESSION["id"]) or $_SESSION["type"] != "admin"){
    header("Location: ../index.php");
}

$globalSettings = $bdd->prepare("SELECT * FROM global_settings WHERE id = ?");
$globalSettings->execute(array(0));
$globalSettings = $globalSettings->fetch();

if(isset($_POST["saveSettings"])){
    $saveRequest = $bdd->prepare("UPDATE global_settings SET save_frequency = ?, update_frequency = ?, price_increase_factor = ?, cursor_price = ? WHERE id = ?");
    $saveRequest->execute(array($_POST["saveFrequency"], $_POST["updateFrequency"], $_POST["priceIncreaseFactor"], $_POST["cursorPrice"], 0));

    header("Location: portal.php");
}

if(isset($_POST["default"])){
    $saveRequest = $bdd->prepare("UPDATE global_settings SET save_frequency = ?, update_frequency = ?, price_increase_factor = ?, cursor_price = ? WHERE id = ?");
    $saveRequest->execute(array(60, 1, 2, 15, 0));

    header("Location: portal.php");
}

?>

<br>
<form method="POST">
    <label>
        Save Frequency :
        <input type="number" step="1" name="saveFrequency" min="1" oninput="validity.valid||(value='');" value="<?= $globalSettings['save_frequency'] ?>">
    </label><br>
    <label>
        Update Frequency :
        <input type="number" step="1" name="updateFrequency" min="1" oninput="validity.valid||(value='');" value="<?= $globalSettings['update_frequency'] ?>">
    </label><br>
    <label>
        Price Increase Factor :
        <input type="number" step="1" name="priceIncreaseFactor" min="1" oninput="validity.valid||(value='');" value="<?= $globalSettings['price_increase_factor'] ?>">
    </label><br>
    <label>
        Cursor Price :
        <input type="number" step="1" name="cursorPrice" min="1" oninput="validity.valid||(value='');" value="<?= $globalSettings['cursor_price'] ?>">
    </label><br>
    <input type="submit" name="saveSettings" value="Save modifications">
    <input type="submit" name="default" value="Return to default values">
</form>