<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['profile_pic'])) {

    $file = $_FILES['profile_pic'];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("Format d'image non autorisé");
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        die("Image trop lourde (max 2MB)");
    }

    $filename = uniqid() . "." . $ext;
    $destination = "uploads/profile_pics/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {

        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $user_id);
        $stmt->execute();

        header("Location: profile.php");
        exit;
    } else {
        echo "Erreur lors de l'upload";
    }
}
