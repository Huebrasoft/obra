<?php
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Proyecto.php';
require_once __DIR__ . '/../models/ParteDiario.php';

class InformeController {
    public function trabajadorMensual(): void {
        requireLogin();
        $trabajadores = (new Trabajador())->all();
        $trabajadorId = (int)($_GET['trabajador_id'] ?? 0);
        $mes = $_GET['mes'] ?? date('Y-m');

        $resultado = ['filas' => [], 'total_horas' => 0, 'total_coste' => 0];
        if ($trabajadorId > 0) {
            $resultado = (new ParteDiario())->resumenTrabajadorMensual($trabajadorId, $mes);
        }

        if (isset($_GET['export']) && $_GET['export'] === 'csv' && $trabajadorId > 0) {
            $rows = [];
            foreach ($resultado['filas'] as $f) {
                $rows[] = [$f['dia'], $f['horas'], $f['coste']];
            }
            $rows[] = ['TOTAL', $resultado['total_horas'], $resultado['total_coste']];
            csvOutput('informe_trabajador_' . $mes . '.csv', ['Día', 'Horas', 'Coste'], $rows);
        }

        include __DIR__ . '/../views/informes/trabajador_mensual.php';
    }

    public function proyectoCompleto(): void {
        requireLogin();
        $proyectos = (new Proyecto())->allWithCliente();
        $proyectoId = (int)($_GET['proyecto_id'] ?? 0);
        $proyecto = null;
        $informe = null;
        if ($proyectoId > 0) {
            $proyecto = (new Proyecto())->find($proyectoId);
            $informe = (new ParteDiario())->informeProyecto($proyectoId);
        }

        if (isset($_GET['export']) && $_GET['export'] === 'csv' && $proyectoId > 0 && $informe) {
            $rows = [
                ['Total Horas', $informe['mano_obra']['horas'] ?? 0],
                ['Coste Mano de Obra', $informe['totales']['mano_obra']],
                ['Coste Materiales', $informe['totales']['materiales']],
                ['Coste Maquinaria', $informe['totales']['maquinaria']],
                ['Coste Total Proyecto', $informe['totales']['total']],
            ];
            csvOutput('informe_proyecto_' . $proyectoId . '.csv', ['Concepto', 'Valor'], $rows);
        }

        include __DIR__ . '/../views/informes/proyecto_completo.php';
    }
}
