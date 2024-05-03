<?php

namespace App;

use Valitron\Validator as ValitronValidator;

class Validator extends ValitronValidator
{

  protected function checkAndSetLabel($field, $message, $params)
  {
    return str_replace('{field} ', '', $message);
  }
}
