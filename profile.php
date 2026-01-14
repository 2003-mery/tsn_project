<?php
session_start();
include 'config.php';

$id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>

<h2>Mon profil</h2>
<p>Nom : <?= $user['name'] ?></p>
<p>Email : <?= $user['email'] ?></p>
<p>Bio : <?= $user['bio'] ?></p>
