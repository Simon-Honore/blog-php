<?php

use App\Connection;
use App\Model\Post;
use App\URL;

$pdo = Connection::getPDO();

$current_page = URL::getIntPositive('page', 1);

$count_posts = (int)$pdo->query("SELECT COUNT(id) count FROM post")->fetch()['count'];
$posts_per_page = 12;
$count_pages = ceil($count_posts / $posts_per_page);
if ($current_page > $count_pages) {
  throw new Exception('Cette page n\'existe pas');
}
$offset = $posts_per_page * ($current_page - 1);
$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $posts_per_page OFFSET $offset");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

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
  <?php if ($current_page > 1) : ?>
    <?php
    $link = $router->url('home');
    if ($current_page > 2) {
      $link .= "?page=" . ($current_page - 1);
    }
    ?>
    <a href="<?= $link ?>" class="btn btn-primary">&laquo; Page précédente</a>
  <?php endif ?>
  <?php if ($current_page < $count_pages) : ?>
    <a href="<?= $router->url('home') ?>?page=<?= $current_page + 1 ?>" class="btn btn-primary ms-auto">Page suivante &raquo;</a>
  <?php endif ?>
</div>