<?php

use App\Connection;
use App\HTML\Form;
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
  $v
    ->rule('required', ['name', 'slug'])
    ->rule('lengthBetween', ['name', 'slug'], 3, 200);
  $post
    ->setName($_POST['name'])
    ->setSlug($_POST['slug'])
    ->setContent($_POST['content'])
    ->setCreatedAt($_POST['created_at']);
  if ($v->validate()) {
    $postTable->update($post);
    $success = true;
  } else {
    $errors = $v->errors();
  }
}

$form = new Form($post, $errors);

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
  <?= $form->input('name', 'Titre') ?>
  <?= $form->input('slug', 'Slug') ?>
  <?= $form->textarea('content', 'Contenu') ?>
  <?= $form->input('created_at', 'Date de création') ?>

  <button type="submit" class="btn btn-primary">Modifier</button>
</form>