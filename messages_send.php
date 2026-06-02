<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receiver = trim($_POST["receiver"]);
    $content  = trim($_POST["content"]);
    $sender   = $_SESSION["username"];

    if ($receiver !== "" && $content !== "") {

        // Prüfen, ob Empfänger existiert
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $receiver);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $message = "Empfänger existiert nicht.";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO messages (sender, receiver, content) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $sender, $receiver, $content);
            $stmt->execute();

            header("Location: messages.php");
            exit;
        }
    } else {
        $message = "Bitte alles ausfüllen.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Nachricht senden – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Neue Nachricht</h2>

    <?php if ($message): ?>
        <p style="color:#ff4444;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="receiver" placeholder="Empfänger">
        <textarea name="content" style="width:100%;height:200px;" placeholder="Nachricht"></textarea>
        <button class="btn">Senden</button>
    </form>

    <p><a href="messages.php">Zurück</a></p>
</div>

</body>
</html>
