<?php
require_once __DIR__ . '/includes/session_middleware.php';
require_once __DIR__ . '/controllers/AuthController.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $error = 'Completa usuario/email y contraseña.';
    } else {
        $authController = new AuthController();
        if ($authController->login($identifier, $password)) {
            header('Location: index.php');
            exit;
        }
        $error = 'Credenciales inválidas.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Obra App</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="container auth-container">
    <h1>Obra App</h1>
    <p>Acceso interno</p>

    <?php if ($error !== ''): ?>
        <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" class="card">
        <label for="identifier">Usuario o email</label>
        <input type="text" id="identifier" name="identifier" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>
</main>
</body>
</html>
