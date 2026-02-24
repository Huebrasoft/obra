<?php
require_once __DIR__ . '/BaseModel.php';

class Material extends BaseModel {
    public function all(): array {
        return $this->db->query('SELECT * FROM materiales WHERE deleted_at IS NULL ORDER BY nombre')->fetchAll();
    }
}
