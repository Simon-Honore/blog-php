<?php

namespace App;

use Exception;
use PDO;

class PaginatedQuery
{

  private $query;
  private $queryCount;
  private $pdo;
  private $perPage;
  private $count;
  private $items;

  public function __construct(
    string $query,
    string $queryCount,
    ?PDO $pdo = null,
    int $perPage = 12
  ) {

    $this->query = $query;
    $this->queryCount = $queryCount;
    $this->pdo = $pdo ?? Connection::getPDO();
    $this->perPage = $perPage;
  }

  public function getItems(string $classMapping): array
  {
    if (!$this->items) {
      $current_page = $this->getCurrentPage();
      $count_pages = $this->getCountPages();
      if ($current_page > $count_pages) {
        throw new Exception('Cette page n\'existe pas');
      }
      $offset = $this->perPage * ($current_page - 1);
      $this->items = $this->pdo
        ->query($this->query . " LIMIT {$this->perPage} OFFSET $offset")
        ->fetchAll(PDO::FETCH_CLASS, $classMapping);
    }
    return $this->items;
  }

  public function previousPagLink(string $link): ?string
  {
    $current_page = $this->getCurrentPage();
    if ($current_page <= 1) return null;
    if ($current_page > 2) $link .= "?page=" . ($current_page - 1);
    return <<<HTML
      <a href="{$link}" class="btn btn-primary">&laquo; Page précédente</a>
    HTML;
  }

  public function nextPagLink(string $link): ?string
  {
    $count_pages = $this->getCountPages();
    $current_page = $this->getCurrentPage();
    if ($current_page >= $count_pages) return null;
    $link .= "?page=" . ($current_page + 1);
    return <<<HTML
      <a href="{$link}" class="btn btn-primary ms-auto">Page suivante &raquo;</a>
    HTML;
  }

  private function getCurrentPage(): int
  {
    return URL::getIntPositive('page', 1);
  }

  private function getCountPages(): int
  {
    if (!$this->count) {
      $this->count = (int)$this->pdo
        ->query($this->queryCount)
        ->fetch(PDO::FETCH_NUM)[0];
    }
    return ceil($this->count / $this->perPage);
  }
}
