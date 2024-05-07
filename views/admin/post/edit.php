<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validator;
use App\Validators\PostValidator;

Auth::check();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);


$success = false;
$errors = [];

if (!empty($_POST)) {
  Validator::lang('fr');
  $v = new PostValidator($_POST, $postTable, $post->getId());
  ObjectHelper::hydrate($post, $_POST, ['name', 'slug', 'content', 'created_at']);
  if ($v->validate()) {
    $postTable->updatePost($post);
    $success = true;
  } else {
    $errors = $v->errors();
  }
}

$form = new Form($post, $errors);

?>

<h1>edition de l'article <?= e($post->getName()) ?></h1>

<?php if ($success) : ?>
  <div class="alert alert-success">
    L'article a bien été modifié.
  </div>
<?php endif ?>

<?php if (isset($_GET['created'])) : ?>
  <div class="alert alert-success">
    L'article a bien été créé.
  </div>
<?php endif ?>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    L'article n'a pas pu être modifié, merci de corriger vos erreurs.
  </div>
<?php endif ?>

<?php require('_form.php') ?>