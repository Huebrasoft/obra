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
            $this->assertTaskProject($pdo, $data['tarea_id'], $data['proyecto_id']);

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

            $trabajadorCostes = $this->loadIndexedDecimal(
                $pdo,
                'SELECT id, coste_hora FROM trabajadores WHERE id IN (%s)',
                array_column($data['trabajadores'], 'trabajador_id'),
                'coste_hora'
            );

            $materialPrecios = $this->loadIndexedDecimal(
                $pdo,
                'SELECT id, precio_referencia FROM materiales WHERE id IN (%s)',
                array_column($data['materiales'], 'material_id'),
                'precio_referencia'
            );

            $maquinariaCostes = $this->loadIndexedDecimal(
                $pdo,
                'SELECT id, coste_hora_referencia FROM maquinaria WHERE id IN (%s)',
                array_column($data['maquinaria'], 'maquinaria_id'),
                'coste_hora_referencia'
            );

            $stmtTrabajador = $pdo->prepare(
                'INSERT INTO parte_trabajadores (parte_diario_id, trabajador_id, horas, coste_hora_snapshot)
                 VALUES (:parte_diario_id, :trabajador_id, :horas, :coste_hora_snapshot)'
            );

            foreach ($data['trabajadores'] as $item) {
                $snapshot = $trabajadorCostes[$item['trabajador_id']] ?? 0;
                $stmtTrabajador->execute([
                    'parte_diario_id' => $parteId,
                    'trabajador_id' => $item['trabajador_id'],
                    'horas' => $item['horas'],
                    'coste_hora_snapshot' => $snapshot,
                ]);
            }

            $stmtMaterial = $pdo->prepare(
                'INSERT INTO parte_materiales (parte_diario_id, material_id, cantidad, precio_unitario_snapshot)
                 VALUES (:parte_diario_id, :material_id, :cantidad, :precio_unitario_snapshot)'
            );

            foreach ($data['materiales'] as $item) {
                $snapshot = $materialPrecios[$item['material_id']] ?? 0;
                $stmtMaterial->execute([
                    'parte_diario_id' => $parteId,
                    'material_id' => $item['material_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_snapshot' => $snapshot,
                ]);
            }

            $stmtMaquinaria = $pdo->prepare(
                'INSERT INTO parte_maquinaria (parte_diario_id, maquinaria_id, horas, coste_hora_snapshot)
                 VALUES (:parte_diario_id, :maquinaria_id, :horas, :coste_hora_snapshot)'
            );

            foreach ($data['maquinaria'] as $item) {
                $snapshot = $maquinariaCostes[$item['maquinaria_id']] ?? 0;
                $stmtMaquinaria->execute([
                    'parte_diario_id' => $parteId,
                    'maquinaria_id' => $item['maquinaria_id'],
                    'horas' => $item['horas'],
                    'coste_hora_snapshot' => $snapshot,
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

    private function assertTaskProject(PDO $pdo, int $tareaId, int $proyectoId): void
    {
        if ($tareaId <= 0) {
            return;
        }

        $stmt = $pdo->prepare('SELECT id FROM tareas_proyecto WHERE id = :tarea_id AND proyecto_id = :proyecto_id LIMIT 1');
        $stmt->execute([
            'tarea_id' => $tareaId,
            'proyecto_id' => $proyectoId,
        ]);

        if (!$stmt->fetch()) {
            throw new RuntimeException('La tarea no pertenece al proyecto.');
        }
    }

    private function loadIndexedDecimal(PDO $pdo, string $sqlTemplate, array $ids, string $field): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        $ids = array_filter($ids, static fn ($id) => $id > 0);

        if (!$ids) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = sprintf($sqlTemplate, $placeholders);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        $rows = $stmt->fetchAll();

        $map = [];
        foreach ($rows as $row) {
            $map[(int)$row['id']] = (float)$row[$field];
        }

        return $map;
    }
}
