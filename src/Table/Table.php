<?php

namespace App\Table;

use App\Table\Exception\NotFoundException;
use Exception;
use PDO;

abstract class Table
{

  protected $pdo;
  protected $table = null;
  protected $class = null;

  public function __construct(PDO $pdo)
  {
    if (!$this->table) {
      throw new Exception('La class ' . get_class($this) . " n'a pas de propriété \$table");
    }
    if (!$this->class) {
      throw new Exception('La class ' . get_class($this) . " n'a pas de propriété \$class");
    }
    $this->pdo = $pdo;
  }

  public function find(int $id)
  {
    $query = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
    $query->execute(['id' => $id]);
    $result = $query->fetchObject($this->class);
    if (!$result) {
      throw new NotFoundException($this->table, $id);
    }
    return $result;
  }
}
