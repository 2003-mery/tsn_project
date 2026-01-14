<?php
session_start();
include 'config.php';

$userId = $_SESSION['user_id'];

/* 1. Amis directs */
$directFriends = [];
$res = $conn->query("SELECT friend_id FROM friends WHERE user_id = $userId");
while ($row = $res->fetch_assoc()) {
    $directFriends[] = $row['friend_id'];
}

/* 2. Amis des amis */
$recommendations = [];

foreach ($directFriends as $friend) {
    $res2 = $conn->query("SELECT friend_id FROM friends WHERE user_id = $friend");
    while ($row2 = $res2->fetch_assoc()) {
        $foaf = $row2['friend_id'];

        if ($foaf != $userId && !in_array($foaf, $directFriends)) {
            $recommendations[] = $foaf;
        }
    }
}

$recommendations = array_unique($recommendations);

/* 3. Affichage */
echo "<h2>Suggestions d'amis</h2>";

foreach ($recommendations as $id) {
    $u = $conn->query("SELECT name FROM users WHERE id=$id")->fetch_assoc();
    echo $u['name'] . "<br>";
}
