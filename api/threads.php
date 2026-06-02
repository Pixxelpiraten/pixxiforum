<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

header("Content-Type: application/json");

// GET → Threads einer Kategorie
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $cat = intval($_GET["cat"] ?? 0);

    $stmt = $mysqli->prepare("SELECT id, title, author, created_at FROM threads WHERE category_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $cat);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode($rows);
    exit;
}

// POST → Thread erstellen
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_login();

    $cat = intval($_POST["cat"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $author = $_SESSION["username"];

    if ($title !== "" && $content !== "") {
        // Thread
        $stmt = $mysqli->prepare("INSERT INTO threads (category_id, title, author) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $cat, $title, $author);
        $stmt->execute();
        $thread_id = $stmt->insert_id;

        // Erster Post
        $stmt = $mysqli->prepare("INSERT INTO posts (thread_id, author, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $thread_id, $author, $content);
        $stmt->execute();

        echo json_encode(["status" => "ok", "thread_id" => $thread_id]);
        exit;
    }

    echo json_encode(["error" => "Missing fields"]);
    exit;
}

echo json_encode(["error" => "Invalid request"]);
