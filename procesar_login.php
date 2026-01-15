<?php
session_start();
require 'db.php';

$usuario  = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['usuario_id'] = $user['id'];
  $_SESSION['usuario'] = $user['usuario'];

  header("Location: minutario.php");
  exit;
}

header("Location: login.php?error=1");
exit;
