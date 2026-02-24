<?php
require_once __DIR__ . '/BaseModel.php';

class Trabajador extends BaseModel {
    public function activos(): array {
        return $this->db->query('SELECT * FROM trabajadores WHERE activo = 1 AND deleted_at IS NULL ORDER BY nombre')->fetchAll();
    }

    public function all(): array {
        return $this->db->query('SELECT * FROM trabajadores WHERE deleted_at IS NULL ORDER BY nombre')->fetchAll();
    }
}
