// Settings

// Gets the settings from the HTML page and saves it as a global variable
const settings = getSettings(); 
/* 
Removes the element in which the settings are stored from the HTML page. 
This is to prevent users from seeing this info in the navigator's dev tools.
This doesn't serve any purpose as the user won't be able to modify this information anyways, but it looks cleaner in my opinion.
*/
document.getElementById("settings").remove();

// Functions

/**
 * Allows a user to buy an item using its score as currency.
 * 
 * Increments the number of a specified item in the HTML page, if the score value is high enough for the purchase to happen. 
 * This is calculated using values set by site administrators using the following formula, for items other than the score itself :
 * price_of_specified_item + (increase_factor ^ (number_of_specified_item / 10)).
 * 
 * @see getData()
 * @see setData()
 * @see displayPrices()
 * 
 * @param {string} item The type of item you wish to buy. Possibilities include "score", "cursor"...
 */
function buy(item) {
    let score = getData("score");
    switch (item) {
        case "score":
            score++;
            setData("score", score);
            break;
        case "cursor":
            let cursor = getData("cursor");
            if (score >= settings["cursorPrice"] + Math.round(Math.pow(settings["priceIncreaseFactor"], cursor/10))) {
                score -= settings["cursorPrice"] + Math.round(Math.pow(settings["priceIncreaseFactor"], cursor/10));
                cursor++;
                setData("score", score);
                setData("cursor", cursor);
            }
            break;
    }
    displayPrices();
}

/**
 * Returns the amount of a specified item from the HTML page.
 * 
 * @param {string} item The item we wish to get the amount of. Possibilities include "score", "cursor"...
 * @returns {number} The amount of the item. 
 */
function getData(item) {
    switch (item){
        case "score":
            return parseFloat(document.getElementById("pastequeCount").innerText);
        case "cursor":
            return parseFloat(document.getElementById("cursorCount").innerText);
    }
}

/**
 * Sets the number of an item to a specified value on the HTML page.
 * 
 * @param {string} item The item we want to set the value of. Possibilities include "score", "cursor"...
 * @param {number} value The value we want to set.
 */
function setData(item, value) {
    switch (item) {
        case "score":
            document.getElementById("pastequeCount").innerText = parseFloat(value).toString(); break;
        case "cursor":
            document.getElementById("cursorCount").innerText = value.toString(); break;
    }
}

/**
 * Updates the score value depending on the other items owned by the player.
 * 
 * Increments the score by adding to it the amount of cookies earned per second multiplied by the frequency at which the game should be updated 
 * (defined by either the player or the admin)
 * 
 * @see getData()
 * @see setData()
 */
function updateGame() {
    let score = getData("score"), cursor = getData("cursor"), gain = 0;
    gain += cursor * settings["updateFrequency"];
    setData("score", score + gain);
}

/**
 * Starts two loops:  the update loop and the game loop.
 * 
 * The loops run the saveGame() and updateGame() functions at two different intervals depending on the settings set by the user
 * @see saveGame()
 * @see updateGame()
 */
function startGameLoop() {
    setInterval(saveGame, 1000 * settings["saveFrequency"]);
    setInterval(updateGame, 1000 * settings["updateFrequency"]);
}

/**
 * Displays the prices for the next upgrades
 * 
 * Calculates the price for the next purchase of an upgrade by getting the number of said upgrade and calculating the price according to this formula:
 * upgrade_price + price_increase_factor*number_of_upgrade_owned
 * @see getData()
 */
function displayPrices() {
    let cursor = getData("cursor");
    document.getElementById("cursorPrice").innerText = (settings["cursorPrice"] + Math.round(Math.pow(settings["priceIncreaseFactor"], cursor/10))).toString();
}

/**
 * Fetches the settings from the HTML page and turns them into an array
 * 
 * @returns {Array<number>}
 */
function getSettings() {
    return {
        saveFrequency: parseInt(document.getElementById("saveFrequency").innerText),
        updateFrequency: parseInt(document.getElementById("updateFrequency").innerText),
        priceIncreaseFactor: parseInt(document.getElementById("priceIncreaseFactor").innerText),
        cursorPrice: parseInt(document.getElementById("cursorPrice").innerText)
    };
}

/**
 * Sends an ajax request to the server that saves your game data to the database.
 * 
 * Constructs an array of your game data and sends it to the server as a JSON string. 
 * The server then saves this data to the database.
 * @see getData()
 */
function saveGame() {
    const data = {
        score: getData("score"),
        cursor: getData("cursor"),
    }

    const jsonData = JSON.stringify(data);
    const saveRequest = new XMLHttpRequest();

    saveRequest.open("POST", "../resources/server/save.php", true);
    saveRequest.setRequestHeader("Content-type", "application/json");
    saveRequest.send(jsonData);
}
