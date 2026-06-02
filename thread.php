<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$cat = intval($_GET["cat"] ?? 0);

// Threads laden
$stmt = $mysqli->prepare("SELECT id, title, author, created_at FROM threads WHERE category_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $cat);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Threads – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Threads</h2>

    <p><a href="newthread.php?cat=<?= $cat ?>">Neuen Thread erstellen</a></p>
    <p><a href="forum.php">Zurück</a></p>

    <?php while ($t = $result->fetch_assoc()): ?>
        <div class="thread">
            <a href="post.php?id=<?= $t['id'] ?>">
                <?= htmlspecialchars($t['title']) ?>
            </a>
            <br>
            <small>von <?= htmlspecialchars($t['author']) ?> – <?= $t['created_at'] ?></small>
        </div>
    <?php endwhile; ?>

</div>

</body>
</html>
