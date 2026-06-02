<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username === "" || $password === "") {
        $message = "Bitte alle Felder ausfüllen.";
    } else {
        // Prüfen, ob Username existiert
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Benutzername existiert bereits.";
        } else {
            // Passwort hashen
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'member')");
            $stmt->bind_param("ss", $username, $hash);
            $stmt->execute();

            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Registrierung – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box">
    <h2>Registrierung</h2>

    <?php if ($message): ?>
        <p style="color:#ff4444;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Benutzername">
        <input type="password" name="password" placeholder="Passwort">
        <button class="btn">Registrieren</button>
    </form>

    <p><a href="login.php">Zum Login</a></p>
</div>

</body>
</html>
