<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;

$pdo = Connection::getPDO();

$id = $params['id'];
$slug = $params['slug'];

$stmt = $pdo->prepare("SELECT * FROM post WHERE id = :id");

$stmt->execute(['id' => $id]);

$post = $stmt->fetchObject(Post::class);

if (!$post) {
  throw new Exception('Aucun article ne correspond');
}

if ($post->getSlug() !== $slug) {
  $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
  http_response_code(301);
  header('Location: ' . $url);
  exit();
}

$stmt = $pdo->prepare("
SELECT c.id, c.name, c.slug
FROM post_category pc
JOIN category c ON pc.category_id = c.id
WHERE pc.post_id = :id");

$stmt->execute(['id' => $post->getId()]);

/** @var Category[] */
$categories = $stmt->fetchAll(PDO::FETCH_CLASS, Category::class);

?>

<h1><?= $post->getName() ?></h1>

<p class="text-muted"><?= $post->getDateTime()->format('d F Y') ?></p>

<?php foreach ($categories as $k => $category) : ?>
  <?= $k > 0 ? ', ' : '' ?>
  <a href="<?= $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>"><?= $category->getName() ?></a>
<?php endforeach ?>

<p class="my-4"><?= $post->getFormattedContent() ?></p>