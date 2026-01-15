<?php
session_start();
include "config.php";
include "header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/*
  On prend les utilisateurs
  qui ne sont PAS :
  - toi
  - déjà tes amis
*/
$sql = "
SELECT u.id, u.name
FROM users u
WHERE u.id != $user_id
AND u.id NOT IN (
    SELECT friend_id FROM friends WHERE user_id = $user_id
)
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Suggestions d'amis</title>

<style>
.container {
    padding:20px;
}

.grid {
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap:20px;
}

.card {
    background:#fff;
    border:1px solid #ccc;
    border-radius:8px;
    padding:15px;
    text-align:center;
}

.avatar {
    width:80px;
    height:80px;
    border-radius:50%;
    background:#ddd;
    margin:0 auto 10px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    color:#555;
}

.card h3 {
    margin:10px 0;
}

.card a {
    display:block;
    margin:5px 0;
    padding:8px;
    text-decoration:none;
    border-radius:5px;
    font-weight:bold;
}

.add {
    background:#1877f2;
    color:white;
}

.msg {
    background:#e4e6eb;
    color:black;
}
</style>
</head>

<body>

<div class="container">
    <h2>Suggestions d'amis</h2>

    <div class="grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="avatar">
                    <?= strtoupper($row['name'][0]) ?>
                </div>

                <h3><?= htmlspecialchars($row['name']) ?></h3>

                <a class="add" href="add_friend.php?id=<?= $row['id'] ?>">
                    Ajouter ami
                </a>

            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
