function buy(item) {
    let score = getData("score");
    switch (item) {
        case "score":
            score++;
            setData("score", score);
            break;
        case "cursor":
            var cursor = getData("cursor");
            if (score >= 15 + 2*cursor) {
                score -= 15 + 2*cursor;
                cursor++;
                setData("score", score);
                setData("cursor", cursor);
            }
            break;
    }
}

function getData(data) {
    switch (data){
        case "score":
            return parseInt(document.getElementById("pastequeDisplay").innerText);
        case "cursor":
            return parseInt(document.getElementById("cursorAdd").getAttribute("value"));
    }
}

function setData(data, value) {
    switch (data) {
        case "score":
            document.getElementById("pastequeDisplay").innerText = value.toString(); break;
        case "cursor":
            document.getElementById("cursorAdd").setAttribute("value", value.toString()); break;
    }
}

function saveGame() {
    const data = {
        score: getData("score"),
        cursor: getData("cursor"),
    }

    const jsonData = JSON.stringify(data);
    const saveRequest = new XMLHttpRequest();

    saveRequest.onload = loadGame();

    saveRequest.open("POST", "../resources/ajax/save.php", true);
    saveRequest.setRequestHeader("Content-type", "application/json");
    saveRequest.send(jsonData);
}

function updateGame() {

}

function loadGame() {

}

function startGameLoop() {
    setInterval(saveGame, 60000);
    setInterval(updateGame, 1000);
}
