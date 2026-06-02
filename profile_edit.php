<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_login();

$user = $_SESSION["username"];
$message = "";

// Daten laden
$stmt = $mysqli->prepare("SELECT bio, avatar FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($bio, $avatar);
$stmt->fetch();
$stmt->close();

// Speichern
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newBio = trim($_POST["bio"]);

    // Avatar Upload
    if (!empty($_FILES["avatar"]["name"])) {
        $file = $_FILES["avatar"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if (in_array($ext, ["jpg", "jpeg", "png", "gif"])) {
            $newAvatar = $user . "." . $ext;
            move_uploaded_file($file["tmp_name"], "assets/img/" . $newAvatar);
        } else {
            $message = "Ungültiges Bildformat.";
        }
    } else {
        $newAvatar = $avatar;
    }

    if ($message === "") {
        $stmt = $mysqli->prepare("UPDATE users SET bio = ?, avatar = ? WHERE username = ?");
        $stmt->bind_param("sss", $newBio, $newAvatar, $user);
        $stmt->execute();

        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Profil bearbeiten – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
</head>
<body>

<div class="box" style="width:800px;">
    <h2>Profil bearbeiten</h2>

    <?php if ($message): ?>
        <p style="color:#ff4444;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <textarea name="bio" style="width:100%;height:150px;"><?= htmlspecialchars($bio) ?></textarea>

        <p>Avatar ändern:</p>
        <input type="file" name="avatar">

        <button class="btn">Speichern</button>
    </form>

    <p><a href="profile.php">Zurück</a></p>
</div>

</body>
</html>
