<?php
session_start();
require 'db.php';

$sql = "
SELECT 
    posts.*,
    users.username,

    SUM(CASE WHEN votes.vote = 1 THEN 1 ELSE 0 END) AS yes_votes,
    SUM(CASE WHEN votes.vote = 0 THEN 1 ELSE 0 END) AS no_votes

FROM posts
JOIN users ON posts.user_id = users.id
LEFT JOIN votes ON posts.id = votes.post_id

GROUP BY posts.id
ORDER BY posts.id DESC
";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Ana Sayfa</title>

  <!-- Bulma CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<body class="has-background-dark">
<?php if (isset($_SESSION['error'])): ?>
  <div class="notification is-danger is-light">
    <?= $_SESSION['error'] ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<!-- NAVBAR -->
<nav class="navbar is-dark" role="navigation">

  <div class="navbar-brand">
    <a class="navbar-item has-text-white" href="index.php">
      Ana Sayfa
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="menu">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="menu" class="navbar-menu">

    <div class="navbar-end">

      <a class="navbar-item" href="etkinlik.php">
        Etkinlik Düzenle
      </a>

      <?php if (!isset($_SESSION['user_id'])): ?>

        <!-- Giriş yapmamış kullanıcı -->
        <a class="navbar-item" href="login.html">
          Giriş Yap
        </a>

        <a class="navbar-item" href="register.html">
          Kayıt Ol
        </a>

      <?php else: ?>

        <!-- Giriş yapmış kullanıcı -->
        <a class="navbar-item" href="#">
          <?= htmlspecialchars($_SESSION['username'] ?? 'Kullanıcı') ?>
        </a>

        <a class="navbar-item" href="logout.php">
          Hesaptan Çık
        </a>

      <?php endif; ?>

    </div>

  </div>
</nav>

<!-- CONTENT -->
<section class="section">
  <div class="container has-text-centered">

    <h1 class="title has-text-white">
      Ana Sayfa
    </h1>

    <p class="subtitle has-text-grey-light" style="max-width: 700px; margin: auto;">
      Bu bir örnek ana sayfa tasarımıdır. Bulma CSS ile modern, responsive ve temiz bir arayüz oluşturuldu.
    </p>

  </div>
</section>
<section class="section">
  <div class="container">

    <h1 class="title has-text-white has-text-centered">Etkinlikler</h1>
<?php foreach ($posts as $post): ?>

<div class="box post-card" style="position: relative;">

  <!-- USER + INFO -->
  <div style="margin-bottom:10px;">
      <?= htmlspecialchars($post['username']) ?> •
      <?= htmlspecialchars($post['event_time']) ?> •
      <?= htmlspecialchars($post['event_location']) ?>
  </div>

  <h2 class="title is-5"><?= htmlspecialchars($post['title']) ?></h2>

  <p><?= htmlspecialchars($post['description']) ?></p>

  <!-- OY BUTONLARI -->
  <div class="buttons mt-3">

  <div class="buttons mt-3">
    <a class="button is-success is-small"
       href="vote.php?post_id=<?= $post['id'] ?>&vote=1">
      👍 Katılıyorum
    </a>

    <a class="button is-danger is-small"
       href="vote.php?post_id=<?= $post['id'] ?>&vote=0">
      👎 Katılmıyorum
    </a>
  </div>

</div>
<div class="tags mt-2">
  <span class="tag is-success is-light">
    👍 <?= $post['yes_votes'] ?? 0 ?>
  </span>

  <span class="tag is-danger is-light">
    👎 <?= $post['no_votes'] ?? 0 ?>
  </span>
</div>
  <!-- SAĞ ALT BUTONLAR -->
  <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
  
    <div style="
        position:absolute;
        bottom:10px;
        right:10px;
        display:flex;
        gap:5px;
    ">
      
      <a href="edit_post.php?id=<?= $post['id'] ?>"
         class="button is-small is-info">
        Düzenle
      </a>

      <a href="delete_post.php?id=<?= $post['id'] ?>"
         class="button is-small is-danger"
         onclick="return confirm('Silmek istiyor musun?')">
        Sil
      </a>

    </div>

  <?php endif; ?>

</div>

<?php endforeach; ?>

  </div>
</section>
<!-- Navbar mobil açılır menü scripti -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const burgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  burgers.forEach(el => {
    el.addEventListener('click', () => {
      const target = el.dataset.target;
      const menu = document.getElementById(target);

      el.classList.toggle('is-active');
      menu.classList.toggle('is-active');
    });
  });
});
</script>

</body>
</html>