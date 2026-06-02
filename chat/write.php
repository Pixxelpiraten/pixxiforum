<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';
require_login();

$user = $_SESSION["username"];
$msg  = $_GET["msg"] ?? "";

if ($msg !== "") {
    $msg = htmlspecialchars($msg);

    $stmt = $mysqli->prepare("INSERT INTO chat (username, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $user, $msg);
    $stmt->execute();
}
?>
