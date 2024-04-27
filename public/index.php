<?php

use App\Router;

require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (isset($_GET['page']) && $_GET['page'] === '1') {
  $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
  $get = $_GET;
  unset($get['page']);
  $query = http_build_query($get);
  if (!empty($query)) {
    $uri .= '?' . $query;
  }
  header('Location: ' . $uri);
  http_response_code(301);
  exit();
}

$router = new Router(dirname(__DIR__) . '/views' . DIRECTORY_SEPARATOR);

$router
  ->get('/', 'post/index', 'home')
  ->get('/blog/[*:slug]-[int:id]', 'post/show', 'post')
  ->get('/blog/category', 'category/show', 'category')
  ->run();
