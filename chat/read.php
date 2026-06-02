<?php
require __DIR__ . '/../config/db.php';

$result = $mysqli->query("SELECT username, message, created_at FROM chat ORDER BY id ASC");

while ($row = $result->fetch_assoc()) {
    $user = htmlspecialchars($row["username"]);
    $msg  = nl2br(htmlspecialchars($row["message"]));
    $time = $row["created_at"];

    // Admin rot
    $color = ($user === "admin") ? "#ff0000" : "#" . substr(md5($user), 0, 6);

    echo "<span style='color:$color;'>[$time] $user</span>: $msg<br>";
}
?>
