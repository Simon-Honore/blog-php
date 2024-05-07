<!DOCTYPE html>
<html lang="fr" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title><?= $title ?? 'Mon blog' ?></title>
</head>

<body class="d-flex flex-column h-100">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary p-2">
    <a href="<?= $router->url('home') ?>" class="navbar-brand">Mon site </a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="<?= $router->url('admin_posts') ?>" class="nav-link">Articles</a>
      </li>
      <li class="nav-item">
        <a href="<?= $router->url('admin_categories') ?>" class="nav-link">Catégories</a>
      </li>
    </ul>
  </nav>

  <div class="container mt-4">
    <?= $content ?>
  </div>

  <footer class="py-4 footer bg-light mt-auto">
    <div class="container">
      <?php if (defined('DEBUG_TIME')) : ?>
        Page générée en <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?>ms
      <?php endif ?>
    </div>
  </footer>

</body>

</html>