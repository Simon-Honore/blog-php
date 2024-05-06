<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validator;
use App\Validators\PostValidator;

$errors = [];

$post = new Post();

if (!empty($_POST)) {
  $pdo = Connection::getPDO();
  $postTable = new PostTable($pdo);
  Validator::lang('fr');
  $v = new PostValidator($_POST, $postTable, $post->getId());
  ObjectHelper::hydrate($post, $_POST, ['name', 'slug', 'content', 'created_at']);
  if ($v->validate()) {
    $postTable->create($post);
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