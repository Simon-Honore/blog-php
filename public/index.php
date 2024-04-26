<?php

use App\Router;

require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router = new Router(dirname(__DIR__) . '/views' . DIRECTORY_SEPARATOR);

$router
  ->get('/blog', 'post/index', 'blog')
  ->get('/blog/category', 'category/show', 'category')
  ->run();
