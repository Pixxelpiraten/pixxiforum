<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';
require_login();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Live‑Chat – PixxiForum</title>
<link rel="stylesheet" href="../assets/css/matrix.css">
<style>
#matrix { position: fixed; top:0; left:0; z-index:-1; }
#chatbox {
    position:absolute; top:0; left:0;
    width:100%; height:80vh;
    overflow-y:auto; padding:20px;
    background:rgba(0,20,0,0.4);
    border-bottom:1px solid #00ff41;
}
#inputbox {
    position:absolute; bottom:0; left:0;
    width:100%; padding:20px;
    display:flex; gap:10px;
    background:rgba(0,20,0,0.6);
}
input, button {
    background:#001900; border:1px solid #00ff41;
    color:#00ff41; padding:10px;
}
input { flex:1; }
button:hover { background:#003300; cursor:pointer; }
</style>
</head>
<body>

<canvas id="matrix"></canvas>

<div id="chatbox">Lade Chat…</div>

<div id="inputbox">
    <input type="text" id="msg" placeholder="Nachricht eingeben…">
    <button onclick="sendMsg()">Senden</button>
</div>

<audio id="sound" src="notify.mp3"></audio>

<script src="../assets/js/matrix.js"></script>

<script>
// USERNAME
let user = "<?= $_SESSION['username'] ?>";

// USER-FARBE
function getUserColor(name) {
    let hash = 0;
    for (let i = 0; i < name.length; i++)
        hash = name.charCodeAt(i) + ((hash << 5) - hash);

    let color = "#";
    for (let i = 0; i < 3; i++) {
        let value = (hash >> (i * 8)) & 255;
        color += ("00" + value.toString(16)).substr(-2);
    }
    return color;
}

// CHAT LADEN
let lastChat = "";

function loadChat() {
    fetch("read.php")
        .then(r => r.text())
        .then(t => {
            if (t !== lastChat) {
                document.getElementById("sound").play();
                lastChat = t;
            }
            document.getElementById("chatbox").innerHTML = t;
            document.getElementById("chatbox").scrollTop = 999999;
        });
}
setInterval(loadChat, 1000);
loadChat();

// ENTER SENDEN
document.getElementById("msg").addEventListener("keypress", e => {
    if (e.key === "Enter") sendMsg();
});

// ANTI-SPAM
let lastSend = 0;

// SENDEN
function sendMsg() {
    let now = Date.now();
    if (now - lastSend < 1000) return;
    lastSend = now;

    let msg = document.getElementById("msg").value;
    if (msg.trim() === "") return;

    fetch("write.php?msg=" + encodeURIComponent(msg));
    document.getElementById("msg").value = "";
}
</script>

</body>
</html>
