<?php

namespace App\Table;

use App\Model\Post;
use App\PaginatedQuery;

final class PostTable extends Table
{
  protected $table = 'post';
  protected $class = Post::class;

  public function findPaginated()
  {
    $paginatedQuery = new PaginatedQuery(
      "SELECT * FROM post ORDER BY created_at DESC",
      "SELECT COUNT(id) FROM post"
    );

    $posts = $paginatedQuery->getItems(Post::class);
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
  }

  public function findPaginatedByCategory(int $categoryId)
  {
    $query = "
      SELECT p.* 
      FROM post p 
      JOIN post_category pc ON pc.post_id = p.id 
      WHERE pc.category_id = {$categoryId} 
      ORDER BY created_at DESC";

    $queryCount = "
      SELECT COUNT(category_id) 
      FROM post_category WHERE category_id = {$categoryId}";

    $paginatedQuery = new PaginatedQuery($query, $queryCount);
    $posts = $paginatedQuery->getItems($this->class);
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
  }
}
