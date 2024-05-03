<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

$title = 'Administration';

Auth::check();

$pdo = Connection::getPDO();

[$posts, $pagination] = (new PostTable($pdo))->findPaginated();

$link = $router->url('admin_posts');

?>
<?php if (isset($_GET['delete'])) : ?>
  <div class="alert alert-success">
    L'article a bien été supprimé
  </div>
<?php endif ?>

<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Titre</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($posts as $post) : ?>
      <tr>
        <td>#<?= $post->getId() ?></td>
        <td>
          <a href="<?= $router->url('admin_post', ['id' => $post->getId()]) ?>">
            <?= $post->getName() ?>
          </a>
        </td>
        <td>
          <a href="<?= $router->url('admin_post', ['id' => $post->getId()]) ?>" class="btn btn-primary">
            Éditer
          </a>
          <form action="<?= $router->url('admin_post_delete', ['id' => $post->getId()]) ?>" method="post" onclick="return confirm('Souhaitez-vous vraiment supprimer cet article ?')" style="display: inline">
            <button type="submit" class="btn btn-danger">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<div class="d-flex justify-content-between my-4">
  <?= $pagination->previousPagLink($link) ?>
  <?= $pagination->nextPagLink($link) ?>
</div>