<?php
session_start();
include "config.php";
include "header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $bio = $_POST["bio"];

    $conn->query("
        INSERT INTO users (name, email, password, bio)
        VALUES ('$name', '$email', '$password', '$bio')
    ");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Créer un compte</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #f0f2f5;
    margin: 0;
}

.register-container {
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.register-box {
    background: #fff;
    padding: 30px;
    width: 100%;
    max-width: 420px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.register-box h2 {
    text-align: center;
    margin-bottom: 20px;
}

.register-box input {
    width: 100%;
    padding: 12px;
    margin-bottom: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.register-box textarea {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    resize: none;
    min-height: 80px;
    margin-bottom: 12px;
}

.register-box input[type="submit"] {
    background: #42b72a;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.register-box input[type="submit"]:hover {
    background: #36a420;
}

.login-link {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
}

.login-link a {
    color: #1877f2;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="register-container">
    <div class="register-box">
        <h2>Créer un compte</h2>

        <form method="post">
            <input name="name" placeholder="Nom complet" required>
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Mot de passe" required>
            <textarea name="bio" placeholder="Bio (facultatif)"></textarea>
            <input type="submit" value="Créer le compte">
        </form>

        <div class="login-link">
            <a href="login.php">Déjà un compte ? Se connecter</a>
        </div>
    </div>
</div>

</body>
</html>
