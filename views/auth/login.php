<?php

use App\Connection;
use App\HTML\Form;
use App\Model\User;
use App\Table\Exception\NotFoundException;
use App\Table\UserTable;

$user = new User();
$errors = [];

if (!empty($_POST)) {
  $user->setUsername($_POST['username']);
  $errors['password'] = 'Identifiant ou mot de passe incorrect';

  if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $table = new UserTable(Connection::getPDO());
    try {
      $u = $table->findByUsername($_POST['username']);
      if (password_verify($_POST['password'], $u->getPassword())) {
        session_start();
        $_SESSION['auth'] = $u->getId();
        header('Location: ' . $router->url('admin_posts'));
        exit();
      }
    } catch (NotFoundException $e) {
    }
  }
}

$form = new Form($user, $errors);

?>

<h1 class="mb-4">Espace administrateur</h1>

<?php if (isset($_GET['forbidden'])) : ?>
  <div class="alert alert-danger">
    Vous devez être connecté en tant qu'administrateur pour accéder à cette page
  </div>
<?php endif ?>

<form action="<?= $router->url('login') ?>" method="post">
  <?= $form->input('username', 'Nom d\'utilisateur') ?>
  <?= $form->input('password', 'Mot de passe') ?>
  <button type="submit" class="btn btn-primary">Se connecter</button>
</form>