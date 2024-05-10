<?php

namespace App\Validators;

use App\Table\PostTable;

final class PostValidator extends AbstractValidator
{

  public function __construct(array $data, PostTable $table, array $categories, ?int $postId = null)
  {
    parent::__construct($data);
    $this->validator
      ->rule('required', ['name', 'slug'])
      ->rule('lengthBetween', ['name', 'slug'], 3, 200)
      ->rule('slug', 'slug')
      ->rule('subset', 'categories_ids', array_keys($categories))
      ->rule(function ($field, $value) use ($table, $postId) {
        return !$table->exists($field, $value, $postId);
      }, ['slug', 'name'], 'Cette valeur est déjà utilisé');
  }
}
