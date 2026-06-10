<?php
require 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post bulunamadı");
}
?>

<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Etkinlik Düzenle</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<body class="has-background-dark">

<section class="section has-background-dark">
  <div class="container" style="max-width: 500px;">

    <div class="box has-background-grey-darker">

      <h1 class="title has-text-white has-text-centered">
        Etkinlik Düzenle
      </h1>

      <form action="update_post.php" method="POST">

        <input type="hidden" name="id" value="<?= $post['id'] ?>">

        <!-- BAŞLIK -->
        <div class="field">
          <label class="label has-text-white">Başlık</label>
          <input class="input is-dark" type="text"
                 name="title"
                 value="<?= htmlspecialchars($post['title']) ?>"
                 required>
        </div>

        <!-- SAAT -->
        <div class="field">
          <label class="label has-text-white">Saat</label>
          <input class="input is-dark" type="text"
                 name="event_time"
                 value="<?= htmlspecialchars($post['event_time']) ?>"
                 required>
        </div>

        <!-- YER -->
        <div class="field">
          <label class="label has-text-white">Yer</label>
          <input class="input is-dark" type="text"
                 name="event_location"
                 value="<?= htmlspecialchars($post['event_location']) ?>"
                 required>
        </div>

        <!-- AÇIKLAMA -->
        <div class="field">
          <label class="label has-text-white">Açıklama</label>
          <textarea class="textarea is-dark"
                    name="description"
                    required><?= htmlspecialchars($post['description']) ?></textarea>
        </div>

        <!-- BUTON -->
        <button class="button is-primary is-fullwidth">
          Güncelle
        </button>

      </form>

    </div>

  </div>
</section>

</body>
</html>