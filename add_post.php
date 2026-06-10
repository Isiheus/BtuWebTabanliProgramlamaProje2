<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];

$title = $_POST['title'];
$desc = $_POST['description'];
$time = $_POST['event_time'];
$location = $_POST['event_location'];

$sql = "INSERT INTO posts 
(user_id, title, description, event_time, event_location)
VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $title, $desc, $time, $location]);

header("Location: index.php");
exit;