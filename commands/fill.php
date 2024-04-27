<?php

use App\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$faker = Faker\Factory::create('fr_FR');

$pdo = Connection::getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

// Posts
$posts = [];
for ($i = 0; $i < 50; $i++) {
  $pdo->exec("INSERT INTO post SET name='{$faker->sentence()}', slug='{$faker->slug()}', content='{$faker->paragraphs(rand(3, 15), true)}', created_at='{$faker->date()} {$faker->time()}'");
  $posts[] = $pdo->lastInsertId();
}

// Categories
$categories = [];
for ($i = 0; $i < 5; $i++) {
  $pdo->exec("INSERT INTO category SET name='{$faker->sentence(3)}', slug='{$faker->slug()}'");
  $categories[] = $pdo->lastInsertId();
}

// Post_category
foreach ($posts as $post) {
  $random_categories = $faker->randomElements($categories, rand(0, count($categories)));
  foreach ($random_categories as $category) {
    $pdo->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
  }
}

// User (admin)
$admin_password_hash = password_hash($_ENV['ADMIN_PASSWORD'], PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='{$_ENV['ADMIN_USERNAME']}', password='$admin_password_hash'");
