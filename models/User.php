<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE username = ? AND deleted_at IS NULL LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
