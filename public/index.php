<?php

use App\Router;

require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router = new Router(dirname(__DIR__) . '/views' . DIRECTORY_SEPARATOR);

$router
  ->get('/', 'post/index', 'home')
  ->get('/blog/[*:slug]-[int:id]', 'post/show', 'post')
  ->get('/blog/category', 'category/show', 'category')
  ->run();
