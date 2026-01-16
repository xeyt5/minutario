<?php
session_start();

if (isset($_SESSION['usuario_id']) && !isset($_GET['success'])) {
  header("Location: tabla.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Minutario</title>

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-slate-100">

  <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md relative">
    <h2 class="text-2xl font-bold text-center mb-6">
      Iniciar sesión
    </h2>

    <?php if (isset($_GET['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
        Usuario o contraseña incorrectos
      </div>
    <?php endif; ?>

    <form method="POST" action="procesar_login.php" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold mb-1">Usuario</label>
        <input
          type="text"
          name="usuario"
          required
          class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200"
        >
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Contraseña</label>
        <input
          type="password"
          name="password"
          required
          class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200"
        >
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
        Entrar
      </button>
    </form>
  </div>

  <?php if (isset($_GET['success'])): ?>
  <div id="successModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center
           opacity-0 pointer-events-none transition">

    <div class="bg-white rounded-xl p-6 w-96 text-center
                transform scale-95 transition">
      <h2 class="text-2xl font-bold mb-3">Bienvenido <?= htmlspecialchars($_SESSION['usuario'] ?? '') ?></h2>
      <p class="text-slate-600 mb-6">
        Inicio de sesión correcto 
      </p>

      <button id="closeModalBtn"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
        Continuar
      </button>
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
        window.location.href = 'tabla.php';
      }, 300);
    });
  </script>
  <?php endif; ?>

</body>
</html>
