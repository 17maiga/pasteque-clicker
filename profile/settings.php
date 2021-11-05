<?php
require_once "../resources/includes/header.php";

if (!isset($_SESSION["id"])){
    header("Location: profile/login.php");
}

$userInfo = $bdd->prepare("SELECT * FROM users WHERE user_id = ?");
$userInfo->execute(array($_SESSION["id"]));
$userInfo = $userInfo->fetch();

$globalSettings = $bdd->prepare("SELECT * FROM global_settings WHERE id = ?");
$globalSettings->execute(array(0));
$globalSettings = $globalSettings->fetch();

$userSettings = $bdd->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$userSettings->execute(array($_SESSION["id"]));

if ($userSettings->rowCount() != 0) {
    $userSettingsSet = true;
    $userSettings = $userSettings->fetch();
    $saveFrequency = $userSettings["save_frequency"];
    $updateFrequency = $userSettings["update_frequency"];
} else {
    $userSettingsSet = false;
    $saveFrequency = $globalSettings["save_frequency"];
    $updateFrequency = $globalSettings["update_frequency"];
}

if(isset($_POST["saveSettings"])){
    if($userSettingsSet) {
        $saveRequest = $bdd->prepare("UPDATE user_settings SET save_frequency = ?, update_frequency = ? WHERE user_id = ?");
        $saveRequest->execute(array($_POST["saveFrequency"], $_POST["updateFrequency"], $_SESSION["id"]));
    } else {
        $saveRequest = $bdd->prepare("INSERT INTO user_settings (user_id, save_frequency, update_frequency) VALUES (?,?,?)");
        $saveRequest->execute(array($_SESSION["id"], $_POST["saveFrequency"], $_POST["updateFrequency"]));
    }

    header("Location: profile.php");
}

if(isset($_POST["default"])){
    $deleteRequest = $bdd->prepare("DELETE FROM user_settings WHERE user_id = ?");
    $deleteRequest->execute(array($_SESSION["id"]));

    header("Location: profile.php");
}
?>
<br>
<form method="POST">
    <label>
        Frequency at which the game will save your progress (in seconds) :
        <input type="number" step="1" name="saveFrequency" min="1" oninput="validity.valid||(value='');" value="<?= $saveFrequency ?>">
    </label><br>
    <label>
        Frequency at which the game will update the values displayed (in seconds) :
        <input type="number" step="1" name="updateFrequency" min="1" oninput="validity.valid||(value='');" value="<?= $updateFrequency ?>">
    </label><br>
    <input type="submit" name="saveSettings" value="Save modifications">
    <input type="submit" name="default" value="Return to default values">
</form>