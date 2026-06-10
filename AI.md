-sql ile bir websitesi için nasıl şifre veri tabanı oluştururum

-Bir web sitesi için SQL ile kullanıcı şifrelerini saklayacaksan, **şifreleri düz metin (plain text) olarak veritabanına kaydetmemelisin**. Bunun yerine güvenli bir **şifre hash'i** saklamalısın.

Örnek kullanıcı tablosu:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Kayıt sırasında:

1. Kullanıcı şifreyi girer.
2. Uygulama tarafında (PHP, Python, Node.js vb.) şifreyi **bcrypt**, **Argon2** veya **scrypt** ile hash'lersin.
3. Oluşan hash'i `password_hash` alanına kaydedersin.

Örnek bir kayıt:

```sql
INSERT INTO users (username, email, password_hash)
VALUES (
    'ahmet',
    'ahmet@example.com',
    '$2b$12$u2PqjJ3yK9...'
);
```

Giriş yaparken:

1. Kullanıcının e-posta veya kullanıcı adını bulursun.
2. Veritabanındaki `password_hash` değerini alırsın.
3. Girilen şifreyi aynı algoritmanın doğrulama fonksiyonuyla kontrol edersin.

Örneğin PHP'de:

```php
$hash = password_hash($password, PASSWORD_BCRYPT);

// Doğrulama
if (password_verify($password, $hash)) {
    echo "Giriş başarılı";
}
```

Önemli güvenlik kuralları:

* ❌ Şifreleri düz metin saklama.
* ❌ MD5 veya SHA1 kullanma.
* ✅ Argon2 veya bcrypt kullan.
* ✅ HTTPS kullan.
* ✅ Güçlü şifre politikaları uygula.
* ✅ Giriş denemelerine hız sınırlaması ekle.

Hangi teknolojiyi kullanıyorsun (PHP, ASP.NET, Node.js, Python/Django, Laravel vb.)? Ona göre tam örnek verebilirim.

-Etkinlik düzenleye tıkladığımda index.php de gözükecek bir post nasıl oluştururum ve veritabanına kaydederim.

-Bunu aslında “etkinlik oluştur → veritabanına kaydet → index.php’de listele” akışıyla yaparsın.

---

# 🧱 1. VERİTABANI (MySQL)

phpMyAdmin veya MySQL’de çalıştır:

```sql id="db1"
CREATE DATABASE etkinlik_sistemi;

USE etkinlik_sistemi;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

# 🔌 2. VERİTABANI BAĞLANTISI (db.php)

```php id="db2"
<?php

$host = "localhost";
$dbname = "etkinlik_sistemi";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
```

---

# ➕ 3. POST EKLEME (add_post.php)

```php id="php1"
<?php

require 'db.php';

$title = $_POST['title'];
$desc = $_POST['description'];

$sql = "INSERT INTO posts (title, description) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $desc]);

header("Location: index.php");
exit;
```

---

# 📥 4. POSTLARI ÇEKME (index.php)

```php id="php2"
<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Ana Sayfa</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<body class="has-background-dark">

<section class="section">
  <div class="container">

    <h1 class="title has-text-white has-text-centered">Etkinlikler</h1>

    <!-- FORM -->
    <div class="box">
      <form action="add_post.php" method="post">

        <div class="field">
          <input class="input" type="text" name="title" placeholder="Başlık" required>
        </div>

        <div class="field">
          <textarea class="textarea" name="description" placeholder="Açıklama" required></textarea>
        </div>

        <button class="button is-primary">Paylaş</button>
      </form>
    </div>

    <!-- POSTLAR -->
    <?php foreach ($posts as $post): ?>
      <div class="box has-background-grey-darker has-text-white">
        <h2 class="title is-5 has-text-white">
          <?= htmlspecialchars($post['title']) ?>
        </h2>
        <p><?= htmlspecialchars($post['description']) ?></p>
      </div>
    <?php endforeach; ?>

  </div>
</section>

</body>
</html>
```

---

# 📁 PROJE YAPISI

```
/proje
│
├── db.php
├── index.php
├── add_post.php
```

---

# 🧠 NASIL ÇALIŞIR?

### 1. Kullanıcı formu doldurur

→ `index.php`

### 2. PHP çalışır

→ `add_post.php`

### 3. Veritabanına kaydeder

### 4. Sayfa yenilenir

### 5. Yeni post görünür

---

# ⚠️ ÖNEMLİ

Bu sistem çalışması için:

* XAMPP / WAMP / Laragon kullanmalısın
* Apache + MySQL açık olmalı
* Dosyalar `htdocs` içine konmalı

---

# 🚀 İstersen bir üst seviye

Bunu geliştirebiliriz:

🔥 Login sistemi ekleme
🔥 Kullanıcı adıyla post atma
🔥 Post silme / düzenleme
🔥 Beğeni sistemi
🔥 Yorum sistemi
🔥 Instagram benzeri feed

İstersen sana bunu **tam mini sosyal medya uygulaması** haline getirebilirim.
