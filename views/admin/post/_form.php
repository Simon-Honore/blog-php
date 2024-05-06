<form action="" method="post">
  <?= $form->input('name', 'Titre') ?>
  <?= $form->input('slug', 'Slug') ?>
  <?= $form->textarea('content', 'Contenu') ?>
  <?= $form->input('created_at', 'Date de création') ?>

  <button type="submit" class="btn btn-primary">
    <?= $post->getId() !== null ? 'Modifier' : 'Créer' ?>
  </button>
</form>