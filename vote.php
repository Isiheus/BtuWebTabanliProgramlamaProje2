<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Oy verebilmek için giriş yapmalısın!";
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_GET['post_id'];
$vote = $_GET['vote'];

// aynı kullanıcı aynı posta tekrar oy vermesin
$sql = "INSERT INTO votes (user_id, post_id, vote)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE vote = VALUES(vote)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $post_id, $vote]);

header("Location: index.php");
exit;