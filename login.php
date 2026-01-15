<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
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

  <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Iniciar sesión</h2>

    <?php if (isset($_GET['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
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
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold"
      >
        Entrar
      </button>
    </form>
  </div>

</body>
</html>
