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

    private function firstExistingColumn($candidates, $default = null) {
        $columns = $this->getColumns();
        foreach ($candidates as $col) {
            if (in_array($col, $columns, true)) {
                return $col;
            }
        }
        return $default;
    }

    public function findByUsername($username) {
        $username = trim((string)$username);
        if ($username === '') {
            return null;
        }

        $userColumn = $this->firstExistingColumn(array('username', 'usuario', 'email', 'user'), 'username');
        $activeColumn = $this->firstExistingColumn(array('activo', 'active', 'estado'));
        $deletedColumn = $this->firstExistingColumn(array('deleted_at', 'borrado_en'));

        $sql = 'SELECT * FROM usuarios WHERE ' . $userColumn . ' = ?';
        $params = array($username);

        if ($activeColumn !== null) {
            if ($activeColumn === 'estado') {
                $sql .= " AND " . $activeColumn . " IN ('1','activo','ACTIVO')";
            } else {
                $sql .= ' AND ' . $activeColumn . ' = 1';
            }
        }

        if ($deletedColumn !== null) {
            $sql .= ' AND ' . $deletedColumn . ' IS NULL';
        }

        $sql .= ' LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();
        return $user ? $user : null;
    }

    public function getStoredPassword($user) {
        $candidates = array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass');
        foreach ($candidates as $field) {
            if (isset($user[$field]) && (string)$user[$field] !== '') {
                return (string)$user[$field];
            }
        }
        return '';
    }

    public function updatePasswordHash($userId, $newHash) {
        $passwordCol = $this->firstExistingColumn(array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass'));
        if ($passwordCol === null) {
            return false;
        }

        $updatedCol = $this->firstExistingColumn(array('updated_at', 'modificado_en'));
        if ($updatedCol !== null) {
            $sql = 'UPDATE usuarios SET ' . $passwordCol . ' = ?, ' . $updatedCol . ' = NOW() WHERE id = ? LIMIT 1';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array($newHash, (int)$userId));
        }

        $sql = 'UPDATE usuarios SET ' . $passwordCol . ' = ? WHERE id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array($newHash, (int)$userId));
    }
}
