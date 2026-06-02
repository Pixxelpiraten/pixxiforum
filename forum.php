<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

// Kategorien laden
$result = $mysqli->query("SELECT id, name FROM categories ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Forum – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Forum – Kategorien</h2>
    <p><a href="index.php">Zurück</a></p>

    <?php while ($cat = $result->fetch_assoc()): ?>
        <div class="thread">
            <a href="thread.php?cat=<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        </div>
    <?php endwhile; ?>

</div>

</body>
</html>
