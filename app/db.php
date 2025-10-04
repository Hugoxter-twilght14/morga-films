<?php
require_once __DIR__ . '/config.php';

$driver = envv('DB_CONNECTION', 'mysql');
$host   = envv('DB_HOST', 'localhost:3307');
$port   = envv('DB_PORT', '3306');
$name   = envv('DB_DATABASE', 'morga_films');
$user   = envv('DB_USERNAME', 'root');
$pass   = envv('DB_PASSWORD', '');

$dsn = "$driver:host=$host;port=$port;dbname=$name;charset=utf8mb4";

$pdo = new PDO($dsn, $user, $pass, [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
]);
