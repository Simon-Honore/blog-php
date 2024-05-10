<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$errors = [];

$post = new Post();
$pdo = Connection::getPDO();
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();


if (!empty($_POST)) {
  $postTable = new PostTable($pdo);
  $v = new PostValidator($_POST, $postTable, $categories, $post->getId());
  ObjectHelper::hydrate($post, $_POST, ['name', 'slug', 'content', 'created_at']);
  if ($v->validate()) {
    $pdo->beginTransaction();
    $postTable->createPost($post);
    $postTable->attachCategories($post->getId(), $_POST['categories_ids']);
    $pdo->commit();
    header('Location: ' . $router->url('admin_post', ['id' => $post->getId()]) . '?created=1');
    exit();
  } else {
    $errors = $v->errors();
  }
}

$form = new Form($post, $errors);

?>

<h1>Création d'un article</h1>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    L'article n'a pas pu être enregistré, merci de corriger vos erreurs.
  </div>
<?php endif ?>

<?php require('_form.php') ?>