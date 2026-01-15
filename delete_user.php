<?php
session_start();
include 'config.php';
include "header.php";

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id=$id");

header("Location: index.php");
