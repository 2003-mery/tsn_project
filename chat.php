<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sender_id   = $_SESSION['user_id'];
$receiver_id = $_GET['user'] ?? null;

if (!$receiver_id) {
    echo "Utilisateur introuvable";
    exit;
}

$user = $conn->query(
    "SELECT name FROM users WHERE id = $receiver_id"
)->fetch_assoc();

$conn->query("
    UPDATE messages 
    SET seen = 1 
    WHERE sender_id = $receiver_id 
      AND receiver_id = $sender_id
");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['content'])) {
    $content = $_POST['content'];

    $stmt = $conn->prepare("
        INSERT INTO messages (sender_id, receiver_id, content, seen)
        VALUES (?, ?, ?, 0)
    ");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $content);
    $stmt->execute();
    exit;
}

if (isset($_GET['load'])) {
    $stmt = $conn->prepare("
        SELECT sender_id, content 
        FROM messages
        WHERE (sender_id=? AND receiver_id=?)
           OR (sender_id=? AND receiver_id=?)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param(
        "iiii",
        $sender_id, $receiver_id,
        $receiver_id, $sender_id
    );
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $class = ($row['sender_id'] == $sender_id) ? "me" : "other";
        echo "<div class='$class'>".htmlspecialchars($row['content'])."</div>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Discussion</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    margin: 0;
    background: #f0f2f5;
}

.chat-container {
    max-width: 700px;
    margin: 20px auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    height: 90vh;
}

.chat-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: bold;
}

.back {
    text-decoration: none;
    color: #1877f2;
    font-size: 14px;
}

#chat-box {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f9f9f9;
}

.me {
    background: #dcf8c6;
    padding: 8px 12px;
    border-radius: 15px;
    margin: 6px 0;
    max-width: 70%;
    margin-left: auto;
    font-size: 14px;
}

.other {
    background: #e4e6eb;
    padding: 8px 12px;
    border-radius: 15px;
    margin: 6px 0;
    max-width: 70%;
    font-size: 14px;
}

.input-box {
    display: flex;
    gap: 8px;
    padding: 12px;
    border-top: 1px solid #eee;
}

.input-box input {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    outline: none;
}

.input-box button {
    padding: 10px 16px;
    border: none;
    border-radius: 20px;
    background: #1877f2;
    color: white;
    cursor: pointer;
}

.input-box button:hover {
    background: #0f5ec7;
}
</style>
</head>

<body>

<div class="chat-container">

    <div class="chat-header">
        <a href="javascript:history.back()" class="back">← </a>
        <span><?= htmlspecialchars($user['name']) ?></span>
    </div>

    <div id="chat-box"></div>

    <div class="input-box">
        <input type="text" id="message" placeholder="Écrire un message...">
        <button onclick="sendMessage()">Envoyer</button>
    </div>

</div>

<script>
function loadMessages() {
    fetch("chat.php?user=<?= $receiver_id ?>&load=1")
        .then(res => res.text())
        .then(data => {
            const box = document.getElementById("chat-box");
            box.innerHTML = data;
            box.scrollTop = box.scrollHeight;
        });
}

function sendMessage() {
    const msg = document.getElementById("message").value;
    if (msg === "") return;

    fetch("chat.php?user=<?= $receiver_id ?>", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: "content=" + encodeURIComponent(msg)
    });

    document.getElementById("message").value = "";
    loadMessages();
}

setInterval(loadMessages, 1500);
loadMessages();
</script>

</body>
</html>
