<?php
namespace App\Controllers;

use App\Controller;
use App\Services\ReporteService;
use App\Session;

class ReporteController extends Controller {
    
    public function indexAction() {
        $esta_semana_inicio = date('Y-m-d', strtotime('monday this week'));
        $esta_semana_fin    = date('Y-m-d', strtotime('sunday this week'));
        
        $reporteService = new ReporteService();
        $datos          = $reporteService->generarReporteSemanal($esta_semana_inicio, $esta_semana_fin);
        $estadisticas   = $reporteService->obtenerEstadisticas();

        // Calcular métricas de la semana desde $datos
        $total_cabezas_semana = array_sum(array_column($datos, 'cabezas'));
        $peso_prom_semana     = count($datos) > 0
            ? number_format(array_sum(array_column($datos, 'peso_promedio_kg')) / count($datos), 1)
            : '0.0';

        $this->render('reportes.index', [
            'datos'                 => $datos,
            'estadisticas'          => $estadisticas,
            'fecha_inicio'          => $esta_semana_inicio,
            'fecha_fin'             => $esta_semana_fin,
            'total_cabezas_semana'  => $total_cabezas_semana,
            'peso_prom_semana'      => $peso_prom_semana,
            'page_title'            => 'Reportes',
            'csrf'                  => $this->generateCsrf()
        ]);
    }
    
    public function generarPDFAction() {
        $fecha_inicio = trim($this->getGet('fecha_inicio', date('Y-m-d', strtotime('monday this week'))));
        $fecha_fin    = trim($this->getGet('fecha_fin',    date('Y-m-d', strtotime('sunday this week'))));
        
        $reporteService = new ReporteService();
        $datos          = $reporteService->generarReporteSemanal($fecha_inicio, $fecha_fin);
        $resultado      = $reporteService->generarReportePDF($datos);
        
        if (!class_exists('TCPDF')) {
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_' . date('Y-m-d') . '.html"');
            echo $resultado;
        }
        exit;
    }
    
    public function personalizadoAction() {
        if ($this->isPost()) {
            if (!$this->validateCsrf($this->getPost('csrf_token'))) {
                Session::flash('error', 'Token CSRF inválido');
                $this->redirect('/reporte/personalizado');
            }
            
            $fecha_inicio = trim($this->getPost('fecha_inicio', date('Y-m-d')));
            $fecha_fin    = trim($this->getPost('fecha_fin',    date('Y-m-d')));
            
            $reporteService = new ReporteService();
            $datos          = $reporteService->generarReporteSemanal($fecha_inicio, $fecha_fin);

            // Calcular resumen para ver.php
            $resumen = $this->calcularResumen($datos, $fecha_inicio, $fecha_fin);
            
            Session::set('reporte_datos',  $datos);
            Session::set('reporte_rango',  ['inicio' => $fecha_inicio, 'fin' => $fecha_fin]);
            Session::set('reporte_resumen', $resumen);
            
            $this->redirect('/reporte/ver');
        }

        // Presets vía GET
        $preset = trim($this->getGet('preset', ''));
        if ($preset !== '') {
            $fecha_inicio = date('Y-m-d', strtotime('monday this week'));
            $fecha_fin    = date('Y-m-d', strtotime('sunday this week'));
            
            switch ($preset) {
                case 'hoy':
                    $fecha_inicio = date('Y-m-d');
                    $fecha_fin    = date('Y-m-d');
                    break;
                case 'esta_semana':
                    $fecha_inicio = date('Y-m-d', strtotime('monday this week'));
                    $fecha_fin    = date('Y-m-d', strtotime('sunday this week'));
                    break;
                case 'este_mes':
                    $fecha_inicio = date('Y-m-01');
                    $fecha_fin    = date('Y-m-t');
                    break;
                case 'ultimo_mes':
                    $fecha_inicio = date('Y-m-01', strtotime('first day of last month'));
                    $fecha_fin    = date('Y-m-t',  strtotime('last day of last month'));
                    break;
            }
            
            $reporteService = new ReporteService();
            $datos          = $reporteService->generarReporteSemanal($fecha_inicio, $fecha_fin);
            $resumen        = $this->calcularResumen($datos, $fecha_inicio, $fecha_fin);
            
            Session::set('reporte_datos',   $datos);
            Session::set('reporte_rango',   ['inicio' => $fecha_inicio, 'fin' => $fecha_fin]);
            Session::set('reporte_resumen', $resumen);
            
            $this->redirect('/reporte/ver');
        }
        
        $this->render('reportes.personalizado', [
            'csrf'              => $this->generateCsrf(),
            'fecha_inicio_pred' => date('Y-m-d', strtotime('monday this week')),
            'fecha_fin_pred'    => date('Y-m-d', strtotime('sunday this week'))
        ]);
    }
    
    public function verAction() {
        $datos   = Session::get('reporte_datos',   []);
        $rango   = Session::get('reporte_rango',   []);
        $resumen = Session::get('reporte_resumen', []);

        // Fallback si se accede directamente sin sesión
        if (empty($resumen) && !empty($datos) && !empty($rango)) {
            $resumen = $this->calcularResumen($datos, $rango['inicio'], $rango['fin']);
        }
        
        $this->render('reportes.ver', [
            'datos'   => $datos,
            'rango'   => $rango,
            'resumen' => $resumen,
            'csrf'    => $this->generateCsrf()
        ]);
    }

    public function generarPDFVerAction() {
        $datos = Session::get('reporte_datos', []);
        $rango = Session::get('reporte_rango', []);

        if (empty($rango)) {
            $rango = ['inicio' => date('Y-m-d'), 'fin' => date('Y-m-d')];
        }

        $reporteService = new ReporteService();
        $resultado      = $reporteService->generarReportePDFCompacto($datos, $rango);

        if (!class_exists('TCPDF')) {
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_lotes_' . date('Y-m-d') . '.html"');
            echo $resultado;
        }
        exit;
    }

    // ─── Método auxiliar: calcular resumen del período ────────────────────────
    private function calcularResumen(array $datos, string $fecha_inicio, string $fecha_fin): array {
        $total_lotes   = count($datos);
        $total_cabezas = array_sum(array_column($datos, 'cabezas'));
        $peso_promedio = $total_lotes > 0
            ? array_sum(array_column($datos, 'peso_promedio_kg')) / $total_lotes
            : 0;
        $dias_periodo  = (new \DateTime($fecha_fin))->diff(new \DateTime($fecha_inicio))->days + 1;

        return [
            'total_lotes'   => $total_lotes,
            'total_cabezas' => $total_cabezas,
            'peso_promedio' => $peso_promedio,
            'dias_periodo'  => $dias_periodo,
        ];
    }
}