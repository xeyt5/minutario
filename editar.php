<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

require 'db.php';

$usuario_id = $_SESSION['usuario_id'];
$nombreUsuario = $_SESSION['usuario'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: tabla.php');
  exit;
}

$id = (int) $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM oficios WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$oficio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$oficio) {
  header('Location: tabla.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $fecha         = $_POST['fecha'];
  $destinatario  = trim($_POST['destinatario']);
  $archivado_en  = trim($_POST['archivado_en']);
  $estatus       = trim($_POST['estatus']);
  $concepto      = trim($_POST['concepto']);

  if ($fecha && $destinatario && $archivado_en && $estatus && $concepto) {

    $update = "
      UPDATE oficios SET
        fecha = :fecha,
        destinatario = :destinatario,
        archivado_en = :archivado_en,
        estatus = :estatus,
        concepto = :concepto
      WHERE id = :id
    ";

    $stmt = $pdo->prepare($update);
    $stmt->execute([
      'fecha'         => $fecha,
      'destinatario'  => $destinatario,
      'archivado_en'  => $archivado_en,
      'estatus'       => $estatus,
      'concepto'      => $concepto,
      'id'            => $id
    ]);

    header('Location: tabla.php');
    exit;
  }

  $error = "Todos los campos son obligatorios.";
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Oficio</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">
<?php require 'navbar.php'; ?>

<div class="max-w-4xl mx-auto p-8">
  <div class="bg-white shadow-lg rounded-2xl p-8">

    <h1 class="text-3xl font-bold mb-6">Editar Oficio</h1>

    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" id="formEditar" class="space-y-6">


      <div>
        <label class="font-semibold text-sm">Número de Oficio</label>
        <input
          type="text"
          value="<?= htmlspecialchars($oficio['numero_oficio']) ?>"
          readonly
          class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg
                 bg-slate-100 cursor-not-allowed text-slate-600"
        />
      </div>

      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <label class="font-semibold text-sm">Fecha</label>
          <input type="date" name="fecha" required
            value="<?= htmlspecialchars($oficio['fecha']) ?>"
            class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div>
          <label class="font-semibold text-sm">Estatus</label>
          <input type="text" name="estatus" required
            value="<?= htmlspecialchars($oficio['estatus']) ?>"
            class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
      </div>

      <div>
        <label class="font-semibold text-sm">Destinatario</label>
        <input type="text" name="destinatario" required
          value="<?= htmlspecialchars($oficio['destinatario']) ?>"
          class="w-full px-4 py-3 border-2 rounded-lg">
      </div>

      <div>
        <label class="font-semibold text-sm">Archivado en</label>
        <input type="text" name="archivado_en" required
          value="<?= htmlspecialchars($oficio['archivado_en']) ?>"
          class="w-full px-4 py-3 border-2 rounded-lg">
      </div>

      <div>
        <label class="font-semibold text-sm">Concepto</label>
        <textarea name="concepto" rows="5" required
          class="w-full px-4 py-3 border-2 rounded-lg"><?= htmlspecialchars($oficio['concepto']) ?></textarea>
      </div>

      <div class="flex gap-4">
        <button type="button" onclick="cancelar()"
          class="flex-1 bg-slate-200 py-3 rounded-lg font-semibold">
          Cancelar
        </button>

        <button type="button" onclick="confirmarGuardar()"
          class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold">
          Guardar Cambios
        </button>
      </div>

    </form>
  </div>
</div>


<div id="modalCancelar" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center">
  <div class="bg-white p-6 rounded-xl w-96">
    <h2 class="text-xl font-bold mb-4">¿Cancelar cambios?</h2>
    <p class="text-slate-600 mb-6">Tienes cambios sin guardar.</p>
    <div class="flex gap-3">
      <button onclick="cerrarModal('modalCancelar')" class="flex-1 bg-slate-200 py-2 rounded">
        Seguir editando
      </button>
      <a href="tabla.php" class="flex-1 bg-red-600 text-white py-2 rounded text-center">
        Salir
      </a>
    </div>
  </div>
</div>

<div id="modalGuardar" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center">
  <div class="bg-white p-6 rounded-xl w-96">
    <h2 class="text-xl font-bold mb-4">¿Guardar cambios?</h2>
    <p class="text-slate-600 mb-6">¿Seguro que deseas actualizar este oficio?</p>
    <div class="flex gap-3">
      <button onclick="cerrarModal('modalGuardar')" class="flex-1 bg-slate-200 py-2 rounded">
        Cancelar
      </button>
      <button onclick="document.getElementById('formEditar').submit()"
        class="flex-1 bg-blue-600 text-white py-2 rounded">
        Sí, guardar
      </button>
    </div>
  </div>
</div>

<script>
  const form = document.getElementById('formEditar');
  const initialData = new FormData(form);

  function huboCambios() {
    const current = new FormData(form);
    for (let [key, value] of current.entries()) {
      if (initialData.get(key) !== value) return true;
    }
    return false;
  }

  function cancelar() {
    if (huboCambios()) {
      document.getElementById('modalCancelar').classList.remove('hidden');
    } else {
      window.location.href = 'tabla.php';
    }
  }

  function confirmarGuardar() {
    document.getElementById('modalGuardar').classList.remove('hidden');
  }

  function cerrarModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>

</body>
</html>
