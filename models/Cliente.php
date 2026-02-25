<?php
require_once __DIR__ . '/BaseModel.php';

class Cliente extends BaseModel {
    public function all(): array {
        return $this->db->query('SELECT * FROM clientes WHERE deleted_at IS NULL ORDER BY nombre')->fetchAll();
    }
}
