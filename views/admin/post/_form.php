<form action="" method="post" enctype="multipart/form-data">
  <?= $form->input('name', 'Titre') ?>
  <?= $form->input('slug', 'Slug') ?>
  <div class="row">
    <div class="col-md-10">
      <?= $form->file('image', 'Image à la une') ?>
    </div>
    <div class="col-md-2">
      <?php if ($post->getImage()) : ?>
        <img src="<?= $post->getImageURL('small') ?>" alt="image de l'article" style="width:100%">
      <?php endif ?>
    </div>
  </div>
  <?= $form->select('categories_ids', 'Catégories', $categories) ?>
  <?= $form->textarea('content', 'Contenu') ?> <?= $form->input('created_at', 'Date de création') ?>
  <button type="submit" class="btn btn-primary">
    <?= $post->getId() !== null ? 'Modifier' : 'Créer' ?>
  </button>
</form>