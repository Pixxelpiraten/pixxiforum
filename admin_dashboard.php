<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_admin();

// Statistiken laden
$users_total = $mysqli->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$threads_total = $mysqli->query("SELECT COUNT(*) AS c FROM threads")->fetch_assoc()['c'];
$posts_total = $mysqli->query("SELECT COUNT(*) AS c FROM posts")->fetch_assoc()['c'];
$cats_total = $mysqli->query("SELECT COUNT(*) AS c FROM categories")->fetch_assoc()['c'];
$chat_total = $mysqli->query("SELECT COUNT(*) AS c FROM chat")->fetch_assoc()['c'];

// User online (letzte 5 Minuten)
$users_online = $mysqli->query("
    SELECT COUNT(*) AS c 
    FROM users 
    WHERE last_active > NOW() - INTERVAL 5 MINUTE
")->fetch_assoc()['c'];

// Chat‑Traffic (Nachrichten pro Tag)
$traffic = $mysqli->query("
    SELECT DATE(created_at) AS d, COUNT(*) AS c
    FROM chat
    GROUP BY DATE(created_at)
    ORDER BY d DESC
    LIMIT 7
");
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Dashboard – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
<link rel="stylesheet" href="assets/css/matrix_global.css">
<style>
.statbox {
    border:1px solid #00ff41;
    padding:10px;
    margin-bottom:10px;
    background:rgba(0,20,0,0.4);
}
</style>
</head>
<body>

<canvas id="matrix"></canvas>
<script src="assets/js/matrix.js"></script>

<div class="box" style="width:900px;">
    <h2>Dashboard – Systemstatistik</h2>
    <p><a href="admin.php">Zurück</a></p>

    <div class="statbox">
        <h3>Allgemeine Statistik</h3>
        <p>👤 Benutzer insgesamt: <strong><?= $users_total ?></strong></p>
        <p>🟢 Benutzer online (5 Min): <strong><?= $users_online ?></strong></p>
        <p>📂 Kategorien: <strong><?= $cats_total ?></strong></p>
        <p>🧵 Threads: <strong><?= $threads_total ?></strong></p>
        <p>💬 Posts: <strong><?= $posts_total ?></strong></p>
        <p>⚡ Chat‑Nachrichten: <strong><?= $chat_total ?></strong></p>
    </div>

    <div class="statbox">
        <h3>Chat‑Traffic (letzte 7 Tage)</h3>
        <?php while ($row = $traffic->fetch_assoc()): ?>
            <p><?= $row['d'] ?> → <?= $row['c'] ?> Nachrichten</p>
        <?php endwhile; ?>
    </div>

</div>

</body>
</html>
