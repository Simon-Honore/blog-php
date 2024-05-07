<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post
{

  private $id;
  private $name;
  private $slug;
  private $content;
  private $created_at;
  private $categories = [];

  /**
   * Get the name of post converted to HTML entities
   */
  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): self
  {
    $this->content = $content;
    return $this;
  }

  /**
   * Get a excerpt of content of post converted to HTML entities
   * 
   * @param int $limit character limit
   * 
   * @return string|null
   */
  public function getExcerpt(int $limit = 60): ?string
  {
    return $this->content ? nl2br(htmlentities(Text::excerpt($this->content, $limit))) : null;
  }

  public function getCreatedAt(): DateTime
  {
    return new DateTime($this->created_at ?? '');
  }

  public function setCreatedAt(string $created_at): self
  {
    $this->created_at = $created_at;
    return $this;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id): self
  {
    $this->id = $id;
    return $this;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): self
  {
    $this->slug = $slug;
    return $this;
  }

  public function getFormattedContent(): ?string
  {
    return $this->content ? nl2br(htmlentities($this->content)) : null;
  }

  /**
   * @return Category[]
   */
  public function getCategories(): array
  {
    return $this->categories;
  }

  public function addCategory(Category $category): void
  {
    $this->categories[] = $category;
    $category->setPost($this);
  }
}
