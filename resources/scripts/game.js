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
    console.log("the loop is working :)");
}

function startGameLoop() {
    setInterval(saveGame, 60000);
}
