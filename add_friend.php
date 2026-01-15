<?php
session_start();
include "config.php";

$user_id = $_SESSION['user_id'];
$friend_id = $_GET['id'];

/* éviter doublon */
$check = $conn->query("
    SELECT * FROM friends 
    WHERE user_id=$user_id AND friend_id=$friend_id
");

if ($check->num_rows == 0) {
    $conn->query("
        INSERT INTO friends (user_id, friend_id, status)
        VALUES ($user_id, $friend_id, 'pending')
    ");
}

header("Location: recommendation.php");
exit;
