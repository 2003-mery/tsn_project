<?php
$user_id = $_SESSION['user_id'] ?? null;

$unread = 0;
if ($user_id) {
    $res = $conn->query("
        SELECT COUNT(*) AS total 
        FROM messages 
        WHERE receiver_id = $user_id AND seen = 0
    ");
    $row = $res->fetch_assoc();
    $unread = $row['total'];
}

?>

<style>
.header {
    background: #1877f2;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header .logo {
    color: white;
    font-size: 20px;
    font-weight: bold;
    text-decoration: none;
}

.nav {
    display: flex;
    gap: 20px;
    align-items: center;
}

.nav a {
    color: white;
    text-decoration: none;
    font-size: 15px;
    position: relative;
}

.nav a:hover {
    text-decoration: underline;
}

.badge {
    background: red;
    color: white;
    border-radius: 50%;
    padding: 2px 7px;
    font-size: 12px;
    position: absolute;
    top: -6px;
    right: -12px;
}
</style>
<?php
$notifCount = $conn->query("
    SELECT COUNT(*) AS total
    FROM notifications
    WHERE user_id = {$_SESSION['user_id']} AND is_read = 0
")->fetch_assoc()['total'];
?>

<div class="header">
    <a href="index.php" class="logo">Talky</a>

    <div class="nav">
        <a href="index.php">Home</a>

        <a href="messages.php">
            Messages
            <?php if ($unread > 0): ?>
                <span class="badge"><?= $unread ?></span>
            <?php endif; ?>
        </a>
        <a href="notifications.php">
            Notifications
            <?php if ($notifCount > 0): ?>
                <span style="
                    background:red;
                    color:white;
                    padding:2px 6px;
                    border-radius:50%;
                    font-size:12px;
                ">
                    <?= $notifCount ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="profile.php">Profile</a>
        <a href="recommendation.php">Suggestions</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
