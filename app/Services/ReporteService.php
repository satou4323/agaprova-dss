<?php
namespace App\Services;

use App\Database;

class ReporteService {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function generarReporteSemanal($fecha_inicio, $fecha_fin) {
        $sql = 'SELECT 
                    l.id, l.cabezas, l.peso_promedio_kg, l.hora_salida, l.fecha_registro,
                    cg.nombre as condicion, e.nombre as estacion,
                    r.codigo as ruta, m.nombre as mercado,
                    p.precio_kg, cf.costo_cabeza
                FROM lotes_ganado l
                JOIN condiciones_ganado cg ON l.condicion_id = cg.id
                JOIN estaciones e ON l.estacion_id = e.id
                LEFT JOIN rutas r ON l.ruta_optima_id = r.id
                LEFT JOIN mercados m ON r.mercado_id = m.id
                LEFT JOIN precios p ON p.mercado_id = m.id AND p.activo = 1
                LEFT JOIN costos_flete cf ON cf.ruta_id = r.id AND cf.activo = 1
                WHERE l.fecha_registro BETWEEN ? AND ? AND l.activo = 1
                ORDER BY l.fecha_registro ASC';
        
        return $this->db->fetchAll($sql, [$fecha_inicio, $fecha_fin]);
    }
    
    public function generarReportePDF($datos, $titulo = 'Reporte Operativo Semanal') {
        if (!class_exists('TCPDF')) {
            return $this->generarHTML($datos);
        }

        $pdf = new AgaprovaPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $pdf->SetCreator('DSS AGAPROVA');
        $pdf->SetAuthor('AGAPROVA');
        $pdf->SetTitle($titulo);
        $pdf->SetSubject('Reporte de Optimización Logística');
        
        $pdf->setHeaderFont(['helvetica', '', 10]);
        $pdf->setFooterFont(['helvetica', '', 8]);
        
        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 25, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 20);
        
        $pdf->setImageScale(1.25);
        
        $pdf->AddPage();
        
        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(46, 125, 50);
        $pdf->Cell(0, 10, $titulo, 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(60, 60, 60);
        $pdf->Cell(0, 6, 'Fecha de generacion: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Asociacion: AGAPROVA - Asociacion de Ganaderos de la Provincia de Vallegrande', 0, 1, 'L');
        $pdf->Ln(5);
        
        // Totales
        $total_cabezas = array_sum(array_column($datos, 'cabezas'));
        $total_lotes = count($datos);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(46, 125, 50);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(90, 8, 'Total Lotes: ' . $total_lotes, 1, 0, 'C', true);
        $pdf->Cell(90, 8, 'Total Cabezas: ' . $total_cabezas, 1, 1, 'C', true);
        $pdf->Ln(5);
        
        if (!empty($datos)) {
            // Tabla de datos
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(46, 125, 50);
            $pdf->SetTextColor(255, 255, 255);
            
            $w = [10, 18, 18, 18, 22, 18, 22, 20, 20, 18];
            $headers = ['Lote', 'Fecha', 'Cabezas', 'Peso', 'Condicion', 'Estacion', 'Hora Salida', 'Ruta', 'Mercado', 'Precio'];
            
            for ($i = 0; $i < count($headers); $i++) {
                $pdf->Cell($w[$i], 7, $headers[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();
            
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(40, 40, 40);
            $fill = false;
            
            foreach ($datos as $fila) {
                $pdf->SetFillColor(245, 240, 232);
                
                $pdf->Cell($w[0], 6, $fila['id'], 1, 0, 'C', $fill);
                $pdf->Cell($w[1], 6, date('d/m/Y', strtotime($fila['fecha_registro'])), 1, 0, 'C', $fill);
                $pdf->Cell($w[2], 6, $fila['cabezas'], 1, 0, 'C', $fill);
                $pdf->Cell($w[3], 6, number_format($fila['peso_promedio_kg'], 1), 1, 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $fila['condicion'], 1, 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $fila['estacion'], 1, 0, 'C', $fill);
                $pdf->Cell($w[6], 6, $fila['hora_salida'], 1, 0, 'C', $fill);
                $pdf->Cell($w[7], 6, $fila['ruta'] ?? '—', 1, 0, 'C', $fill);
                $pdf->Cell($w[8], 6, $fila['mercado'] ?? '—', 1, 0, 'C', $fill);
                $pdf->Cell($w[9], 6, $fila['precio_kg'] ? 'Bs ' . number_format($fila['precio_kg'], 2) : '—', 1, 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            
            $pdf->Ln(5);
            
            // Costos
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetTextColor(46, 125, 50);
            $pdf->Cell(0, 8, 'Costos de Flete Vigentes (Bs/cabeza):', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(60, 60, 60);
            
            $costos = \App\Models\CostoFlete::getCostosActivos();
            if (!empty($costos)) {
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetFillColor(46, 125, 50);
                $pdf->SetTextColor(255, 255, 255);
                $cw = [90, 50, 45];
                $pdf->Cell($cw[0], 7, 'Ruta', 1, 0, 'C', true);
                $pdf->Cell($cw[1], 7, 'Costo por Cabeza', 1, 0, 'C', true);
                $pdf->Cell($cw[2], 7, 'Semana', 1, 1, 'C', true);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(40, 40, 40);
                foreach ($costos as $c) {
                    $pdf->Cell($cw[0], 6, $c['nombre'], 1, 0, 'L');
                    $pdf->Cell($cw[1], 6, 'Bs ' . number_format($c['costo_cabeza'], 2), 1, 0, 'C');
                    $pdf->Cell($cw[2], 6, date('d/m/Y', strtotime($c['semana_inicio'])), 1, 1, 'C');
                }
            }
        } else {
            $pdf->SetFont('helvetica', '', 11);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell(0, 10, 'No hay datos registrados en el periodo seleccionado.', 0, 1, 'C');
        }
        
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, 'Sistema DSS AGAPROVA v' . APP_VERSION . ' | Generado automaticamente', 0, 1, 'C');
        
        return $pdf->Output('reporte_' . date('Y-m-d') . '.pdf', 'D');
    }
    
    public function generarReporteHTML($datos, $nombre_archivo = 'reporte.html') {
        $html = $this->generarHTML($datos);
        return $html;
    }
    
    private function generarHTML($datos) {
        $html = '<!DOCTYPE html><html><head>';
        $html .= '<meta charset="UTF-8"><title>Reporte Semanal AGAPROVA</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Arial, sans-serif; color: #333; }';
        $html .= 'h1 { color: #2E7D32; text-align: center; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
        $html .= 'th { background-color: #2E7D32; color: white; padding: 10px; text-align: left; }';
        $html .= 'td { padding: 8px; border-bottom: 1px solid #ddd; }';
        $html .= 'tr:hover { background-color: #f5f0e8; }';
        $html .= '.total { background-color: #E8F5E9; font-weight: bold; }';
        $html .= '</style></head><body>';
        
        $html .= '<h1>Reporte Operativo Semanal</h1>';
        $html .= '<p><strong>Fecha Generación:</strong> ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<p><strong>Asociación:</strong> AGAPROVA</p>';
        
        $html .= '<table>';
        $html .= '<tr><th>Lote</th><th>Fecha</th><th>Cabezas</th><th>Peso Prom</th>';
        $html .= '<th>Condición</th><th>Estación</th><th>Hora Salida</th>';
        $html .= '<th>Ruta</th><th>Mercado</th></tr>';
        
        if (!empty($datos)) {
            foreach ($datos as $fila) {
                $html .= '<tr>';
                $html .= '<td>' . $fila['id'] . '</td>';
                $html .= '<td>' . $fila['fecha_registro'] . '</td>';
                $html .= '<td>' . $fila['cabezas'] . '</td>';
                $html .= '<td>' . number_format($fila['peso_promedio_kg'], 2) . ' kg</td>';
                $html .= '<td>' . $fila['condicion'] . '</td>';
                $html .= '<td>' . $fila['estacion'] . '</td>';
                $html .= '<td>' . $fila['hora_salida'] . '</td>';
                $html .= '<td>' . $fila['ruta'] . '</td>';
                $html .= '<td>' . $fila['mercado'] . '</td>';
                $html .= '</tr>';
            }
        }
        
        $html .= '</table>';
        $html .= '<p style="margin-top: 30px; font-size: 12px; color: #999;">';
        $html .= 'Sistema DSS AGAPROVA v' . APP_VERSION . ' | Generado automáticamente';
        $html .= '</p></body></html>';
        
        return $html;
    }
    
    public function obtenerEstadisticas() {
        $stats = [];
        
        $sql = 'SELECT COUNT(*) as total FROM lotes_ganado WHERE activo = 1';
        $stats['total_lotes'] = $this->db->fetch($sql)['total'] ?? 0;
        
        $sql = 'SELECT SUM(cabezas) as total FROM lotes_ganado WHERE activo = 1';
        $stats['total_cabezas'] = $this->db->fetch($sql)['total'] ?? 0;
        
        $sql = 'SELECT mercado_id, AVG(precio_kg) as promedio FROM precios WHERE activo = 1 GROUP BY mercado_id';
        $precios = $this->db->fetchAll($sql);
        foreach ($precios as $p) {
            $stats['precio_' . $p['mercado_id']] = $p['promedio'];
        }
        
        return $stats;
    }
    
    public function obtenerSensibilidad($resultado, $cabezas) {
        if (!$resultado['factible']) {
            return null;
        }
        
        $detalles = $resultado['detalles'];
        $ruta_optima = $detalles['ruta_optima'];
        
        $margen_key = 'margen_r' . $ruta_optima;
        $margen_optimo = $detalles[$margen_key];
        
        $sensibilidad = [
            'margen_por_cabeza' => $margen_optimo,
            'total_cabezas' => $cabezas,
            'margen_total' => $resultado['ganancia_total'],
            'punto_equilibrio_cabezas' => 0,
            'punto_equilibrio_precio' => 0,
            'analisis_ruptura' => []
        ];
        
        // Punto de equilibrio: cuántas cabezas se necesitan para que el margen total sea >= 0
        if ($margen_optimo < 0) {
            $costo_ruta = $detalles['costo_c' . $ruta_optima];
            $precio_destino = ($ruta_optima == 1 || $ruta_optima == 3) ? $detalles['precio_sc'] : $detalles['precio_cb'];
            $eficiencia = $detalles['eficiencia_efectiva'];
            
            // Punto de equilibrio: Precio_necesario = Costo / E_inv_efectivo
            $sensibilidad['punto_equilibrio_precio'] = $precio_destino > 0 ? round($costo_ruta / $eficiencia, 2) : 0;
            
            // Punto de equilibrio en cabezas: asumiendo precio mejorado
            $sensibilidad['punto_equilibrio_cabezas'] = 0; // con margen negativo no hay equilibrio en cabezas
        } else {
            $sensibilidad['punto_equilibrio_cabezas'] = 1; // 1 cabeza ya es rentable
            $sensibilidad['punto_equilibrio_precio'] = $detalles['margen_r1'] > 0 ? 0 : 0;
        }
        
        // Análisis de escenarios de ruptura: qué precio mínimo haría cada ruta rentable
        $rutas_analisis = [
            1 => ['costo' => $detalles['costo_c1'], 'precio' => $detalles['precio_sc']],
            2 => ['costo' => $detalles['costo_c2'], 'precio' => $detalles['precio_cb']],
            3 => ['costo' => $detalles['costo_c3'], 'precio' => $detalles['precio_sc']],
            4 => ['costo' => $detalles['costo_c4'], 'precio' => $detalles['precio_cb']]
        ];
        
        $eficiencia = $detalles['eficiencia_efectiva'];
        foreach ($rutas_analisis as $r => $info) {
            if ($eficiencia > 0) {
                $precio_equilibrio = round($info['costo'] / $eficiencia, 2);
                $margen_actual = round(($info['precio'] * $eficiencia) - $info['costo'], 2);
                $sensibilidad['analisis_ruptura'][] = [
                    'ruta' => $r,
                    'costo' => $info['costo'],
                    'precio_actual' => $info['precio'],
                    'precio_equilibrio' => $precio_equilibrio,
                    'margen_actual' => $margen_actual,
                    'diferencia_precio' => round($precio_equilibrio - $info['precio'], 2)
                ];
            }
        }
        
        return $sensibilidad;
    }
}
