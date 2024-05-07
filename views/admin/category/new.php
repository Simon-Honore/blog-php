<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validator;
use App\Validators\CategoryValidator;

Auth::check();

$errors = [];

$item = new Category();
$fields = ['name', 'slug'];

if (!empty($_POST)) {
  $pdo = Connection::getPDO();
  $table = new CategoryTable($pdo);
  Validator::lang('fr');
  $v = new CategoryValidator($_POST, $table);
  ObjectHelper::hydrate($item, $_POST, $fields);
  if ($v->validate()) {
    $table->create([
      'name' => $item->getName(),
      'slug' => $item->getSlug()
    ]);
    header('Location: ' . $router->url('admin_categories' . '?created=1'));
    exit();
  } else {
    $errors = $v->errors();
  }
}

$form = new Form($item, $errors);

?>

<h1>Création d'une catégorie</h1>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    La catégorie n'a pas pu être enregistrée, merci de corriger vos erreurs.
  </div>
<?php endif ?>

<?php require('_form.php') ?>