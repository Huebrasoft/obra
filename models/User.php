<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    private $columnsCache = null;

    private function getColumnsMeta() {
        if ($this->columnsCache !== null) {
            return $this->columnsCache;
        }

        $meta = array();
        $stmt = $this->db->query('SHOW COLUMNS FROM usuarios');
        foreach ($stmt->fetchAll() as $col) {
            $meta[] = $col;
        }
        $this->columnsCache = $meta;
        return $meta;
    }

    private function getColumns() {
        $out = array();
        foreach ($this->getColumnsMeta() as $col) {
            $out[] = $col['Field'];
        }
        return $out;
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

    private function detectLoginColumns() {
        $preferred = $this->existingColumns(array('username', 'usuario', 'email', 'user', 'login', 'nick', 'nombre', 'name'));
        if (!empty($preferred)) {
            return $preferred;
        }

        // Fallback: cualquier columna de texto que pueda contener el login
        $detected = array();
        foreach ($this->getColumnsMeta() as $col) {
            $field = $col['Field'];
            $type = strtolower((string)$col['Type']);
            if (strpos($type, 'char') !== false || strpos($type, 'text') !== false) {
                $detected[] = $field;
            }
        }
        return $detected;
    }

    public function findByUsername($username) {
        $username = trim((string)$username);
        if ($username === '') {
            return null;
        }

        $userCols = $this->detectLoginColumns();
        if (empty($userCols)) {
            return null;
        }

        $conditions = array();
        $params = array();
        foreach ($userCols as $col) {
            $conditions[] = 'LOWER(TRIM(CAST(' . $col . ' AS CHAR))) = LOWER(?)';
            $params[] = $username;
        }

        $sql = 'SELECT * FROM usuarios WHERE (' . implode(' OR ', $conditions) . ') ORDER BY id ASC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();

        if ($user) {
            return $user;
        }

        // Último fallback: búsqueda exacta sin lower/trim por si hay collation extraña
        $conditions = array();
        $params = array();
        foreach ($userCols as $col) {
            $conditions[] = $col . ' = ?';
            $params[] = $username;
        }
        $sql = 'SELECT * FROM usuarios WHERE (' . implode(' OR ', $conditions) . ') ORDER BY id ASC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();

        return $user ? $user : null;
    }

    public function getStoredPassword($user) {
        $candidates = array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass', 'contrasenya');
        foreach ($candidates as $field) {
            if (isset($user[$field]) && trim((string)$user[$field]) !== '') {
                return trim((string)$user[$field]);
            }
        }

        // Fallback: detectar cualquier campo de texto con nombre de contraseña
        foreach ($user as $key => $value) {
            $k = strtolower((string)$key);
            if (strpos($k, 'pass') !== false || strpos($k, 'clave') !== false || strpos($k, 'contra') !== false) {
                if (trim((string)$value) !== '') {
                    return trim((string)$value);
                }
            }
        }

        return '';
    }

    public function updatePasswordHash($userId, $newHash) {
        $passwordCols = $this->existingColumns(array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass', 'contrasenya'));
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
