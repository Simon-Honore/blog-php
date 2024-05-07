<?php

namespace App\Table;

use App\Model\Post;
use App\PaginatedQuery;
use Exception;

final class PostTable extends Table
{
  protected $table = 'post';
  protected $class = Post::class;

  public function findPaginated()
  {
    $paginatedQuery = new PaginatedQuery(
      "SELECT * FROM {$this->table} ORDER BY created_at DESC",
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
      FROM {$this->table} p 
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

  public function updatePost(Post $post): void
  {
    $this->update([
      'name' => $post->getName(),
      'slug' => $post->getSlug(),
      'content' => $post->getContent(),
      'created_at' => $post->getCreatedAt()->format("Y-m-d H:i:s"),
    ], $post->getId());
  }

  public function createPost(Post $post): void
  {
    $id = $this->create([
      'name' => $post->getName(),
      'slug' => $post->getSlug(),
      'content' => $post->getContent(),
      'created_at' => $post->getCreatedAt()->format("Y-m-d H:i:s"),
    ]);
    $post->setId($id);
  }
}
