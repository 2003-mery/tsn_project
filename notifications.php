<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$conn->query("
    UPDATE notifications 
    SET is_read = 1 
    WHERE user_id = $user_id
");

include "header.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Notifications</title>

<style>
body {
    background: #f0f2f5;
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
}

.container {
    max-width: 700px;
    margin: 40px auto;
    padding: 20px;
}

.card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.notification {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}

.notification:last-child {
    border-bottom: none;
}

.notification small {
    color: #777;
    font-size: 12px;
}
</style>
</head>

<body>

<div class="container">
    <div class="card">
        <h2>Notifications</h2>

        <?php
        $res = $conn->query("
            SELECT message, created_at 
            FROM notifications
            WHERE user_id = $user_id
            ORDER BY created_at DESC
        ");

        if ($res->num_rows == 0) {
            echo "<p>Aucune notification</p>";
        }

        while ($n = $res->fetch_assoc()) {
            echo "
                <div class='notification'>
                    🔔 {$n['message']}<br>
                    <small>{$n['created_at']}</small>
                </div>
            ";
        }
        ?>
    </div>
</div>

</body>
</html>
