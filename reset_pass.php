<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-slate-100">

  <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Cambiar contraseña</h2>

    <?php if (isset($_GET['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= htmlspecialchars($_GET['error']) ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
      <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        Contraseña actualizada correctamente
      </div>
    <?php endif; ?>

    <form method="POST" action="processes_pass.php" class="space-y-4">
      
      <div>
        <label class="block text-sm font-semibold mb-1">Contraseña actual</label>
        <input
          type="password"
          name="password_actual"
          required
          class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200"
        >
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Nueva contraseña</label>
        <input
          type="password"
          name="password_nueva"
          required
          class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200"
        >
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Confirmar nueva contraseña</label>
        <input
          type="password"
          name="password_confirmar"
          required
          class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200"
        >
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold"
      >
        Cambiar contraseña
      </button>
    </form>
  </div>

<?php if (isset($_GET['success'])): ?>
<div
  id="successModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
>
  <div
    class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform scale-95 transition-all duration-300"
  >
    <div class="text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <h3 class="text-lg font-semibold text-slate-800">
        Contraseña actualizada
      </h3>

      <p class="text-sm text-slate-600 mt-2">
        Por seguridad, tu sesión se cerrará.
      </p>

      <button
        id="closeModalBtn"
        class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition"
      >
        Aceptar
      </button>
    </div>
  </div>
</div>

    <script>
    const modal = document.getElementById('successModal');
    const btn = document.getElementById('closeModalBtn');

    setTimeout(() => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.querySelector('div').classList.remove('scale-95');
    }, 50);

    btn.addEventListener('click', () => {
        modal.querySelector('div').classList.add('scale-95');
        modal.classList.add('opacity-0');

        setTimeout(() => {
        window.location.href = 'logout.php';
        }, 300);
    });
    </script>
    <?php endif; ?>


</body>
</html>
