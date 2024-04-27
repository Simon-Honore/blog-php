<?php

use App\Model\Post;

$pdo = new PDO($_ENV['DATABASE_URL'], $_ENV['DATABASE_USERNAME'], $_ENV['DATABASE_PASSWORD'], [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT 12");
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