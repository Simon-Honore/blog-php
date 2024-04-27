<div class="card h-100">
  <div class="card-body">
    <h5 class="card-title"><?= $post->getName() ?></h5>
    <p class="text-muted"><?= $post->getDateTime()->format('d F Y') ?></p>
    <p class="card-text"><?= $post->getExcerpt() ?></p>
    <a href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>" class="btn btn-primary btn-sm">voir plus</a>
  </div>
</div>