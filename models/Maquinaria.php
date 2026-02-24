<?php
require_once __DIR__ . '/BaseModel.php';

class Maquinaria extends BaseModel {
    public function all(): array {
        return $this->db->query('SELECT * FROM maquinaria WHERE deleted_at IS NULL ORDER BY nombre')->fetchAll();
    }
}
