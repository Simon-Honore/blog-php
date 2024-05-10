<?php

namespace App;

use AltoRouter;
use App\Security\ForbiddenException;

class Router
{
  /**
   * @var string
   */
  private $viewPath;

  /**
   * @var AltoRouter
   */
  private $router;

  public function __construct(string $viewPath)
  {
    $this->viewPath = $viewPath;
    $this->router = new AltoRouter();
  }

  public function get(string $url, string $view, ?string $name = null): self
  {
    $this->router->map('GET', $url, $view, $name);
    return $this;
  }

  public function post(string $url, string $view, ?string $name = null): self
  {
    $this->router->map('POST', $url, $view, $name);
    return $this;
  }

  public function match(string $url, string $view, ?string $name = null): self
  {
    $this->router->map('POST|GET', $url, $view, $name);
    return $this;
  }

  public function url(string $name, array $params = [])
  {
    return $this->router->generate($name, $params);
  }

  public function run(): self
  {
    $match = $this->router->match();
    if (!$match) {
      header("HTTP/1.0 404 Not Found");
      $router = $this;
      ob_start();
      require $this->viewPath . DIRECTORY_SEPARATOR . "e404.php";
      $content = ob_get_clean();
      require $this->viewPath . 'layout/default.php';

      exit();
    }
    $view = $match['target'] ?: 'e404';
    $params = $match['params'];
    $router = $this;
    $isAdmin = strpos($view, 'admin') !== false;
    $layout = $isAdmin ? 'admin/layout/default' : 'layout/default';
    try {

      ob_start();
      require $this->viewPath . $view . '.php';
      $content = ob_get_clean();
      require $this->viewPath . $layout .  '.php';
    } catch (ForbiddenException $e) {
      header('Location: ' . $this->url('login') . '?forbidden=1');
      exit();
    }
    return $this;
  }
}
