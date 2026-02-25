<?php
require_once __DIR__ . '/../models/ParteDiarioModel.php';

class ParteDiarioController
{
    private ParteDiarioModel $model;

    public function __construct()
    {
        $this->model = new ParteDiarioModel();
    }

    public function createView(): void
    {
        $title = 'Nuevo parte diario';
        $data = $this->model->getFormData();
        include __DIR__ . '/../views/partes/crear.php';
    }

    public function save(array $input, int $userId): array
    {
        $validation = $this->validate($input);
        if ($validation['ok'] === false) {
            return $validation;
        }

        try {
            $parteId = $this->model->create($validation['data'], $userId);
            return ['ok' => true, 'message' => 'Parte guardado correctamente.', 'parte_id' => $parteId];
        } catch (Throwable $e) {
            return ['ok' => false, 'message' => 'No se pudo guardar el parte.'];
        }
    }

    private function validate(array $input): array
    {
        $proyectoId = (int)($input['proyecto_id'] ?? 0);
        $fecha = trim($input['fecha'] ?? '');
        $tareaId = (int)($input['tarea_id'] ?? 0);
        $notas = trim($input['notas'] ?? '');

        if ($proyectoId <= 0 || $fecha === '') {
            return ['ok' => false, 'message' => 'Proyecto y fecha son obligatorios.'];
        }

        $trabajadores = [];
        foreach (($input['trabajadores'] ?? []) as $row) {
            $trabajadorId = (int)($row['trabajador_id'] ?? 0);
            $horas = (float)($row['horas'] ?? 0);
            $coste = (float)($row['coste_hora_snapshot'] ?? 0);
            if ($trabajadorId > 0 && $horas > 0) {
                $trabajadores[] = [
                    'trabajador_id' => $trabajadorId,
                    'horas' => $horas,
                    'coste_hora_snapshot' => max(0, $coste),
                ];
            }
        }

        $materiales = [];
        foreach (($input['materiales'] ?? []) as $row) {
            $materialId = (int)($row['material_id'] ?? 0);
            $cantidad = (float)($row['cantidad'] ?? 0);
            $precio = (float)($row['precio_unitario_snapshot'] ?? 0);
            if ($materialId > 0 && $cantidad > 0) {
                $materiales[] = [
                    'material_id' => $materialId,
                    'cantidad' => $cantidad,
                    'precio_unitario_snapshot' => max(0, $precio),
                ];
            }
        }

        $maquinaria = [];
        foreach (($input['maquinaria'] ?? []) as $row) {
            $maquinariaId = (int)($row['maquinaria_id'] ?? 0);
            $horas = (float)($row['horas'] ?? 0);
            $coste = (float)($row['coste_hora_snapshot'] ?? 0);
            if ($maquinariaId > 0 && $horas > 0) {
                $maquinaria[] = [
                    'maquinaria_id' => $maquinariaId,
                    'horas' => $horas,
                    'coste_hora_snapshot' => max(0, $coste),
                ];
            }
        }

        if (!$trabajadores && !$materiales && !$maquinaria) {
            return ['ok' => false, 'message' => 'Añade al menos un registro (trabajador, material o maquinaria).'];
        }

        return [
            'ok' => true,
            'data' => [
                'proyecto_id' => $proyectoId,
                'fecha' => $fecha,
                'tarea_id' => $tareaId,
                'notas' => $notas,
                'trabajadores' => $trabajadores,
                'materiales' => $materiales,
                'maquinaria' => $maquinaria,
            ],
        ];
    }
}
