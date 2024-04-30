<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;
use App\PaginatedQuery;

$paginatedQuery = new PaginatedQuery(
  "SELECT * FROM post ORDER BY created_at DESC",
  "SELECT COUNT(id) FROM post"
);

$posts = $paginatedQuery->getItems(Post::class);

$postsById = [];
foreach ($posts as $post) {
  $postsById[$post->getId()] = $post;
}
$pdo = Connection::getPDO();
$query = $pdo->query("
  SELECT c.*, pc.post_id
  FROM post_category pc
  JOIN category c ON c.id = pc.category_id
  WHERE pc.post_id IN (" . implode(', ', array_keys($postsById)) . ")
");
$categories = $query->fetchAll(PDO::FETCH_CLASS, Category::class);

foreach ($categories as $category) {
  $postsById[$category->getPostId()]->addCategory($category);
}

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