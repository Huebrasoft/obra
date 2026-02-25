<?php
require_once __DIR__ . '/../config/db.php';

class ProyectoModel
{
    public function createProyecto(array $input): int
    {
        $pdo = getPDO();
        $pdo->beginTransaction();

        try {
            $nombre = trim($input['nombre'] ?? '');
            $estado = trim($input['estado'] ?? 'Proximo');
            $fechaInicio = trim($input['fecha_inicio'] ?? '');
            $ubicacion = trim($input['ubicacion'] ?? '');
            $descripcion = trim($input['descripcion'] ?? '');
            $clienteId = (int)($input['cliente_id'] ?? 0);
            $clienteNuevoNombre = trim($input['cliente_nuevo_nombre'] ?? '');

            if ($nombre === '') {
                throw new RuntimeException('El nombre de la obra es obligatorio.');
            }

            if (!in_array($estado, ['Proximo', 'En curso', 'Pausado'], true)) {
                $estado = 'Proximo';
            }

            if ($fechaInicio !== '') {
                $date = DateTime::createFromFormat('Y-m-d', $fechaInicio);
                if (!$date || $date->format('Y-m-d') !== $fechaInicio) {
                    throw new RuntimeException('La fecha de inicio no es válida.');
                }
            } else {
                $fechaInicio = null;
            }

            if ($clienteId <= 0) {
                if ($clienteNuevoNombre === '') {
                    throw new RuntimeException('Debes seleccionar un cliente o crear uno nuevo.');
                }

                $stmtCliente = $pdo->prepare(
                    'INSERT INTO clientes (nombre, activo) VALUES (:nombre, 1)'
                );
                $stmtCliente->execute(['nombre' => $clienteNuevoNombre]);
                $clienteId = (int)$pdo->lastInsertId();
            } else {
                $stmtValidaCliente = $pdo->prepare('SELECT id FROM clientes WHERE id = :id AND activo = 1 LIMIT 1');
                $stmtValidaCliente->execute(['id' => $clienteId]);
                if (!$stmtValidaCliente->fetch()) {
                    throw new RuntimeException('El cliente seleccionado no existe o está inactivo.');
                }
            }

            $descripcionFinal = $descripcion;
            if ($ubicacion !== '') {
                $descripcionFinal = trim('Ubicación: ' . $ubicacion . ($descripcionFinal !== '' ? ' | ' . $descripcionFinal : ''));
            }

            $stmtProyecto = $pdo->prepare(
                'INSERT INTO proyectos (cliente_id, nombre, descripcion, fecha_inicio, estado, activo)
                 VALUES (:cliente_id, :nombre, :descripcion, :fecha_inicio, :estado, 1)'
            );

            $stmtProyecto->execute([
                'cliente_id' => $clienteId,
                'nombre' => $nombre,
                'descripcion' => $descripcionFinal !== '' ? $descripcionFinal : null,
                'fecha_inicio' => $fechaInicio,
                'estado' => $estado,
            ]);

            $proyectoId = (int)$pdo->lastInsertId();
            $pdo->commit();

            return $proyectoId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
