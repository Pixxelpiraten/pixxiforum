<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

header("Content-Type: application/json");

// GET → Posts eines Threads
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $thread = intval($_GET["thread"] ?? 0);

    $stmt = $mysqli->prepare("SELECT author, content, created_at FROM posts WHERE thread_id = ? ORDER BY id ASC");
    $stmt->bind_param("i", $thread);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode($rows);
    exit;
}

// POST → Antwort erstellen
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_login();

    $thread = intval($_POST["thread"] ?? 0);
    $content = trim($_POST["content"] ?? "");
    $author = $_SESSION["username"];

    if ($content !== "") {
        $stmt = $mysqli->prepare("INSERT INTO posts (thread_id, author, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $thread, $author, $content);
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
        exit;
    }

    echo json_encode(["error" => "Missing content"]);
    exit;
}

echo json_encode(["error" => "Invalid request"]);
