<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$me = $_SESSION['user_id'];
$friend_id = (int) $_GET['id'];

$conn->query("
    UPDATE friends 
    SET status = 'accepted'
    WHERE user_id = $friend_id 
    AND friend_id = $me
");


$conn->query("
    INSERT INTO friends (user_id, friend_id, status)
    VALUES ($me, $friend_id, 'accepted')
");

$res = $conn->query("SELECT name FROM users WHERE id = $me");
$meName = $res->fetch_assoc()['name'];

$message = "$meName a accepté votre demande d'ami";

$stmt = $conn->prepare("
    INSERT INTO notifications (user_id, message)
    VALUES (?, ?)
");
$stmt->bind_param("is", $friend_id, $message);
$stmt->execute();

header("Location: profile.php");
exit;
