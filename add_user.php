<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $bio = $_POST["bio"];

    $conn->query("INSERT INTO users (name, email, password, bio)
                  VALUES ('$name', '$email', '$password', '$bio')");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Ajouter un utilisateur</h2>

<form method="post">
    <input name="name" placeholder="Nom" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Mot de passe" required>
    <input name="bio" placeholder="Bio">
    <input type="submit" value="Ajouter">
</form>

</body>
</html>
