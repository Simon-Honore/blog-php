<?php

use App\Model\Post;
use App\PaginatedQuery;

$paginatedQuery = new PaginatedQuery(
  "SELECT * FROM post ORDER BY created_at DESC",
  "SELECT COUNT(id) FROM post"
);

$posts = $paginatedQuery->getItems(Post::class);

$link = $router->url('home');
?>

<h1>Mon Blog</h1>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 gy-2">
  <?php foreach ($posts as $post) : ?>
    <div class="col">
      <?php require 'card.php' ?>
    </div>
  <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
  <?= $paginatedQuery->previousPagLink($link) ?>
  <?= $paginatedQuery->nextPagLink($link) ?>
</div>