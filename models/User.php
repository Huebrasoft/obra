<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    private $columnsCache = null;
    private $tableNameCache = null;
    private $primaryKeyCache = null;

    private function tableName() {
        if ($this->tableNameCache !== null) {
            return $this->tableNameCache;
        }

        $candidates = array('usuarios', 'users', 'usuario', 'tb_usuarios', 'tbl_usuarios');
        foreach ($candidates as $table) {
            $stmt = $this->db->prepare('SHOW TABLES LIKE ?');
            $stmt->execute(array($table));
            if ($stmt->fetchColumn()) {
                $this->tableNameCache = $table;
                return $this->tableNameCache;
            }
        }

        // Fallback por compatibilidad con el esquema original
        $this->tableNameCache = 'usuarios';
        return $this->tableNameCache;
    }

    private function getColumnsMeta() {
        if ($this->columnsCache !== null) {
            return $this->columnsCache;
        }

        $meta = array();
        $table = $this->tableName();
        $stmt = $this->db->query('SHOW COLUMNS FROM `' . $table . '`');
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

    private function detectPrimaryKey() {
        if ($this->primaryKeyCache !== null) {
            return $this->primaryKeyCache;
        }

        foreach ($this->getColumnsMeta() as $col) {
            if (isset($col['Key']) && $col['Key'] === 'PRI') {
                $this->primaryKeyCache = $col['Field'];
                return $this->primaryKeyCache;
            }
        }

        $candidates = $this->existingColumns(array('id', 'user_id', 'usuario_id'));
        $this->primaryKeyCache = !empty($candidates) ? $candidates[0] : 'id';
        return $this->primaryKeyCache;
    }

    private function detectLoginColumns() {
        $preferred = $this->existingColumns(array('username', 'usuario', 'email', 'user', 'login', 'nick', 'nombre', 'name'));
        if (!empty($preferred)) {
            return $preferred;
        }

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

        $table = $this->tableName();
        $userCols = $this->detectLoginColumns();
        if (empty($userCols)) {
            return null;
        }

        $conditions = array();
        $params = array();
        foreach ($userCols as $col) {
            $conditions[] = 'LOWER(TRIM(CAST(`' . $col . '` AS CHAR))) = LOWER(?)';
            $params[] = $username;
        }

        $sql = 'SELECT * FROM `' . $table . '` WHERE (' . implode(' OR ', $conditions) . ') LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();

        if ($user) {
            return $user;
        }

        $conditions = array();
        $params = array();
        foreach ($userCols as $col) {
            $conditions[] = '`' . $col . '` = ?';
            $params[] = $username;
        }

        $sql = 'SELECT * FROM `' . $table . '` WHERE (' . implode(' OR ', $conditions) . ') LIMIT 1';
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

    public function getUserId($user) {
        $pk = $this->detectPrimaryKey();
        if (isset($user[$pk])) {
            return (int)$user[$pk];
        }

        foreach (array('id', 'user_id', 'usuario_id') as $k) {
            if (isset($user[$k])) {
                return (int)$user[$k];
            }
        }
        return 0;
    }

    public function updatePasswordHash($userId, $newHash) {
        $table = $this->tableName();
        $pk = $this->detectPrimaryKey();
        $passwordCols = $this->existingColumns(array('password_hash', 'password', 'clave', 'contrasena', 'passwd', 'pass', 'contrasenya'));
        if (empty($passwordCols)) {
            return false;
        }
        $passwordCol = $passwordCols[0];

        $updatedCols = $this->existingColumns(array('updated_at', 'modificado_en'));
        if (!empty($updatedCols)) {
            $sql = 'UPDATE `' . $table . '` SET `' . $passwordCol . '` = ?, `' . $updatedCols[0] . '` = NOW() WHERE `' . $pk . '` = ? LIMIT 1';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array($newHash, (int)$userId));
        }

        $sql = 'UPDATE `' . $table . '` SET `' . $passwordCol . '` = ? WHERE `' . $pk . '` = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array($newHash, (int)$userId));
    }
}
