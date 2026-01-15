<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

require 'db.php';

$sql = "
SELECT 
  m.id,
  m.numero_oficio,
  m.fecha,
  m.destinatario,
  m.archivado_en,
  m.estatus,
  m.concepto,
  u.usuario AS registrado_por
FROM oficios m
JOIN usuarios u ON u.id = m.usuario_id
ORDER BY m.fecha DESC, m.id DESC
";

$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($registros);
?>
<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>Registros del Minutario</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="h-full">

<?php require 'navbar.php'; ?>

<div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 overflow-auto">
<div class="max-w-7xl mx-auto p-8">

<!-- Header -->
<div class="bg-white rounded-t-2xl shadow-lg p-8 border-b-4 border-blue-600">
  <h1 class="text-4xl font-bold text-slate-800 mb-2">
    Registros del Minutario
  </h1>
  <p class="text-slate-600 text-lg">
    Consulta y búsqueda de documentación oficial
  </p>
</div>

<!-- Tabla -->
<div class="bg-white rounded-b-2xl shadow-lg p-8">

<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
  <div class="flex items-center gap-4">
    <h2 class="text-2xl font-bold text-slate-800">
      Lista de Oficios
    </h2>
    <span class="px-4 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
      Total: <?= $total ?> registros
    </span>
  </div>

  <input
    type="text"
    id="searchInput"
    placeholder="Buscar en la tabla..."
    class="w-full md:w-96 px-4 py-3 border-2 border-slate-300 rounded-lg
           focus:border-blue-500 focus:ring-2 focus:ring-blue-200
           outline-none transition"
  >
</div>

<div class="overflow-x-auto border-2 border-slate-200 rounded-lg">
<table class="w-full" id="tablaMinutario">

<thead class="bg-blue-600 text-white">
<tr>
  <th class="px-6 py-4 text-left text-sm font-semibold">N° Oficio</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Fecha</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Destinatario</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Archivado en</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Estatus</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Registrado por</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Concepto</th>
  <th class="px-6 py-4 text-left text-sm font-semibold">Acciones</th>
</tr>
</thead>

<tbody>
<?php if ($total === 0): ?>
<tr>
  <td colspan="8" class="px-6 py-6 text-center text-slate-500">
    No hay registros aún
  </td>
</tr>
<?php endif; ?>

<?php foreach ($registros as $r): ?>
<tr class="border-b border-slate-200 hover:bg-slate-50 transition">

  <td class="px-6 py-4 font-medium text-slate-800">
    <?= htmlspecialchars($r['numero_oficio']) ?>
  </td>

  <td class="px-6 py-4 text-slate-600">
    <?= htmlspecialchars($r['fecha']) ?>
  </td>

  <td class="px-6 py-4 text-slate-600">
    <?= htmlspecialchars($r['destinatario']) ?>
  </td>

  <td class="px-6 py-4 text-slate-600">
    <?= htmlspecialchars($r['archivado_en']) ?>
  </td>

  <td class="px-6 py-4">
    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
      <?php
        echo match (strtolower($r['estatus'])) {
          'enviado'   => 'bg-green-100 text-green-800',
          'pendiente' => 'bg-yellow-100 text-yellow-800',
          'urgente'   => 'bg-red-100 text-red-800',
          'archivado' => 'bg-gray-100 text-gray-800',
          default     => 'bg-blue-100 text-blue-800',
        };
      ?>">
      <?= htmlspecialchars($r['estatus']) ?>
    </span>
  </td>

  <td class="px-6 py-4 text-slate-600">
    <?= htmlspecialchars($r['registrado_por']) ?>
  </td>

  <td class="px-6 py-4 text-slate-600">
    <?= htmlspecialchars($r['concepto']) ?>
  </td>

  <td class="px-6 py-4">
    <a href="editar.php?id=<?= $r['id'] ?>"
       class="inline-flex items-center gap-2 px-4 py-2
              bg-blue-500 hover:bg-blue-600
              text-white text-sm font-semibold
              rounded-lg transition shadow">
      Editar
    </a>
  </td>

</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>

</div>
</div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
  const filtro = this.value.toLowerCase();
  const filas = document.querySelectorAll('#tablaMinutario tbody tr');

  filas.forEach(fila => {
    const texto = fila.textContent.toLowerCase();
    fila.style.display = texto.includes(filtro) ? '' : 'none';
  });
});
</script>

</body>
</html>
