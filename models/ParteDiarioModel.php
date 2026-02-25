<?php
require_once __DIR__ . '/../config/db.php';

class ParteDiarioModel
{
    public function getFormData(): array
    {
        $pdo = getPDO();

        $proyectos = $pdo->query("SELECT id, nombre FROM proyectos WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();
        $trabajadores = $pdo->query("SELECT id, nombre, coste_hora FROM trabajadores WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();
        $materiales = $pdo->query("SELECT id, nombre, unidad, precio_referencia FROM materiales WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();
        $maquinaria = $pdo->query("SELECT id, nombre, coste_hora_referencia FROM maquinaria WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();
        $tareas = $pdo->query("SELECT id, proyecto_id, nombre FROM tareas_proyecto ORDER BY nombre ASC")->fetchAll();

        return [
            'proyectos' => $proyectos,
            'trabajadores' => $trabajadores,
            'materiales' => $materiales,
            'maquinaria' => $maquinaria,
            'tareas' => $tareas,
        ];
    }

    public function create(array $data, int $userId): int
    {
        $pdo = getPDO();
        $pdo->beginTransaction();

        try {
            $stmtParte = $pdo->prepare(
                'INSERT INTO partes_diarios (proyecto_id, tarea_id, fecha, notas, creado_por_usuario_id)
                 VALUES (:proyecto_id, :tarea_id, :fecha, :notas, :usuario_id)'
            );

            $stmtParte->execute([
                'proyecto_id' => $data['proyecto_id'],
                'tarea_id' => $data['tarea_id'] ?: null,
                'fecha' => $data['fecha'],
                'notas' => $data['notas'],
                'usuario_id' => $userId,
            ]);

            $parteId = (int)$pdo->lastInsertId();

            $stmtTrabajador = $pdo->prepare(
                'INSERT INTO parte_trabajadores (parte_diario_id, trabajador_id, horas, coste_hora_snapshot)
                 VALUES (:parte_diario_id, :trabajador_id, :horas, :coste_hora_snapshot)'
            );

            foreach ($data['trabajadores'] as $item) {
                $stmtTrabajador->execute([
                    'parte_diario_id' => $parteId,
                    'trabajador_id' => $item['trabajador_id'],
                    'horas' => $item['horas'],
                    'coste_hora_snapshot' => $item['coste_hora_snapshot'],
                ]);
            }

            $stmtMaterial = $pdo->prepare(
                'INSERT INTO parte_materiales (parte_diario_id, material_id, cantidad, precio_unitario_snapshot)
                 VALUES (:parte_diario_id, :material_id, :cantidad, :precio_unitario_snapshot)'
            );

            foreach ($data['materiales'] as $item) {
                $stmtMaterial->execute([
                    'parte_diario_id' => $parteId,
                    'material_id' => $item['material_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_snapshot' => $item['precio_unitario_snapshot'],
                ]);
            }

            $stmtMaquinaria = $pdo->prepare(
                'INSERT INTO parte_maquinaria (parte_diario_id, maquinaria_id, horas, coste_hora_snapshot)
                 VALUES (:parte_diario_id, :maquinaria_id, :horas, :coste_hora_snapshot)'
            );

            foreach ($data['maquinaria'] as $item) {
                $stmtMaquinaria->execute([
                    'parte_diario_id' => $parteId,
                    'maquinaria_id' => $item['maquinaria_id'],
                    'horas' => $item['horas'],
                    'coste_hora_snapshot' => $item['coste_hora_snapshot'],
                ]);
            }

            $pdo->commit();
            return $parteId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
