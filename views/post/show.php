<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$pdo = Connection::getPDO();

$id = $params['id'];
$slug = $params['slug'];

$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if (!$post) {
  throw new Exception('Aucun article ne correspond');
}

if ($post->getSlug() !== $slug) {
  $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
  http_response_code(301);
  header('Location: ' . $url);
  exit();
}

?>

<h1><?= e($post->getName()) ?></h1>

<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>

<?php foreach ($post->getCategories() as $k => $category) : ?>
  <?= $k > 0 ? ', ' : '' ?>
  <a href="<?= $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>"><?= $category->getName() ?></a>
<?php endforeach ?>

<?php if ($post->getImage()) : ?>
  <p>
    <img src="<?= $post->getImageURL('large') ?>" alt="image de l'article" style="max-width:100%">
  </p>
<?php endif ?>

<p class="my-4"><?= $post->getFormattedContent() ?></p>