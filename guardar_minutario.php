<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: minutario.php');
  exit;
}

require 'db.php'; // aquí se crea $pdo

$usuario_id = $_SESSION['usuario_id'];

$numero_oficio = trim($_POST['numero_oficio'] ?? '');
$fecha         = $_POST['fecha'] ?? '';
$destinatario  = trim($_POST['destinatario'] ?? '');
$archivado_en  = trim($_POST['archivado_en'] ?? '');
$estatus       = trim($_POST['estatus'] ?? '');
$concepto      = trim($_POST['concepto'] ?? '');

if (
  $numero_oficio === '' ||
  $fecha === '' ||
  $destinatario === '' ||
  $archivado_en === '' ||
  $estatus === '' ||
  $concepto === ''
) {
  die('Datos incompletos');
}

$sql = "INSERT INTO oficios
(numero_oficio, fecha, destinatario, archivado_en, estatus, concepto, usuario_id)
VALUES (:numero, :fecha, :destinatario, :archivo, :estatus, :concepto, :usuario)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  ':numero'       => $numero_oficio,
  ':fecha'        => $fecha,
  ':destinatario' => $destinatario,
  ':archivo'      => $archivado_en,
  ':estatus'      => $estatus,
  ':concepto'     => $concepto,
  ':usuario'      => $usuario_id
]);

header("Location: minutario.php?ok=1");
exit;
