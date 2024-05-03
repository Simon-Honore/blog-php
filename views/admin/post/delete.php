<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();

$pdo = Connection::getPDO();
(new PostTable($pdo))->delete($params['id']);
header('Location: ' . $router->url('admin_posts') . '?delete=1');
