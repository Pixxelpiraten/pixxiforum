<?php
// XAMPP Standardwerte
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP hat standardmäßig kein Passwort
$DB_NAME = 'pixxiforum';

// Verbindung herstellen
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Fehler abfangen
if ($mysqli->connect_errno) {
    die("MySQL-Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

// UTF8 für Umlaute
$mysqli->set_charset("utf8mb4");
?>
