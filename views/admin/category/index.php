<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

$title = 'Gestion des catégories';

Auth::check();

$pdo = Connection::getPDO();

$items = (new CategoryTable($pdo))->all();

$link = $router->url('admin_categories');

?>
<?php if (isset($_GET['delete'])) : ?>
  <div class="alert alert-success">
    L'enregistrement a bien été supprimé.
  </div>
<?php endif ?>

<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Titre</th>
      <th>URL</th>
      <th>
        <a href="<?= $router->url('admin_category_new') ?>" class="btn btn-primary">
          Nouveau
        </a>
      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item) : ?>
      <tr>
        <td>#<?= $item->getId() ?></td>
        <td>
          <a href="<?= $router->url('admin_category', ['id' => $item->getId()]) ?>">
            <?= e($item->getName()) ?>
          </a>
        </td>
        <td><?= $item->getSlug() ?></td>
        <td>
          <a href="<?= $router->url('admin_category', ['id' => $item->getId()]) ?>" class="btn btn-primary">
            Éditer
          </a>
          <form action="<?= $router->url('admin_category_delete', ['id' => $item->getId()]) ?>" method="post" onclick="return confirm('Souhaitez-vous vraiment supprimer cette catégorie ?')" style="display: inline">
            <button type="submit" class="btn btn-danger">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>