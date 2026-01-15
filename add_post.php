<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$content = trim($_POST['content']);
$imageName = null;

if (!empty($_FILES['image']['name'])) {

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("Format d'image non autorisé");
    }

    if ($_FILES['image']['size'] > 3 * 1024 * 1024) {
        die("Image trop lourde (max 3MB)");
    }

    $imageName = uniqid() . '.' . $ext;
    $destination = 'uploads/posts/' . $imageName;

    move_uploaded_file($_FILES['image']['tmp_name'], $destination);
}

$stmt = $conn->prepare("
    INSERT INTO posts (user_id, content, image, created_at)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("iss", $user_id, $content, $imageName);
$stmt->execute();

header("Location: index.php");
exit;
