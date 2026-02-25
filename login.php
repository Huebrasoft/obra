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
    <title>Login | HuebraSoft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
<main class="auth-card">
    <div class="brand"><span class="logo">H</span> HuebraSoft</div>
    <h1>Acceso interno</h1>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" class="stack">
        <div>
            <label for="identifier">Usuario o email</label>
            <input type="text" id="identifier" name="identifier" required>
        </div>

        <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn-primary">Entrar</button>
    </form>
</main>
</body>
</html>
