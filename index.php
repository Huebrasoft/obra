<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/ParteController.php';
require_once __DIR__ . '/controllers/InformeController.php';

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'parte_nuevo':
        (new ParteController())->nuevo();
        break;
    case 'parte_guardar_ajax':
        (new ParteController())->guardarAjax();
        break;
    case 'informe_trabajador':
        (new InformeController())->trabajadorMensual();
        break;
    case 'informe_proyecto':
        (new InformeController())->proyectoCompleto();
        break;
    case 'dashboard':
    default:
        (new HomeController())->dashboard();
        break;
}
