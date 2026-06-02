<?php
require __DIR__ . '/config/session.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>PixxiForum – Startseite</title>
<link rel="stylesheet" href="assets/css/matrix.css">
<style>
#matrix {
    position: fixed;
    top: 0;
    left: 0;
    z-index: -1;
}
.menu {
    width: 400px;
    margin: 120px auto;
    padding: 20px;
    border: 1px solid #00ff41;
    background: rgba(0,20,0,0.4);
    text-align: center;
}
.menu a {
    display: block;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #00ff41;
}
</style>
</head>
<body>

<canvas id="matrix"></canvas>

<div class="menu">
    <h2>PixxiForum</h2>

    <?php if (is_logged_in()): ?>
        <p>Willkommen, <?= $_SESSION["username"] ?></p>
        <a href="forum.php">Forum</a>
        <a href="chat/chat.php">Live‑Chat</a>
        <?php if (is_admin()): ?>
            <a href="admin.php">Adminbereich</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Registrieren</a>
    <?php endif; ?>
</div>

<script src="assets/js/matrix.js"></script>
</body>
</html>
