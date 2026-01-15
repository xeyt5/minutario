<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nombreUsuario = $_SESSION['usuario'];

// Año actual
$anioActual = date('Y');

// Obtener último número del año actual
$sql = "
  SELECT numero_oficio 
  FROM oficios 
  WHERE numero_oficio LIKE 'SM/%/$anioActual'
  ORDER BY id DESC 
  LIMIT 1
";

$stmt = $pdo->query($sql);
$ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ultimo) {
  // Ejemplo: SM/023/2026
  $partes = explode('/', $ultimo['numero_oficio']);
  $numero = intval($partes[1]) + 1;
} else {
  $numero = 1;
}

// Formatear con ceros
$numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);
$numeroOficio = "SM/$numeroFormateado/$anioActual";
?>
<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>Minutario de Oficios</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full">
<?php require 'navbar.php'; ?>

<div class="min-h-full bg-gradient-to-br from-slate-50 to-slate-100">
  <div class="max-w-4xl mx-auto p-8">

    <div class="bg-white rounded-t-2xl shadow-lg p-8 border-b-4 border-blue-600">
      <h1 class="text-4xl font-bold text-slate-800">Minutario de Oficios</h1>
      <p class="text-slate-600 text-lg">
        Registro y control de documentación oficial
      </p>
    </div>

    <?php if (isset($_GET['ok'])): ?>
      <div class="bg-green-100 text-green-700 p-4 rounded-lg mt-4 shadow">
        ✔ Registro guardado correctamente
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-b-2xl shadow-lg p-8 mt-4">
      <form method="POST" action="guardar_minutario.php" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <!-- Número de oficio automático -->
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
              Número de Oficio
            </label>
            <input
              type="text"
              name="numero_oficio"
              value="<?= $numeroOficio ?>"
              readonly
              class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg
                     bg-slate-100 text-slate-700 cursor-not-allowed"
            />
            <p class="text-xs text-slate-500 mt-1">
              Siguiente número disponible
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
              Fecha
            </label>
            <input
              type="date"
              name="fecha"
              required
              class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg"
            />
          </div>

        </div>

        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            Destinatario
          </label>
          <input
            type="text"
            name="destinatario"
            required
            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            Archivado en
          </label>
          <input
            type="text"
            name="archivado_en"
            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg"
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
              Estatus
            </label>
            <input
              type="text"
              name="estatus"
              class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
              Registrado por
            </label>
            <input
              type="text"
              value="<?= htmlspecialchars($nombreUsuario) ?>"
              readonly
              class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg
                     bg-slate-100 cursor-not-allowed"
            />
          </div>

        </div>

        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            Concepto
          </label>
          <textarea
            name="concepto"
            rows="5"
            required
            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg resize-none"
          ></textarea>
        </div>

        <div class="flex gap-4 pt-4">
          <button
            type="submit"
            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg"
          >
            Guardar Registro
          </button>

          <button
            type="reset"
            class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 rounded-lg"
          >
            Limpiar
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

</body>
</html>
