<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Etkinlik Düzenle</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<body class="has-background-dark">

<nav class="navbar is-dark">
  <div class="navbar-brand">
    <a class="navbar-item has-text-white" href="index.php">Ana Sayfa</a>
  </div>
</nav>

<section class="section has-background-dark">
  <div class="container" style="max-width: 500px;">

    <div class="box has-background-grey-darker">

      <h1 class="title has-text-white has-text-centered">
         Etkinlik Oluştur
      </h1>

      <form action="add_post.php" method="POST">

        <!-- Başlık -->
        <div class="field">
          <label class="label has-text-white">Etkinlik Başlığı</label>
          <div class="control">
            <input class="input is-dark" type="text" name="title" placeholder="Örn: Film Gecesi" required>
          </div>
        </div>

        <!-- Saat -->
        <div class="field">
          <label class="label has-text-white">Saat</label>
          <div class="control">
            <input class="input is-dark" type="text" name="event_time" placeholder="Örn: 18:00" required>
          </div>
        </div>

        <!-- Yer -->
        <div class="field">
          <label class="label has-text-white">Yer</label>
          <div class="control">
            <input class="input is-dark" type="text" name="event_location" placeholder="Örn: Bursa" required>
          </div>
        </div>

        <!-- Açıklama -->
        <div class="field">
          <label class="label has-text-white">Açıklama</label>
          <div class="control">
            <textarea class="textarea is-dark" name="description" placeholder="Etkinlik açıklaması..." required></textarea>
          </div>
        </div>

        <!-- Buton -->
        <div class="field">
          <button class="button is-primary is-fullwidth">
            Paylaş 
          </button>
        </div>

      </form>

    </div>

  </div>
</section>

</body>
</html>