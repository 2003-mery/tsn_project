<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "header.php";

$search = $_GET['search'] ?? '';
$searchSafe = $conn->real_escape_string($search);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Publications</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #f0f2f5;
    margin: 0;
}

.container {
    width: 100%;
    max-width: 700px;
    margin: 40px auto;
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.alert {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
}

.alert.success {
    background: #d4edda;
    color: #155724;
}

.search-box {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.search-box input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}


.posts {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.post {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

.post-header {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}

.post p {
    font-size: 16px;
    margin: 0;
}

.new-post {
    background: #fff;
    margin-top: 30px;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

textarea {
    width: 100%;
    min-height: 80px;
    padding: 10px;
    resize: none;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
}

button {
    margin-top: 10px;
    padding: 10px 20px;
    border: none;
    background: #1877f2;
    color: white;
    font-size: 15px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #0f5ec7;
}
</style>
</head>

<body>

<div class="container">

<?php
if (isset($_GET['friend']) && $_GET['friend'] == 'added') {
    echo "<div class='alert success'>Ami ajouté avec succès</div>";
}
?>

<h2>Vos publications</h2>

<div class="search-box">
    <form method="GET">
        <input 
            type="text" 
            name="search" 
            placeholder="Rechercher par nom..." 
            value="<?= htmlspecialchars($search) ?>">
    </form>
</div>

<div class="posts">
<?php
$sql = "
    SELECT posts.content, posts.created_at, posts.image, users.name
    FROM posts
    JOIN users ON posts.user_id = users.id
";

if (!empty($searchSafe)) {
    $sql .= " WHERE users.name LIKE '%$searchSafe%'";
}

$sql .= " ORDER BY posts.created_at DESC";

$resultPosts = $conn->query($sql);

if ($resultPosts->num_rows == 0) {
    echo "<p>Aucune publication trouvée</p>";
}

while ($post = $resultPosts->fetch_assoc()) {

    echo "<div class='post'>";

    echo "<div class='post-header'>";
    echo "<strong>" . htmlspecialchars($post['name']) . "</strong>";
    echo "<span>" . $post['created_at'] . "</span>";
    echo "</div>";

    echo "<p>" . htmlspecialchars($post['content']) . "</p>";

    if (!empty($post['image'])) {
        echo "
            <img src='uploads/posts/{$post['image']}'
                 style='width:100%;max-height:400px;
                 object-fit:cover;border-radius:8px;margin-top:10px;'>
        ";
    }

    echo "</div>";
}
?>
</div>

<div class="new-post">
    <h3>Nouvelle publication</h3>

    <form method="POST" action="add_post.php" enctype="multipart/form-data">
        <textarea name="content" placeholder="Quoi de neuf ?" required></textarea>

        <input type="file" name="image" accept="image/*">

        <button type="submit">Publier</button>
    </form>
</div>

</div>

</body>
</html>
