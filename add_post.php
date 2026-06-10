<?php
require 'db.php';
session_start();

//  GİRİŞ KONTROLÜ
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Etkinlik paylaşmak için giriş yapmalısın!";
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

//  POST VERİLERİ (güvenli çekim)
$title = $_POST['title'] ?? '';
$desc = $_POST['description'] ?? '';
$time = $_POST['event_time'] ?? '';
$location = $_POST['event_location'] ?? '';

//  boş veri kontrolü
if ($title == '' || $desc == '' || $time == '' || $location == '') {
    $_SESSION['error'] = "Tüm alanları doldurmalısın!";
    header("Location: etkinlik.php");
    exit;
}

//  INSERT
$sql = "INSERT INTO posts 
(user_id, title, description, event_time, event_location)
VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $title, $desc, $time, $location]);

//  geri yönlendirme
$_SESSION['success'] = "Etkinlik başarıyla paylaşıldı!";
header("Location: index.php");
exit;
