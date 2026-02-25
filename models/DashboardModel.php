<?php
require_once __DIR__ . '/../config/db.php';

class DashboardModel
{
    public function getDashboardData(): array
    {
        $pdo = getPDO();

        $metrics = [
            'en_curso' => 0,
            'proximas' => 0,
            'pausadas' => 0,
            'trabajadores_activos' => 0,
        ];

        $stmtCounts = $pdo->query(
            "SELECT estado, COUNT(*) total
             FROM proyectos
             WHERE activo = 1
             GROUP BY estado"
        );

        foreach ($stmtCounts->fetchAll() as $row) {
            if ($row['estado'] === 'En curso') {
                $metrics['en_curso'] = (int)$row['total'];
            } elseif ($row['estado'] === 'Proximo') {
                $metrics['proximas'] = (int)$row['total'];
            } elseif ($row['estado'] === 'Pausado') {
                $metrics['pausadas'] = (int)$row['total'];
            }
        }

        $stmtTrabajadores = $pdo->query("SELECT COUNT(*) total FROM trabajadores WHERE activo = 1");
        $metrics['trabajadores_activos'] = (int)$stmtTrabajadores->fetchColumn();

        $projectsSql =
            "SELECT
                p.id,
                p.nombre,
                p.estado,
                p.fecha_inicio,
                c.nombre AS cliente_nombre,
                COALESCE(SUM(pt.horas), 0) AS horas_totales
             FROM proyectos p
             LEFT JOIN clientes c ON c.id = p.cliente_id
             LEFT JOIN partes_diarios pd ON pd.proyecto_id = p.id
             LEFT JOIN parte_trabajadores pt ON pt.parte_diario_id = pd.id
             WHERE p.activo = 1
             GROUP BY p.id, p.nombre, p.estado, p.fecha_inicio, c.nombre
             ORDER BY
                CASE p.estado
                    WHEN 'En curso' THEN 1
                    WHEN 'Proximo' THEN 2
                    WHEN 'Pausado' THEN 3
                    ELSE 4
                END,
                p.id DESC
             LIMIT 12";

        $projects = $pdo->query($projectsSql)->fetchAll();

        $clientes = $pdo->query("SELECT id, nombre FROM clientes WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();

        return [
            'metrics' => $metrics,
            'projects' => $projects,
            'clientes' => $clientes,
        ];
    }
}
