<?php
require_once __DIR__ . '/BaseModel.php';

class ParteDiario extends BaseModel {
    public function crear(array $data): int {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('INSERT INTO partes_diarios (proyecto_id, fecha, notas, tarea, creado_por) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$data['proyecto_id'], $data['fecha'], $data['notas'], $data['tarea'], $data['creado_por']]);
            $parteId = (int)$this->db->lastInsertId();

            if (!empty($data['trabajadores'])) {
                $sqlT = 'INSERT INTO parte_trabajadores (parte_id, trabajador_id, horas, coste_hora_snapshot) VALUES (?, ?, ?, ?)';
                $st = $this->db->prepare($sqlT);
                foreach ($data['trabajadores'] as $t) {
                    $st->execute([$parteId, $t['trabajador_id'], $t['horas'], $t['coste_hora_snapshot']]);
                }
            }

            if (!empty($data['materiales'])) {
                $sqlM = 'INSERT INTO parte_materiales (parte_id, material_id, cantidad, coste_unitario_snapshot) VALUES (?, ?, ?, ?)';
                $sm = $this->db->prepare($sqlM);
                foreach ($data['materiales'] as $m) {
                    $sm->execute([$parteId, $m['material_id'], $m['cantidad'], $m['coste_unitario_snapshot']]);
                }
            }

            if (!empty($data['maquinaria'])) {
                $sqlQ = 'INSERT INTO parte_maquinaria (parte_id, maquinaria_id, horas, coste_hora_snapshot) VALUES (?, ?, ?, ?)';
                $sq = $this->db->prepare($sqlQ);
                foreach ($data['maquinaria'] as $q) {
                    $sq->execute([$parteId, $q['maquinaria_id'], $q['horas'], $q['coste_hora_snapshot']]);
                }
            }

            $this->db->commit();
            return $parteId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function resumenTrabajadorMensual(int $trabajadorId, string $mes): array {
        $stmt = $this->db->prepare(
            "SELECT DATE(pd.fecha) dia, SUM(pt.horas) horas, SUM(pt.horas * pt.coste_hora_snapshot) coste
            FROM parte_trabajadores pt
            JOIN partes_diarios pd ON pd.id = pt.parte_id
            WHERE pt.trabajador_id = ? AND DATE_FORMAT(pd.fecha, '%Y-%m') = ?
            GROUP BY DATE(pd.fecha)
            ORDER BY dia"
        );
        $stmt->execute([$trabajadorId, $mes]);
        $filas = $stmt->fetchAll();

        $totalHoras = 0;
        $totalCoste = 0;
        foreach ($filas as $f) {
            $totalHoras += (float)$f['horas'];
            $totalCoste += (float)$f['coste'];
        }

        return ['filas' => $filas, 'total_horas' => $totalHoras, 'total_coste' => $totalCoste];
    }

    public function informeProyecto(int $proyectoId): array {
        $resumen = [];
        $stmt = $this->db->prepare('SELECT SUM(pt.horas) horas, SUM(pt.horas * pt.coste_hora_snapshot) coste FROM parte_trabajadores pt JOIN partes_diarios pd ON pd.id = pt.parte_id WHERE pd.proyecto_id = ?');
        $stmt->execute([$proyectoId]);
        $resumen['mano_obra'] = $stmt->fetch() ?: ['horas' => 0, 'coste' => 0];

        $stmt = $this->db->prepare('SELECT t.nombre, SUM(pt.horas) horas, SUM(pt.horas * pt.coste_hora_snapshot) coste FROM parte_trabajadores pt JOIN trabajadores t ON t.id = pt.trabajador_id JOIN partes_diarios pd ON pd.id = pt.parte_id WHERE pd.proyecto_id = ? GROUP BY t.id ORDER BY t.nombre');
        $stmt->execute([$proyectoId]);
        $resumen['detalle_trabajadores'] = $stmt->fetchAll();

        $stmt = $this->db->prepare('SELECT m.nombre, m.unidad_medida, SUM(pm.cantidad) cantidad, SUM(pm.cantidad * pm.coste_unitario_snapshot) coste FROM parte_materiales pm JOIN materiales m ON m.id = pm.material_id JOIN partes_diarios pd ON pd.id = pm.parte_id WHERE pd.proyecto_id = ? GROUP BY m.id ORDER BY m.nombre');
        $stmt->execute([$proyectoId]);
        $resumen['materiales'] = $stmt->fetchAll();

        $stmt = $this->db->prepare('SELECT q.nombre, q.modelo, SUM(pq.horas) horas, SUM(pq.horas * pq.coste_hora_snapshot) coste FROM parte_maquinaria pq JOIN maquinaria q ON q.id = pq.maquinaria_id JOIN partes_diarios pd ON pd.id = pq.parte_id WHERE pd.proyecto_id = ? GROUP BY q.id ORDER BY q.nombre');
        $stmt->execute([$proyectoId]);
        $resumen['maquinaria'] = $stmt->fetchAll();

        $costeMateriales = 0;
        foreach ($resumen['materiales'] as $m) {
            $costeMateriales += (float)$m['coste'];
        }
        $costeMaquinaria = 0;
        foreach ($resumen['maquinaria'] as $m) {
            $costeMaquinaria += (float)$m['coste'];
        }
        $costeManoObra = (float)($resumen['mano_obra']['coste'] ?? 0);

        $resumen['totales'] = [
            'materiales' => $costeMateriales,
            'maquinaria' => $costeMaquinaria,
            'mano_obra' => $costeManoObra,
            'total' => $costeMateriales + $costeMaquinaria + $costeManoObra,
        ];

        return $resumen;
    }
}
