<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "";
$db   = "";
$user = "";
$pass = "";

try {
  $pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die("ERROR DB: " . $e->getMessage());
}
