<?php
require_once "../resources/includes/header.php";

if (!isset($_SESSION["id"])){ // Redirect to login page if no user is logged in
    header("Location: login.php");
}

// Fetches the user's info from the database and store them in $userInfo
$userInfo = $bdd->prepare("SELECT * FROM users WHERE user_id = ?");
$userInfo->execute(array($_SESSION["id"]));
$userInfo = $userInfo->fetch();

// Fetches the global settings from the database and store them in $globalSettings
$globalSettings = $bdd->prepare("SELECT * FROM global_settings WHERE id = ?");
$globalSettings->execute(array(0));
$globalSettings = $globalSettings->fetch();

// Fetches the user's settings from the database, if they exist
$userSettings = $bdd->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$userSettings->execute(array($_SESSION["id"]));

if ($userSettings->rowCount() != 0) { // If the user has set specific settings, the $saveFrequency and $updateFrequency variables are set with the user's settings. 
    $userSettingsSet = true;
    $userSettings = $userSettings->fetch();
    $saveFrequency = $userSettings["save_frequency"];
    $updateFrequency = $userSettings["update_frequency"];
} else { // If the user has not set specific settings, the $saveFrequency and $updateFrequency variables are set with the global settings. 
    $userSettingsSet = false;
    $saveFrequency = $globalSettings["save_frequency"];
    $updateFrequency = $globalSettings["update_frequency"];
}

// Form results

// Triggers when the 'Save modifications' button is clicked
if (isset($_POST["saveSettings"])) {
    if ($userSettingsSet) { 
        // If the user had previously set specific settings, they are updated in the database
        $saveRequest = $bdd->prepare("UPDATE user_settings SET save_frequency = ?, update_frequency = ? WHERE user_id = ?");
        $saveRequest->execute(array(floatval($_POST["saveFrequency"]), floatval($_POST["updateFrequency"]), $_SESSION["id"]));
    } else { 
        // If the user had not set specific settings, they are inserted in the database
        $saveRequest = $bdd->prepare("INSERT INTO user_settings (user_id, save_frequency, update_frequency) VALUES (?,?,?)");
        $saveRequest->execute(array($_SESSION["id"], floatval($_POST["saveFrequency"]), floatval($_POST["updateFrequency"])));
    }
    // Redirect to the user's profile
    header("Location: profile.php"); 
}

// Triggers when the 'Return to default values' button is clicked
if(isset($_POST["default"])){ 
    // Deletes any user-set information from the user_settings table of the database
    $deleteRequest = $bdd->prepare("DELETE FROM user_settings WHERE user_id = ?");
    $deleteRequest->execute(array($_SESSION["id"]));
    // Redirect to the user's profile
    header("Location: profile.php");
}
?>
<br>
<form method="POST">
    <label>
        <!--Drop-down list that allows the user to pick between a set of values for the frequency at which the game will automatically save their progress-->
        Frequency at which the game will save your progress (in seconds) :
        <select name="saveFrequency">
            <option value="30" <?php if($saveFrequency == 30) {echo "selected";} ?>>30</option>
            <option value="60" <?php if($saveFrequency == 60) {echo "selected";} ?>>60</option>
            <option value="120" <?php if($saveFrequency == 120) {echo "selected";} ?>>120</option>
            <option value="300" <?php if($saveFrequency == 300) {echo "selected";} ?>>300</option>
            <option value="600" <?php if($saveFrequency == 600) {echo "selected";} ?>>600</option>
        </select>
    </label><br>
    <label>
        <!--Drop-down list that allows the user to pick between a set of values for the frequency at which the game will update the values -->
        Frequency at which the game will update the values displayed (in seconds) :
        <select name="updateFrequency">
            <option value="1" <?php if($updateFrequency == 1) {echo "selected";} ?> >1</option>
            <option value="2" <?php if($updateFrequency == 2) {echo "selected";} ?> >2</option>
            <option value="5" <?php if($updateFrequency == 5) {echo "selected";} ?> >5</option>
        </select>
    </label><br>
    <!--Buttons to submit the values or return to the default ones-->
    <input type="submit" name="saveSettings" value="Save modifications">
    <input type="submit" name="default" value="Return to default values">
</form>