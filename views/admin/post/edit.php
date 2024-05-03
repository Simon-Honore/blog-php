<?php

use App\Connection;
use App\Table\PostTable;
use App\Validator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);

$success = false;
$errors = [];


if (!empty($_POST)) {
  Validator::lang('fr');
  $v = new Validator($_POST);
  $v->rules([
    'required' => [
      ['name']
    ],
    'lengthBetween' => [
      ['name', 3, 200]
    ]
  ]);
  $post->setName($_POST['name']);
  if ($v->validate()) {
    $postTable->update($post);
    $success = true;
  } else {
    $errors = $v->errors();
  }
}

?>

<h1>edition de l'article <?= $post->getName() ?></h1>

<?php if ($success) : ?>
  <div class="alert alert-success">
    L'article a bien été modifié.
  </div>
<?php endif ?>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    L'article n'a pas pu être modifié, merci de corriger vos erreurs.
  </div>
<?php endif ?>

<form action="" method="post">
  <div class="mb-3">
    <label for="name" class="form-label">Titre</label>
    <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= $post->getName() ?>" />
    <?php if (isset($errors['name'])) : ?>
      <div class="invalid-feedback">
        <?= implode("<br/>", $errors['name']) ?>
      </div>
    <?php endif ?>
  </div>

  <button type="submit" class="btn btn-primary">Modifier</button>
</form>