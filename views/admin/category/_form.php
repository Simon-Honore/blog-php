<form action="" method="post">
  <?= $form->input('name', 'Titre') ?>
  <?= $form->input('slug', 'Slug') ?>

  <button type="submit" class="btn btn-primary">
    <?= $item->getId() !== null ? 'Modifier' : 'Créer' ?>
  </button>
</form>