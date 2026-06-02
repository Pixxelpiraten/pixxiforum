<p><a href="admin_dashboard.php">📊 Dashboard</a></p>
<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/session.php';
require_admin();

// USER SPERREN / ENTSPERREN
if (isset($_GET["ban"])) {
    $id = intval($_GET["ban"]);
    $mysqli->query("UPDATE users SET role='banned' WHERE id=$id");
    header("Location: admin.php");
    exit;
}

if (isset($_GET["unban"])) {
    $id = intval($_GET["unban"]);
    $mysqli->query("UPDATE users SET role='member' WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// KATEGORIE LÖSCHEN
if (isset($_GET["delcat"])) {
    $id = intval($_GET["delcat"]);
    $mysqli->query("DELETE FROM categories WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// KATEGORIE ERSTELLEN
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["newcat"])) {
    $name = trim($_POST["newcat"]);
    if ($name !== "") {
        $stmt = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }
    header("Location: admin.php");
    exit;
}

// THREAD LÖSCHEN
if (isset($_GET["delthread"])) {
    $id = intval($_GET["delthread"]);
    $mysqli->query("DELETE FROM threads WHERE id=$id");
    $mysqli->query("DELETE FROM posts WHERE thread_id=$id");
    header("Location: admin.php");
    exit;
}

// POST LÖSCHEN
if (isset($_GET["delpost"])) {
    $id = intval($_GET["delpost"]);
    $mysqli->query("DELETE FROM posts WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// CHAT LEEREN
if (isset($_GET["clearchat"])) {
    $mysqli->query("TRUNCATE TABLE chat");
    header("Location: admin.php");
    exit;
}

// DATEN LADEN
$users = $mysqli->query("SELECT id, username, role FROM users ORDER BY id ASC");
$cats  = $mysqli->query("SELECT id, name FROM categories ORDER BY id ASC");
$threads = $mysqli->query("SELECT id, title, author FROM threads ORDER BY id DESC LIMIT 20");
$posts = $mysqli->query("SELECT id, author, content FROM posts ORDER BY id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Adminbereich – PixxiForum</title>
<link rel="stylesheet" href="assets/css/matrix.css">
<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
td, th {
    border: 1px solid #00ff41;
    padding: 5px;
}
</style>
</head>
<body>

<div class="box" style="width:900px;">
    <h2>Adminbereich</h2>
    <p><a href="index.php">Zurück</a></p>

    <h3>Userverwaltung</h3>
    <table>
        <tr><th>ID</th><th>Benutzername</th><th>Rolle</th><th>Aktion</th></tr>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <?php if ($u['role'] !== 'admin'): ?>
                        <?php if ($u['role'] !== 'banned'): ?>
                            <a href="admin.php?ban=<?= $u['id'] ?>">Sperren</a>
                        <?php else: ?>
                            <a href="admin.php?unban=<?= $u['id'] ?>">Entsperren</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Kategorien</h3>
    <form method="POST">
        <input type="text" name="newcat" placeholder="Neue Kategorie">
        <button class="btn">Erstellen</button>
    </form>

    <table>
        <tr><th>ID</th><th>Name</th><th>Aktion</th></tr>
        <?php while ($c = $cats->fetch_assoc()): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><a href="admin.php?delcat=<?= $c['id'] ?>">Löschen</a></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Letzte Threads</h3>
    <table>
        <tr><th>ID</th><th>Titel</th><th>Autor</th><th>Aktion</th></tr>
        <?php while ($t = $threads->fetch_assoc()): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['title']) ?></td>
                <td><?= htmlspecialchars($t['author']) ?></td>
                <td><a href="admin.php?delthread=<?= $t['id'] ?>">Löschen</a></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Letzte Posts</h3>
    <table>
        <tr><th>ID</th><th>Autor</th><th>Inhalt</th><th>Aktion</th></tr>
        <?php while ($p = $posts->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['author']) ?></td>
                <td><?= htmlspecialchars(substr($p['content'], 0, 50)) ?>...</td>
                <td><a href="admin.php?delpost=<?= $p['id'] ?>">Löschen</a></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Chat</h3>
    <p><a href="admin.php?clearchat=1">Chat leeren</a></p>

</div>

</body>
</html>
