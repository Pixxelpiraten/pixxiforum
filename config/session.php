<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_login(): void {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function require_admin(): void {
    if (!is_admin()) {
        header("Location: index.php");
        exit;
    }
}
?>
if (is_logged_in()) {
    $u = $_SESSION["username"];
    $mysqli->query("UPDATE users SET last_active = NOW() WHERE username = '$u'");
}
