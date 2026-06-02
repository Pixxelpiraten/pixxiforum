<?php
// INSTALLER – PixxiForum Ultra Version C

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "pixxiforum";

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS);

if ($mysqli->connect_errno) {
    die("<h1>MySQL-Verbindung fehlgeschlagen:</h1> " . $mysqli->connect_error);
}

// Datenbank erstellen
$mysqli->query("CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$mysqli->select_db($DB_NAME);

// Tabellen erstellen
$tables = [

"CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'member',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
)",

"CREATE TABLE IF NOT EXISTS threads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(255),
    author VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT,
    author VARCHAR(50),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS chat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)"

];

foreach ($tables as $sql) {
    if (!$mysqli->query($sql)) {
        die("<p>Fehler beim Erstellen einer Tabelle: " . $mysqli->error . "</p>");
    }
}

// Admin anlegen, falls nicht vorhanden
$adminCheck = $mysqli->query("SELECT id FROM users WHERE username='admin'");

if ($adminCheck->num_rows === 0) {
    $pass = password_hash(bin2hex(random_bytes(12)), PASSWORD_BCRYPT); // historisches Klartext-Passwort entfernt
    $mysqli->query("INSERT INTO users (username, password, role) VALUES ('admin', '$pass', 'admin')");
}

// Standardkategorien
$mysqli->query("INSERT IGNORE INTO categories (id, name) VALUES
(1, 'Allgemein'),
(2, 'Technik'),
(3, 'Gaming'),
(4, 'Offtopic')
");

echo "
<!DOCTYPE html>
<html lang='de'>
<head>
<meta charset='UTF-8'>
<title>Installer – PixxiForum</title>
<link rel='stylesheet' href='assets/css/matrix.css'>
</head>
<body>
<div class='box'>
<h2>Installation erfolgreich!</h2>
<p>Datenbank <strong>$DB_NAME</strong> wurde eingerichtet.</p>
<p>Admin-Login: <strong>[redacted]</strong></p>
<p><a href='index.php'>Zum Forum starten</a></p>
</div>
</body>
</html>
";
?>
