<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $mysqli->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash, $role);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;

            header("Location: index.php");
            exit;
        } else {
            $message = "Falsches Passwort.";
        }
    } else {
        $message = "Benutzer nicht gefunden.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Login – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box">
    <h2>Login</h2>

    <?php if (isset($_GET["registered"])): ?>
        <p style="color:#00ff41;">Registrierung erfolgreich! Bitte einloggen.</p>
    <?php endif; ?>

    <?php if ($message): ?>
        <p style="color:#ff4444;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Benutzername">
        <input type="password" name="password" placeholder="Passwort">
        <button class="btn">Login</button>
    </form>

    <p><a href="register.php">Registrieren</a></p>
</div>

</body>
</html>
