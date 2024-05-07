<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

Auth::check();

$pdo = Connection::getPDO();
(new CategoryTable($pdo))->delete($params['id']);
header('Location: ' . $router->url('admin_categories') . '?delete=1');
