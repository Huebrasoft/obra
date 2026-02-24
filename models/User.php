<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    private $columnsCache = null;

    private function getColumns() {
        if ($this->columnsCache !== null) {
            return $this->columnsCache;
        }

        $columns = array();
        $stmt = $this->db->query('SHOW COLUMNS FROM usuarios');
        foreach ($stmt->fetchAll() as $col) {
            $columns[] = $col['Field'];
        }

        $this->columnsCache = $columns;
        return $columns;
    }

    private function existingColumns($candidates) {
        $columns = $this->getColumns();
        $out = array();
        foreach ($candidates as $col) {
            if (in_array($col, $columns, true)) {
                $out[] = $col;
            }
        }
        return $out;
    }

    public function findByUsername($username) {
        $username = trim((string)$username);
        if ($username === '') {
            return null;
        }

        // Buscar por múltiples columnas posibles y sin filtrar por activo/deleted
        // porque en algunos hostings esos campos tienen valores no estándar.
        $userCols = $this->existingColumns(array('username', 'usuario', 'email', 'user', 'login', 'nick', 'nombre'));
        if (empty($userCols)) {
            return null;
        }

        $conditions = array();
        $params = array();
        foreach ($userCols as $col) {
            $conditions[] = 'LOWER(TRIM(' . $col . ')) = LOWER(?)';
            $params[] = $username;
        }

        $sql = 'SELECT * FROM usuarios WHERE (' . implode(' OR ', $conditions) . ') ORDER BY id ASC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $user = $stmt->fetch();
        return $user ? $user : null;
    }

    public function getStoredPassword($user) {
        $candidates = array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass');
        foreach ($candidates as $field) {
            if (isset($user[$field]) && trim((string)$user[$field]) !== '') {
                return trim((string)$user[$field]);
            }
        }
        return '';
    }

    public function updatePasswordHash($userId, $newHash) {
        $passwordCols = $this->existingColumns(array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass'));
        if (empty($passwordCols)) {
            return false;
        }
        $passwordCol = $passwordCols[0];

        $updatedCols = $this->existingColumns(array('updated_at', 'modificado_en'));
        if (!empty($updatedCols)) {
            $sql = 'UPDATE usuarios SET ' . $passwordCol . ' = ?, ' . $updatedCols[0] . ' = NOW() WHERE id = ? LIMIT 1';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array($newHash, (int)$userId));
        }

        $sql = 'UPDATE usuarios SET ' . $passwordCol . ' = ? WHERE id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array($newHash, (int)$userId));
    }
}
