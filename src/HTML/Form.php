<?php

namespace App\HTML;

use DateTimeInterface;

class Form
{

  private $data;
  private $errors;

  public function __construct($data, array $errors)
  {
    $this->data = $data;
    $this->errors = $errors;
  }

  public function input(string $key, string $label): string
  {
    return <<<HTML
        <div class="mb-3">
          <label for="field{$key}" class="form-label">$label</label>
          <input type="text" class="{$this->getInputClass($key)}" id="field{$key}" name="{$key}" value="{$this->getValue($key)}" />
          {$this->getErrorsFeedback($key)}
        </div>
    HTML;
  }

  public function textarea(string $key, string $label): string
  {
    return <<<HTML
        <div class="mb-3">
          <label for="field{$key}" class="form-label">$label</label>
          <textarea type="text" class="{$this->getInputClass($key)}" id="field{$key}" name="{$key}" rows="8">{$this->getValue($key)}</textarea>
          {$this->getErrorsFeedback($key)}
        </div>
    HTML;
  }

  private function getValue(string $key): ?string
  {
    if (is_array($this->data)) {
      return $this->data[$key] ?? null;
    }
    $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
    $value = $this->data->$method();
    if ($value instanceof DateTimeInterface) {
      return $value->format("Y-m-d H:i:s");
    }
    return $value;
  }

  private function getInputClass(string $key): string
  {
    $inputClass = 'form-control';
    if (isset($this->errors[$key])) {
      $inputClass .= ' is-invalid';
    }
    return $inputClass;
  }

  private function getErrorsFeedback(string $key): string
  {
    if (isset($this->errors[$key])) {
      return '<div class="invalid-feedback">' .
        implode("<br/>", $this->errors[$key]) .
        '</div>';
    }
    return '';
  }
}
