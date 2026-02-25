<?php
require_once __DIR__ . '/../models/Proyecto.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../models/Maquinaria.php';
require_once __DIR__ . '/../models/ParteDiario.php';

class ParteController {
    public function nuevo(): void {
        requireLogin();
        $proyectos = (new Proyecto())->activos();
        $trabajadores = (new Trabajador())->activos();
        $materiales = (new Material())->all();
        $maquinaria = (new Maquinaria())->all();
        include __DIR__ . '/../views/partes/nuevo.php';
    }

    public function guardarAjax(): void {
        requireLogin();
        header('Content-Type: application/json');

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'error' => 'Datos inválidos.']);
            return;
        }

        $proyectoId = (int)($payload['proyecto_id'] ?? 0);
        $fecha = $payload['fecha'] ?? '';
        if ($proyectoId <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'error' => 'Proyecto y fecha son obligatorios.']);
            return;
        }

        $db = Database::getConnection();
        $data = [
            'proyecto_id' => $proyectoId,
            'fecha' => $fecha,
            'notas' => trim($payload['notas'] ?? ''),
            'tarea' => trim($payload['tarea'] ?? ''),
            'creado_por' => authUser()['id'],
            'trabajadores' => [],
            'materiales' => [],
            'maquinaria' => [],
        ];

        foreach (($payload['trabajadores'] ?? []) as $item) {
            $id = (int)($item['trabajador_id'] ?? 0);
            $horas = (float)($item['horas'] ?? 0);
            if ($id > 0 && $horas > 0) {
                $st = $db->prepare('SELECT coste_hora FROM trabajadores WHERE id = ?');
                $st->execute([$id]);
                $coste = (float)($st->fetchColumn() ?: 0);
                $data['trabajadores'][] = ['trabajador_id' => $id, 'horas' => $horas, 'coste_hora_snapshot' => $coste];
            }
        }

        foreach (($payload['materiales'] ?? []) as $item) {
            $id = (int)($item['material_id'] ?? 0);
            $cantidad = (float)($item['cantidad'] ?? 0);
            if ($id > 0 && $cantidad > 0) {
                $st = $db->prepare('SELECT COALESCE(coste_unitario, 0) FROM materiales WHERE id = ?');
                $st->execute([$id]);
                $coste = (float)($st->fetchColumn() ?: 0);
                $data['materiales'][] = ['material_id' => $id, 'cantidad' => $cantidad, 'coste_unitario_snapshot' => $coste];
            }
        }

        foreach (($payload['maquinaria'] ?? []) as $item) {
            $id = (int)($item['maquinaria_id'] ?? 0);
            $horas = (float)($item['horas'] ?? 0);
            if ($id > 0 && $horas > 0) {
                $st = $db->prepare('SELECT coste_hora FROM maquinaria WHERE id = ?');
                $st->execute([$id]);
                $coste = (float)($st->fetchColumn() ?: 0);
                $data['maquinaria'][] = ['maquinaria_id' => $id, 'horas' => $horas, 'coste_hora_snapshot' => $coste];
            }
        }

        try {
            $parteId = (new ParteDiario())->crear($data);
            echo json_encode(['ok' => true, 'parte_id' => $parteId]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Error guardando parte diario.']);
        }
    }
}
