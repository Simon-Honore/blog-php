<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$category = (new CategoryTable($pdo))->find($id);

if ($category->getSlug() !== $slug) {
  $url = $router->url('post', ['slug' => $category->getSlug(), 'id' => $id]);
  http_response_code(301);
  header('Location: ' . $url);
  exit();
}

$title = "Catégorie {$category->getName()}";

[$posts, $pagination] = (new PostTable($pdo))->findPaginatedByCategory($id);

$link = $router->url('category', ['slug' => $category->getSlug(), 'id' => $category->getId()]);

?>

<h1><?= $title ?></h1>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 gy-2">
  <?php foreach ($posts as $post) : ?>
    <div class="col">
      <?php require dirname(__DIR__) .  '/post/card.php' ?>
    </div>
  <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
  <?= $pagination->previousPagLink($link) ?>
  <?= $pagination->nextPagLink($link) ?>
</div>