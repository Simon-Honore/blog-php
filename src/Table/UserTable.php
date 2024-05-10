<?php

namespace App\Table;

use App\Model\User;
use App\Table\Exception\NotFoundException;

final class UserTable extends Table
{
  protected $table = 'user';
  protected $class = User::class;

  public function findByUsername(string $username)
  {
    $query = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE username = ?");
    $query->execute([$username]);
    $result = $query->fetchObject($this->class);
    if (!$result) {
      throw new NotFoundException($this->table, $username);
    }
    return $result;
  }
}
