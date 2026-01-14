<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows > 0) {
        $conn->query("UPDATE users SET password='$newPassword' WHERE email='$email'");
        $message = "Mot de passe réinitialisé avec succès";
    } else {
        $message = "Email introuvable";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Mot de passe oublié</h2>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

<form method="post">
    <input type="email" name="email" placeholder="Votre email" required>
    <input type="password" name="password" placeholder="Nouveau mot de passe" required>
    <input type="submit" value="Réinitialiser">
</form>

<a href="login.php">Retour à la connexion</a>

</body>
</html>
