<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

header("Content-Type: application/json");

// GET → Chat lesen
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = $mysqli->query("SELECT username, message, created_at FROM chat ORDER BY id ASC");
    $rows = [];

    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode($rows);
    exit;
}

// POST → Chat schreiben
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_login();

    $user = $_SESSION["username"];
    $msg  = trim($_POST["message"] ?? "");

    if ($msg !== "") {
        $stmt = $mysqli->prepare("INSERT INTO chat (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $msg);
        $stmt->execute();
    }

    echo json_encode(["status" => "ok"]);
    exit;
}

echo json_encode(["error" => "Invalid request"]);
