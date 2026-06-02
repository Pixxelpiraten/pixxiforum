<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$thread_id = intval($_GET["id"] ?? 0);

// Thread laden
$stmt = $mysqli->prepare("SELECT title FROM threads WHERE id = ?");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$stmt->bind_result($title);
$stmt->fetch();
$stmt->close();

if (!$title) {
    die("Thread nicht gefunden.");
}

// Posts laden
$stmt = $mysqli->prepare("SELECT id, author, content, created_at FROM posts WHERE thread_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$posts = $stmt->get_result();

// Antwort absenden
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = trim($_POST["content"]);
    $author = $_SESSION["username"];

    if ($content !== "") {
        $stmt = $mysqli->prepare("INSERT INTO posts (thread_id, author, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $thread_id, $author, $content);
        $stmt->execute();

        header("Location: post.php?id=" . $thread_id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($title) ?> – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
<style>
.postbox {
    padding:10px;
    border:1px solid #00ff41;
    margin-bottom:10px;
    background:rgba(0,20,0,0.4);
}
</style>
</head>
<body>

<div class="box" style="width:900px;">
    <h2><?= htmlspecialchars($title) ?></h2>

    <?php while ($p = $posts->fetch_assoc()): ?>
        <div class="postbox">
            <strong><?= htmlspecialchars($p['author']) ?></strong>
            <br>
            <?= nl2br(htmlspecialchars($p['content'])) ?>
            <br>
            <small><?= $p['created_at'] ?></small>
        </div>
    <?php endwhile; ?>

    <h3>Antwort schreiben</h3>
    <form method="POST">
        <textarea name="content" style="width:100%;height:150px;"></textarea>
        <button class="btn">Antwort senden</button>
    </form>

    <p><a href="thread.php?cat=1">Zurück</a></p>
</div>

</body>
</html>


    <h3>Antworten</h3>
    <form method="POST">
        <textarea name="content" style="width:100%;height:150px;"></textarea>
        <button class="btn">Antwort senden</button>
    </form>

    <p><a href="thread.php?cat=1">Zurück</a></p>
</div>

</body>
</html>
