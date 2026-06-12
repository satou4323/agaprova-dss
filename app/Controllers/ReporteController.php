<?php
namespace App\Controllers;

use App\Controller;
use App\Services\ReporteService;
use App\Session;

class ReporteController extends Controller {
    
    public function indexAction() {
        $esta_semana_inicio = date('Y-m-d', strtotime('monday this week'));
        $esta_semana_fin = date('Y-m-d', strtotime('sunday this week'));
        
        $reporteService = new ReporteService();
        $datos = $reporteService->generarReporteSemanal($esta_semana_inicio, $esta_semana_fin);
        $estadisticas = $reporteService->obtenerEstadisticas();
        
        $this->render('reportes.index', [
            'datos' => $datos,
            'estadisticas' => $estadisticas,
            'fecha_inicio' => $esta_semana_inicio,
            'fecha_fin' => $esta_semana_fin,
            'csrf' => $this->generateCsrf()
        ]);
    }
    
    public function generarPDFAction() {
        $fecha_inicio = trim($this->getGet('fecha_inicio', date('Y-m-d', strtotime('monday this week'))));
        $fecha_fin = trim($this->getGet('fecha_fin', date('Y-m-d', strtotime('sunday this week'))));
        
        $reporteService = new ReporteService();
        $datos = $reporteService->generarReporteSemanal($fecha_inicio, $fecha_fin);
        $resultado = $reporteService->generarReportePDF($datos);
        
        // Si TCPDF no está disponible, usar respaldo HTML
        if (!class_exists('TCPDF')) {
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_' . date('Y-m-d') . '.html"');
            echo $resultado;
        }
        // Si TCPDF está disponible, $pdf->Output('D') ya envió headers PDF y contenido
        exit;
    }
    
    public function personalizadoAction() {
        if ($this->isPost()) {
            if (!$this->validateCsrf($this->getPost('csrf_token'))) {
                Session::flash('error', 'Token CSRF inválido');
                $this->redirect('/reporte/personalizado');
            }
            
            $fecha_inicio = trim($this->getPost('fecha_inicio', date('Y-m-d')));
            $fecha_fin = trim($this->getPost('fecha_fin', date('Y-m-d')));
            
            $reporteService = new ReporteService();
            $datos = $reporteService->generarReporteSemanal($fecha_inicio, $fecha_fin);
            
            Session::set('reporte_datos', $datos);
            Session::set('reporte_rango', ['inicio' => $fecha_inicio, 'fin' => $fecha_fin]);
            
            $this->redirect('/reporte/ver');
        }

        // Manejar presets vía GET (un solo clic)
        $preset = trim($this->getGet('preset', ''));
        if ($preset !== '') {
            $fecha_inicio = date('Y-m-d', strtotime('monday this week'));
            $fecha_fin = date('Y-m-d', strtotime('sunday this week'));
            
            switch ($preset) {
                case 'hoy':
                    $fecha_inicio = date('Y-m-d');
                    $fecha_fin = date('Y-m-d');
                    break;
                case 'esta_semana':
                    $fecha_inicio = date('Y-m-d', strtotime('monday this week'));
                    $fecha_fin = date('Y-m-d', strtotime('sunday this week'));
                    break;
                case 'este_mes':
                    $fecha_inicio = date('Y-m-01');
                    $fecha_fin = date('Y-m-t');
                    break;
                case 'ultimo_mes':
                    $fecha_inicio = date('Y-m-01', strtotime('first day of last month'));
                    $fecha_fin = date('Y-m-t', strtotime('last day of last month'));
                    break;
            }
            
            $reporteService = new ReporteService();
            $datos = $reporteService->generarReporteSemanal($fecha_inicio, $fecha_fin);
            
            Session::set('reporte_datos', $datos);
            Session::set('reporte_rango', ['inicio' => $fecha_inicio, 'fin' => $fecha_fin]);
            
            $this->redirect('/reporte/ver');
        }
        
        $fecha_inicio_pred = date('Y-m-d', strtotime('monday this week'));
        $fecha_fin_pred = date('Y-m-d', strtotime('sunday this week'));
        
        $this->render('reportes.personalizado', [
            'csrf' => $this->generateCsrf(),
            'fecha_inicio_pred' => $fecha_inicio_pred,
            'fecha_fin_pred' => $fecha_fin_pred
        ]);
    }
    
    public function verAction() {
        $datos = Session::get('reporte_datos', []);
        $rango = Session::get('reporte_rango', []);
        
        $this->render('reportes.ver', [
            'datos' => $datos,
            'rango' => $rango,
            'csrf' => $this->generateCsrf()
        ]);
    }
}
