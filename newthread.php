<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$cat = intval($_GET["cat"] ?? 0);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $author = $_SESSION["username"];

    if ($title !== "" && $content !== "") {
        $stmt = $mysqli->prepare("INSERT INTO threads (category_id, title, author) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $cat, $title, $author);
        $stmt->execute();

        $thread_id = $stmt->insert_id;

        $stmt = $mysqli->prepare("INSERT INTO posts (thread_id, author, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $thread_id, $author, $content);
        $stmt->execute();

        header("Location: thread.php?id=" . $thread_id);
        exit;
    } else {
        $message = "Bitte alles ausfüllen.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Neuer Thread – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Neuen Thread erstellen</h2>

    <?php if ($message): ?>
        <p style="color:#ff4444;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Titel">
        <textarea name="content" placeholder="Beitrag" style="width:100%;height:200px;"></textarea>
        <button class="btn">Erstellen</button>
    </form>

    <p><a href="forum.php">Zurück</a></p>
</div>

</body>
</html>
