<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$usuario_id = $_SESSION['usuario_id'];
$password_actual = $_POST['password_actual'] ?? '';
$password_nueva = $_POST['password_nueva'] ?? '';
$password_confirmar = $_POST['password_confirmar'] ?? '';

/* Validar que coincidan */
if ($password_nueva !== $password_confirmar) {
  header("Location: reset_pass.php?error=Las contraseñas no coinciden");
  exit;
}

/* Obtener contraseña actual */
$stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  header("Location: reset_pass.php?error=Usuario no encontrado");
  exit;
}


if (!password_verify($password_actual, $usuario['password'])) {
  header("Location: reset_pass.php?error=Contraseña actual incorrecta");
  exit;
}


$password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);


$update = $pdo->prepare(
  "UPDATE usuarios SET password = :password WHERE id = :id"
);

if ($update->execute([
  'password' => $password_hash,
  'id' => $usuario_id
])) {
  header("Location: reset_pass.php?success=1");
} else {
  header("Location: reset_pass.php?error=Error al actualizar contraseña");
}
