<?php

namespace App\Model;

class Category
{

  private $id;
  private $name;
  private $slug;
  private $post_id;
  private $post;

  public function getName(): ?string
  {
    return $this->name ? htmlentities($this->name) : null;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function getPostId(): ?int
  {
    return $this->post_id;
  }

  public function setPost(Post $post): void
  {
    $this->post = $post;
  }
}
