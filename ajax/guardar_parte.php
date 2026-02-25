<?php
require_once __DIR__ . '/../includes/session_middleware.php';
require_once __DIR__ . '/../controllers/ParteDiarioController.php';

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

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Datos inválidos.']);
    exit;
}

$controller = new ParteDiarioController();
$result = $controller->save($input, (int)$_SESSION['user']['id']);

if (!$result['ok']) {
    http_response_code(422);
}

echo json_encode($result);
