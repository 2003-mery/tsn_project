<?php
session_start();
include 'config.php';

$userId = $_SESSION['user_id'];
$friendId = $_GET['id'];

/* Empêcher l’auto-ajout */
if ($userId != $friendId) {

    // Vérifier si la relation existe déjà
    $check = $conn->query("SELECT * FROM friends 
                           WHERE user_id=$userId AND friend_id=$friendId");

    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO friends (user_id, friend_id)
                      VALUES ($userId, $friendId)");
    }
}

header("Location: index.php?friend=added");

