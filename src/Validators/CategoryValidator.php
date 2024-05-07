<?php

namespace App\Validators;

use App\Table\CategoryTable;

final class CategoryValidator extends AbstractValidator
{

  public function __construct(array $data, CategoryTable $table, ?int $id = null)
  {
    parent::__construct($data);
    $this->validator
      ->rule('required', ['name', 'slug'])
      ->rule('lengthBetween', ['name', 'slug'], 3, 200)
      ->rule('slug', 'slug')
      ->rule(function ($field, $value) use ($table, $id) {
        return !$table->exists($field, $value, $id);
      }, ['slug', 'name'], 'Cette valeur est déjà utilisé');
  }
}
