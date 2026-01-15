<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #f0f2f5;
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-box {
    background: #fff;
    padding: 30px;
    width: 100%;
    max-width: 360px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    text-align: center;
}

.login-box h2 {
    margin-bottom: 20px;
}

.login-box input {
    width: 100%;
    padding: 12px;
    margin-bottom: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.login-box input[type="submit"] {
    background: #1877f2;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.login-box input[type="submit"]:hover {
    background: #0f5ec7;
}

.error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

.links {
    margin-top: 15px;
    font-size: 14px;
}

.links a {
    display: block;
    margin-top: 8px;
    color: #ffffff;
    text-decoration: none;
}

.links a:hover {
    text-decoration: underline;
}

.register-btn {
    margin-top: 15px;
    padding: 10px;
    display: block;
    background: #42b72a;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.register-btn:hover {
    background: #36a420;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>Connexion</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="Connexion">
    </form>

    <div class="links">
        <a href="/tsn_project/add_user.php" class="register-btn">Créer un compte</a>
    </div>
</div>

</body>
</html>
