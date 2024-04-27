<?php

namespace App;

use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use PDO;


class Connection
{

  public static function getPDO(): PDO
  {
    return new PDO($_ENV['DATABASE_URL'], $_ENV['DATABASE_USERNAME'], $_ENV['DATABASE_PASSWORD'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
}
