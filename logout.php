<?php
session_start();

// tüm session verilerini sil
$_SESSION = [];

// session'ı tamamen yok et
session_destroy();

// login sayfasına yönlendir
header("Location: login.html");
exit;