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

  /**
   * Vérifie si une valeur existe dans la table 
   * 
   * @param string $field Champs à chercher
   * @param mixed $value Valeur associé au champs 
   * @return bool
   */
  public function exists(string $field, $value, ?int $except): bool
  {
    $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
    $params = [$value];
    if ($except) {
      $sql .= " AND id != ?";
      $params[] = $except;
    }
    $query = $this->pdo->prepare($sql);
    $query->execute($params);
    return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;
  }

  public function queryAndFetchAll(string $query): array
  {
    return $this->pdo->query($query, PDO::FETCH_CLASS, $this->class)->fetchAll();
  }

  public function all(): array
  {
    return $this->queryAndFetchAll("SELECT * FROM {$this->table}");
  }

  public function create(array $data): int
  {
    $sqlFields = [];
    foreach ($data as $key => $value) {
      $sqlFields[] = "$key = :$key";
    }
    $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(', ', $sqlFields));
    $ok = $query->execute($data);
    if (!$ok) {
      throw new Exception("Impossible de créer l'enregistrement dans la table {$this->table}");
    }
    return (int)$this->pdo->lastInsertId();
  }

  public function update(array $data, int $id): void
  {
    $sqlFields = [];
    foreach ($data as $key => $value) {
      $sqlFields[] = "$key = :$key";
    }
    $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $sqlFields) . " WHERE id = :id");
    $ok = $query->execute(array_merge($data, ['id' => $id]));
    if (!$ok) {
      throw new Exception("Impossible de modifier l'enregistrement dans la table {$this->table}");
    }
  }

  public function delete(int $id): void
  {
    $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
    $ok = $query->execute([$id]);
    if (!$ok) {
      throw new Exception("Impossible de supprimer l'enregistrement $id de la table {$this->table}");
    }
  }
}
