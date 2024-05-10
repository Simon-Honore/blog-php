<?php

namespace App\Table\Exception;

use Exception;

class NotFoundException extends Exception
{

  public function __construct(string $table, string|int $needle)
  {
    $this->message = "Aucun enregistrement ne correspond à {$needle} dans la table '$table'";
  }
}
