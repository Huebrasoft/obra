<?php
require_once __DIR__ . '/BaseModel.php';

class Proyecto extends BaseModel {
    public function activos(): array {
        $sql = "SELECT p.*, c.nombre AS cliente_nombre
                FROM proyectos p
                INNER JOIN clientes c ON c.id = p.cliente_id
                WHERE p.deleted_at IS NULL AND p.estado IN ('Próximo','En curso','Pausado')
                ORDER BY p.fecha_inicio_prevista ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function allWithCliente(): array {
        $sql = 'SELECT p.*, c.nombre AS cliente_nombre FROM proyectos p JOIN clientes c ON c.id = p.cliente_id WHERE p.deleted_at IS NULL ORDER BY p.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare('SELECT p.*, c.nombre cliente_nombre FROM proyectos p JOIN clientes c ON c.id=p.cliente_id WHERE p.id=? AND p.deleted_at IS NULL');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
