<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    public function findByUsername($username) {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE username = ? AND deleted_at IS NULL LIMIT 1');
        $stmt->execute(array(trim($username)));
        $user = $stmt->fetch();
        return $user ? $user : null;
    }

    public function updatePasswordHash($userId, $newHash) {
        $stmt = $this->db->prepare('UPDATE usuarios SET password_hash = ?, updated_at = NOW() WHERE id = ? LIMIT 1');
        return $stmt->execute(array($newHash, (int)$userId));
    }
}
