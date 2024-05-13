<?php

use App\Attachment\PostAttachment;
use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();
$post = $postTable->find($params['id']);
$categoryTable->hydratePosts([$post]);


$success = false;
$errors = [];

if (!empty($_POST)) {
  $data = array_merge($_POST, $_FILES);
  $v = new PostValidator($data, $postTable, $categories, $post->getId());
  ObjectHelper::hydrate($post, $data, ['name', 'slug', 'content', 'created_at', 'image']);
  if ($v->validate()) {
    $pdo->beginTransaction();
    PostAttachment::upload($post);
    $postTable->updatePost($post);
    $postTable->attachCategories($post->getId(), $_POST['categories_ids']);
    $pdo->commit();
    $categoryTable->hydratePosts([$post]);
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