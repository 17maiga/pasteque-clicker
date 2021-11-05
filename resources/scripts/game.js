const settings = getSettings();
document.getElementById("settings").remove();

function buy(item) {
    let score = getData("score");
    switch (item) {
        case "score":
            score++;
            setData("score", score);
            break;
        case "cursor":
            let cursor = getData("cursor");
            if (score >= settings["cursorPrice"] + settings["priceIncreaseFactor"]*cursor) {
                score -= settings["cursorPrice"] + settings["priceIncreaseFactor"]*cursor;
                cursor++;
                setData("score", score);
                setData("cursor", cursor);
            }
            break;
    }
    displayPrices();
}

function getData(data) {
    switch (data){
        case "score":
            return parseInt(document.getElementById("pastequeCount").innerText);
        case "cursor":
            return parseInt(document.getElementById("cursorCount").innerText);
    }
}

function setData(data, value) {
    switch (data) {
        case "score":
            document.getElementById("pastequeCount").innerText = value.toString(); break;
        case "cursor":
            document.getElementById("cursorCount").innerText = value.toString(); break;
    }
}

function saveGame() {
    const data = {
        score: getData("score"),
        cursor: getData("cursor"),
    }

    const jsonData = JSON.stringify(data);
    const saveRequest = new XMLHttpRequest();

    saveRequest.open("POST", "../resources/ajax/save.php", true);
    saveRequest.setRequestHeader("Content-type", "application/json");
    saveRequest.send(jsonData);
}

function updateGame() {
    let score = getData("score"), cursor = getData("cursor"), gain = 0;
    gain += cursor / settings["updateFrequency"];
    setData("score", score + gain);
}

function startGameLoop() {
    setInterval(saveGame, 1000 * settings["saveFrequency"]);
    setInterval(updateGame, 1000 * settings["updateFrequency"]);
}

function displayPrices() {
    let cursor = getData("cursor");
    document.getElementById("cursorPrice").innerText = (settings["cursorPrice"] + settings["priceIncreaseFactor"]*cursor).toString();
}

function getSettings() {
    return {
        saveFrequency: parseInt(document.getElementById("saveFrequency").innerText),
        updateFrequency: parseFloat(document.getElementById("updateFrequency").innerText),
        priceIncreaseFactor: parseFloat(document.getElementById("priceIncreaseFactor").innerText),
        cursorPrice: parseInt(document.getElementById("cursorPrice").innerText)
    };
}