<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
if (isset($_GET['friend']) && $_GET['friend'] == 'added') {
    echo "<p style='color:green;'>Ami ajouté avec succès</p>";
}
?>

<nav>
    Connecté : <?= $_SESSION['user_name'] ?> |
    <a href="add_user.php">Ajouter</a>
    <a href="profile.php">Mon profil</a>
    <a href="logout.php">Déconnexion</a>
    <a href="recommendation.php">Suggestions d'amis</a>

</nav>

<h2>Publier un message</h2>


<?php
$resultPosts = $conn->query("
    SELECT posts.content, posts.created_at, users.name
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
");

while ($post = $resultPosts->fetch_assoc()) {
    echo "<p>";
    echo "<strong>" . htmlspecialchars($post['name']) . "</strong><br>";
    echo htmlspecialchars($post['content']) . "<br>";
    echo "<small>" . $post['created_at'] . "</small>";
    echo "</p><hr>";
}
?>
<form method="POST" action="add_post.php">
    <textarea name="content" rows="3" cols="40" required></textarea><br>
    <button type="submit">Publier</button>
</form>

<hr>

<h2>Utilisateurs</h2>

<?php
$result = $conn->query("SELECT id, name, email, bio FROM users");

echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>";
    echo "<strong>{$row['name']}</strong><br>";
    echo "{$row['email']}<br>";
    echo "{$row['bio']}<br>";
    echo "<a href='edit_user.php?id={$row['id']}'>Modifier</a> | ";
    echo "<a href='delete_user.php?id={$row['id']}' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>";
    echo "<br><a href='add_friend.php?id={$row['id']}'>Ajouter comme ami</a>";
    echo "</li>";
}
echo "</ul>";
?>

</body>
</html>
