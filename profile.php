<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$user = $_GET["user"] ?? $_SESSION["username"];

// Userdaten laden
$stmt = $mysqli->prepare("SELECT username, bio, avatar, last_active, role FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($username, $bio, $avatar, $last_active, $role);
$stmt->fetch();
$stmt->close();

if (!$username) {
    die("Benutzer nicht gefunden.");
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Profil – <?= htmlspecialchars($username) ?></title>
<link rel="stylesheet" href="assets/css/matrix.css">
<style>
.avatar {
    width:150px; height:150px;
    border:2px solid #00ff41;
    object-fit:cover;
}
</style>
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Profil von <?= htmlspecialchars($username) ?></h2>

    <img src="assets/img/<?= htmlspecialchars($avatar) ?>" class="avatar">

    <p><strong>Rolle:</strong> <?= $role ?></p>
    <p><strong>Letzte Aktivität:</strong> <?= $last_active ?></p>

    <h3>Über mich</h3>
    <p><?= nl2br(htmlspecialchars($bio)) ?></p>

    <?php if ($username === $_SESSION["username"]): ?>
        <p><a href="profile_edit.php">Profil bearbeiten</a></p>
    <?php endif; ?>

    <p><a href="index.php">Zurück</a></p>
</div>

</body>
</html>
