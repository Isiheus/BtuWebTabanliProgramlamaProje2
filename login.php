<?php
session_start();
require 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {

    // 🔑 kullanıcıyı oturuma kaydet
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // 🚀 ana sayfaya yönlendir
    header("Location: index.php");
    exit;

} else {
    echo "E-posta veya şifre hatalı";
}