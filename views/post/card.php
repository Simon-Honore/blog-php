<?php
$categories = array_map(function ($category) use ($router) {
  $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]);
  return <<<HTML
      <a href="$url">{$category->getName()}</a>
    HTML;
}, $post->getCategories());


?>

<div class="card h-100">
  <?php if ($post->getImage()) : ?>
    <img src="<?= $post->getImageURL('small') ?>" alt="image de l'article" class="card-img-top">
  <?php endif ?>
  <div class="card-body">
    <h5 class="card-title"><?= $post->getName() ?></h5>
    <p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
    <div class="mb-3">
      <?= implode(', ', $categories) ?>
    </div>
    <p class="card-text"><?= $post->getExcerpt() ?></p>
    <a href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>" class="btn btn-primary btn-sm">voir plus</a>
  </div>
</div>