<?php

namespace App;

use Exception;

class URL
{

  public static function getInt(string $name, ?int $default = null): ?int
  {
    if (!isset($_GET[$name])) return $default;
    if ($_GET[$name] === '0') return 0;

    if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
      throw new Exception("Le paramètre '$name' dans l'url n'est pas valide (entier attendu)");
    }

    return (int)$_GET[$name];
  }

  public static function getIntPositive(string $name, ?int $default = null): ?int
  {
    $current = self::getInt($name, $default);

    if ($current !== null && $current <= 0) {
      throw new Exception("Le paramètre '$name' dans l'url doit être un entier positif");
    }

    return $current;
  }
}
