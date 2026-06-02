<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$user = $_SESSION["username"];

// Nachrichten laden
$stmt = $mysqli->prepare("SELECT id, sender, content, created_at, read_status FROM messages WHERE receiver = ? ORDER BY id DESC");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Nachrichten – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
<style>
.unread { color:#00ff41; font-weight:bold; }
</style>
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Private Nachrichten</h2>
    <p><a href="index.php">Zurück</a> | <a href="message_send.php">Neue Nachricht</a></p>

    <?php while ($m = $result->fetch_assoc()): ?>
        <div class="<?= $m['read_status'] ? '' : 'unread' ?>">
            <a href="message_view.php?id=<?= $m['id'] ?>">
                Von: <?= htmlspecialchars($m['sender']) ?>  
                <br>
                <small><?= $m['created_at'] ?></small>
            </a>
            <hr>
        </div>
    <?php endwhile; ?>

</div>

</body>
</html>
