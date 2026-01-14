<?php
session_start();
include 'config.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $bio = $_POST["bio"];

    $conn->query("UPDATE users SET name='$name', email='$email', bio='$bio' WHERE id=$id");
    header("Location: index.php");
}

$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Modifier</h2>

<form method="post">
    <input name="name" value="<?= $user['name'] ?>">
    <input name="email" value="<?= $user['email'] ?>">
    <input name="bio" value="<?= $user['bio'] ?>">
    <input type="submit" value="Modifier">
</form>

</body>
</html>
