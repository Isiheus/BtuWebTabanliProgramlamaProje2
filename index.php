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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

<style>
.post-card {
  position: relative;
}

.post-actions {
  position: absolute;
  bottom: 12px;
  right: 12px;
  display: flex;
  gap: 6px;
}
</style>

</head>

<body class="has-background-dark">

<?php if (isset($_SESSION['error'])): ?>
  <div class="notification is-danger is-light">
    <?= $_SESSION['error'] ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- NAVBAR -->
<nav class="navbar is-dark">
  <div class="navbar-brand">
    <a class="navbar-item" href="index.php">Ana Sayfa</a>

    <a role="button" class="navbar-burger" data-target="menu">
      <span></span><span></span><span></span>
    </a>
  </div>

  <div id="menu" class="navbar-menu">
    <div class="navbar-end">

      <a class="navbar-item" href="etkinlik.php">Etkinlik Düzenle</a>

      <?php if (!isset($_SESSION['user_id'])): ?>
        <a class="navbar-item" href="login.html">Giriş Yap</a>
        <a class="navbar-item" href="register.html">Kayıt Ol</a>
      <?php else: ?>
        <span class="navbar-item">
          <?= htmlspecialchars($_SESSION['username'] ?? 'Kullanıcı') ?>
        </span>
        <a class="navbar-item" href="logout.php">Çıkış</a>
      <?php endif; ?>

    </div>
  </div>
</nav>


<!-- POSTS -->
<section class="section">
  <div class="container">

    <h1 class="title has-text-white has-text-centered">Etkinlikler</h1>

    <?php foreach ($posts as $post): ?>

    <div class="box post-card">

      <!-- INFO -->
      <div class="mb-2 has-text-grey">
        <?= htmlspecialchars($post['username']) ?> •
        <?= htmlspecialchars($post['event_time']) ?> •
        <?= htmlspecialchars($post['event_location']) ?>
      </div>

      <h2 class="title is-5"><?= htmlspecialchars($post['title']) ?></h2>

      <p><?= htmlspecialchars($post['description']) ?></p>

      <!-- VOTES -->
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

      <div class="tags mt-2">
        <span class="tag is-success is-light">
          👍 <?= $post['yes_votes'] ?? 0 ?>
        </span>

        <span class="tag is-danger is-light">
          👎 <?= $post['no_votes'] ?? 0 ?>
        </span>
      </div>

      <!-- OWNER ACTIONS -->
      <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
        <div class="post-actions">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
  const burgers = document.querySelectorAll('.navbar-burger');

  burgers.forEach(el => {
    el.addEventListener('click', () => {
      const target = document.getElementById(el.dataset.target);
      el.classList.toggle('is-active');
      target.classList.toggle('is-active');
    });
  });
});
</script>

</body>
</html>