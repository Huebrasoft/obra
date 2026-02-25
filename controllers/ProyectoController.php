<?php
require_once __DIR__ . '/../models/ProyectoModel.php';

class ProyectoController
{
    private ProyectoModel $model;

    public function __construct()
    {
        $this->model = new ProyectoModel();
    }

    public function create(array $input): array
    {
        try {
            $proyectoId = $this->model->createProyecto($input);
            return [
                'ok' => true,
                'message' => 'Obra creada correctamente.',
                'proyecto_id' => $proyectoId,
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage() ?: 'No se pudo crear la obra.',
            ];
        }
    }
}
