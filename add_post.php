<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$content = trim($_POST['content']);
$userId = $_SESSION['user_id'];

if (!empty($content)) {
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $content);
    $stmt->execute();
}

header("Location: index.php");
