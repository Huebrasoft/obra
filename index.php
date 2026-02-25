<?php
require_once __DIR__ . '/includes/session_middleware.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/ParteDiarioController.php';

$page = $_GET['page'] ?? 'dashboard';

if ($page === 'logout') {
    requireLogin();
    $authController = new AuthController();
    $authController->logout();
    header('Location: login.php');
    exit;
}

requireLogin();

switch ($page) {
    case 'dashboard':
        (new HomeController())->dashboard();
        break;

    case 'nuevo-parte':
        requireRole(['admin', 'operario']);
        (new ParteDiarioController())->createView();
        break;

    case 'usuarios':
        requireRole(['admin']);
        $title = 'Usuarios';
        include __DIR__ . '/views/usuarios.php';
        break;

    case 'mi-perfil':
        requireRole(['admin', 'operario']);
        $title = 'Mi perfil';
        include __DIR__ . '/views/perfil.php';
        break;

    default:
        http_response_code(404);
        include __DIR__ . '/views/errors/404.php';
        break;
}
