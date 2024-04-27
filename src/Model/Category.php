<?php

namespace App\Model;

class Category
{

  private $id;

  private $name;

  private $slug;

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
}
