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
  private $image;
  private $oldImage;
  private $pendingUpload = false;

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

  public function setCategories(array $categories): self
  {
    $this->categories = $categories;
    return $this;
  }

  public function getCategoriesIds(): array
  {
    $ids = [];
    foreach ($this->categories as $category) {
      $ids[] = $category->getId();
    }
    return $ids;
  }

  public function addCategory(Category $category): void
  {
    $this->categories[] = $category;
    $category->setPost($this);
  }

  public function getImage(): ?string
  {
    return $this->image;
  }

  public function getImageURL(string $format): ?string
  {
    if (empty($this->image)) return null;

    return '/uploads/posts/' . $this->image . '_' . $format . '.jpg';
  }

  public function setImage($image): self
  {
    if (is_array($image) && !empty($image['tmp_name'])) {
      if (!empty($this->image)) {
        $this->oldImage = $this->image;
      }
      $this->pendingUpload = true;
      $this->image = $image['tmp_name'];
    }
    if (is_string($image) && !empty($image)) {
      if (!empty($this->image)) {
        $this->oldImage = $this->image;
      }
      $this->pendingUpload = true;
      $this->image = $image;
    }

    return $this;
  }

  public function getOldImage(): ?string
  {
    return $this->oldImage;
  }

  public function shouldUpload(): bool
  {
    return $this->pendingUpload;
  }
}
