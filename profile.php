<?php
session_start();
include "config.php";
include "header.php";

$id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon profil</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #f0f2f5;
    margin: 0;
    padding: 0;
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
    margin-bottom: 25px;
}

h2, h3 {
    margin-top: 0;
}

.profile-info p {
    margin: 8px 0;
    font-size: 15px;
}

.friend-request, .friend {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.friend-request:last-child,
.friend:last-child {
    border-bottom: none;
}

a {
    text-decoration: none;
    color: #1877f2;
    font-size: 14px;
}

a:hover {
    text-decoration: underline;
}

.btn {
    padding: 6px 12px;
    background: #1877f2;
    color: white;
    border-radius: 6px;
    font-size: 13px;
}

.btn:hover {
    background: #0f5ec7;
}
</style>
</head>

<body>

<div class="container">

<div class="card">
    <h2>Mon profil</h2>

    <img src="uploads/profile_pics/<?= htmlspecialchars($user['profile_pic']) ?>"
         alt="Photo de profil"
         style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:10px;">

    <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_pic" accept="image/*" required>
        <br><br>
        <button class="btn" type="submit">Changer la photo</button>
    </form>

    <div class="profile-info">
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Bio :</strong> <?= htmlspecialchars($user['bio']) ?></p>
    </div>
</div>


<div class="card">
    <h3>Demandes d'amis</h3>

    <?php
    $res = $conn->query("
        SELECT u.id, u.name 
        FROM friends f
        JOIN users u ON f.user_id = u.id
        WHERE f.friend_id = {$_SESSION['user_id']}
        AND f.status = 'pending'
    ");

    if ($res->num_rows == 0) {
        echo "<p>Aucune demande en attente</p>";
    }

    while ($row = $res->fetch_assoc()) {
        echo "
            <div class='friend-request'>
                <span>{$row['name']}</span>
                <a class='btn' href='accept_friend.php?id={$row['id']}'>Accepter</a>
            </div>
        ";
    }
    ?>
</div>

<div class="card">
    <h3>Mes amis</h3>

    <?php
    $user_id = $_SESSION['user_id'];

    $res = $conn->query("
        SELECT u.id, u.name
        FROM friends f
        JOIN users u ON f.friend_id = u.id
        WHERE f.user_id = $user_id
        AND f.status = 'accepted'
    ");

    if ($res->num_rows == 0) {
        echo "<p>Aucun ami pour le moment</p>";
    }

    while ($row = $res->fetch_assoc()) {
        echo "
            <div class='friend'>
                <span>{$row['name']}</span>
                <a class='btn' href='chat.php?user={$row['id']}'>Message</a>
            </div>
        ";
    }
    ?>
</div>

</div>


</body>
</html>
