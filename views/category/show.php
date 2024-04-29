<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;
use App\URL;

$pdo = Connection::getPDO();

$id = $params['id'];
$slug = $params['slug'];

$stmt = $pdo->prepare("SELECT * FROM category WHERE id = :id");

$stmt->execute(['id' => $id]);

$category = $stmt->fetchObject(Category::class);

if (!$category) {
  throw new Exception('Aucune catégorie ne correspond');
}

if ($category->getSlug() !== $slug) {
  $url = $router->url('post', ['slug' => $category->getSlug(), 'id' => $id]);
  http_response_code(301);
  header('Location: ' . $url);
  exit();
}

$title = "Catégorie {$category->getName()}";

$current_page = URL::getIntPositive('page', 1);

$count_posts = (int)$pdo
  ->query("SELECT COUNT(category_id) count FROM post_category WHERE category_id = " . $category->getId())
  ->fetch()['count'];

$posts_per_page = 12;
$count_pages = ceil($count_posts / $posts_per_page);

if ($current_page > $count_pages) {
  throw new Exception('Cette page n\'existe pas');
}

$offset = $posts_per_page * ($current_page - 1);

$query = $pdo->query("
  SELECT p.*
  FROM post p 
  JOIN post_category pc ON pc.post_id = p.id
  WHERE pc.category_id = {$category->getId()}
  ORDER BY created_at DESC 
  LIMIT $posts_per_page OFFSET $offset
  ");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

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
  <?php if ($current_page > 1) : ?>
    <?php
    $l = $link;
    if ($current_page > 2) {
      $l = $link . "?page=" . ($current_page - 1);
    }
    ?>
    <a href="<?= $l ?>" class="btn btn-primary">&laquo; Page précédente</a>
  <?php endif ?>
  <?php if ($current_page < $count_pages) : ?>
    <a href="<?= $link ?>?page=<?= $current_page + 1 ?>" class="btn btn-primary ms-auto">Page suivante &raquo;</a>
  <?php endif ?>
</div>