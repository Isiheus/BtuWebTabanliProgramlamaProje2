<?php
require 'db.php';

$id = $_POST['id'];
$title = $_POST['title'];
$time = $_POST['event_time'];
$location = $_POST['event_location'];
$desc = $_POST['description'];

$sql = "
UPDATE posts
SET 
    title = ?,
    event_time = ?,
    event_location = ?,
    description = ?
WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $time, $location, $desc, $id]);

header("Location: index.php");
exit;