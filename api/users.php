<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';

header("Content-Type: application/json");

// GET → Userliste
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = $mysqli->query("SELECT id, username, role FROM users ORDER BY id ASC");

    $rows = [];
    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode($rows);
    exit;
}

echo json_encode(["error" => "Invalid request"]);
