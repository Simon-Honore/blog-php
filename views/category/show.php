<?php

use App\{Connection, PaginatedQuery};
use App\Model\Category;
use App\Model\Post;

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

$query = "
  SELECT p.* 
  FROM post p 
  JOIN post_category pc ON pc.post_id = p.id 
  WHERE pc.category_id = {$category->getId()} 
  ORDER BY created_at DESC";

$queryCount = "
  SELECT COUNT(category_id) 
  FROM post_category WHERE category_id = {$category->getId()}";

$paginatedQuery = new PaginatedQuery($query, $queryCount);

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
  <?= $paginatedQuery->previousPagLink($link) ?>
  <?= $paginatedQuery->nextPagLink($link) ?>
</div>