<?php
require '../vendor/autoload.php';

$router = new AltoRouter();

define('VIEW_PATH', dirname(__DIR__) . '/views');

$router->map('GET', '/blog', function () {
  require VIEW_PATH . '/post/index.php';
});
$router->map('GET', '/blog/category', function () {
  require VIEW_PATH . '/category/show.php';
});

$match = $router->match();

$match['target']();
