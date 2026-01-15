<?php
session_start();
include "config.php";
include "header.php";


$name = "Admin";
$email = "admin@admin.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$bio = "Compte administrateur";

$conn->query("INSERT INTO users (name, email, password, bio)
VALUES ('$name', '$email', '$password', '$bio')");

echo "Compte admin créé"
