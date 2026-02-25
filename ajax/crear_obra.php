<?php
require_once __DIR__ . '/../includes/session_middleware.php';
require_once __DIR__ . '/../controllers/ProyectoController.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión no válida.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Payload inválido.']);
    exit;
}

$controller = new ProyectoController();
$result = $controller->create($input);

if (!$result['ok']) {
    http_response_code(422);
}

echo json_encode($result);
