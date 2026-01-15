<?php
session_start();
include "config.php";
include "header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    u.id,
    u.name,
    COUNT(CASE WHEN m.seen = 0 THEN 1 END) AS unread
FROM messages m
JOIN users u ON u.id = m.sender_id
WHERE m.receiver_id = $user_id
GROUP BY u.id, u.name
ORDER BY MAX(m.created_at) DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Messages</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #f0f2f5;
    margin: 0;
}

.container {
    max-width: 700px;
    margin: 30px auto;
    padding: 15px;
}

.title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 20px;
}

.chat {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

.chat a {
    color: #000;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
}

.chat a:hover {
    text-decoration: underline;
}

.badge {
    background: #e41e3f;
    color: white;
    border-radius: 50%;
    padding: 4px 9px;
    font-size: 12px;
}
</style>
</head>

<body>

<div class="container">

<div class="title">Messages</div>

<?php if ($result->num_rows == 0): ?>
    <p>No message</p>
<?php endif; ?>

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="chat">
        <a href="chat.php?user=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['name']) ?>
        </a>

        <?php if ($row['unread'] > 0): ?>
            <span class="badge"><?= $row['unread'] ?></span>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

</div>

</body>
</html>
