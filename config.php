<?php

$hostname = 'localhost';
$dbname   = 'quiz';
$username = 'root';
$password = '';
$charset = 'utf8mb4';
$port = 3306;

$options = [
  \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
  \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
  \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$hostname;dbname=$dbname;charset=$charset;port=$port;";

try {
  $conn = new \PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}