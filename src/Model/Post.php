<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;
use DateTimeZone;

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
    return $this->name ? htmlentities($this->name) : null;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
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

  public function getDateTime(): DateTime
  {
    return new DateTime($this->created_at);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
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
