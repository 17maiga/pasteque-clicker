<?php
// Header and redirections
require_once '../resources/includes/header.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
} elseif ($_SESSION['type'] != 'admin') {
    header('Location: ../profile/profile.php');
}

// Fetch the global settings from the database
$globalSettings = $bdd->prepare('SELECT * FROM global_settings WHERE id = ?');
$globalSettings->execute(array(0));
$globalSettings = $globalSettings->fetch();

// Triggers when the 'Save modifications' button is clicked. Updates values in the database and redirects to the portal
if(isset($_POST['saveSettings'])){
    $saveRequest = $bdd->prepare('UPDATE global_settings SET save_frequency = ?, update_frequency = ?, price_increase_factor = ?, cursor_price = ? WHERE id = ?');
    $saveRequest->execute(array($_POST['saveFrequency'], $_POST['updateFrequency'], $_POST['priceIncreaseFactor'], $_POST['cursorPrice'], 0));

    header('Location: portal.php');
}

// Triggers when the 'Return to default values' button is clicked. Sets values in the database back to defaults and redirects to the portal
if(isset($_POST['default'])){
    $saveRequest = $bdd->prepare('UPDATE global_settings SET save_frequency = ?, update_frequency = ?, price_increase_factor = ?, cursor_price = ? WHERE id = ?');
    $saveRequest->execute(array(60, 1, 2, 15, 0));

    header('Location: portal.php');
}

?>

<br>
<form method="POST">
    <label>
        Save Frequency :
        <!--Drop-down menu for the save frequency-->
        <select name="saveFrequency">
            <option value="30" <?php if($globalSettings['save_frequency'] == 30) echo 'selected'; ?>>30</option>
            <option value="60" <?php if($globalSettings['save_frequency'] == 60) echo 'selected'; ?>>60</option>
            <option value="120" <?php if($globalSettings['save_frequency'] == 120) echo 'selected'; ?>>120</option>
            <option value="300" <?php if($globalSettings['save_frequency'] == 300) echo 'selected'; ?>>300</option>
            <option value="600" <?php if($globalSettings['save_frequency'] == 600) echo 'selected'; ?>>600</option>
        </select>
    </label><br>
    <label>
        Update Frequency :
        <!--Drop-down menu for the update frequency-->
        <select name="updateFrequency">
            <option value="1" <?php if($globalSettings['update_frequency'] == 1) echo 'selected'; ?> >1</option>
            <option value="2" <?php if($globalSettings['update_frequency'] == 2) echo 'selected'; ?> >2</option>
            <option value="5" <?php if($globalSettings['update_frequency'] == 5) echo 'selected'; ?> >5</option>
        </select>
    </label><br>
    <label>
        Price Increase Factor :
        <input type="number" step="1" name="priceIncreaseFactor" min="1" value="<?= $globalSettings['price_increase_factor'] ?>">
    </label><br>
    <label>
        Cursor Price :
        <input type="number" step="1" name="cursorPrice" min="1" value="<?= $globalSettings['cursor_price'] ?>">
    </label><br>
    <input type="submit" name="saveSettings" value="Save modifications">
    <input type="submit" name="default" value="Return to default values">
</form>