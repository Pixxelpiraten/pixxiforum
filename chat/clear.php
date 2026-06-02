<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/session.php';
require_admin();

$mysqli->query("TRUNCATE TABLE chat");

header("Location: ../admin.php");
exit;
