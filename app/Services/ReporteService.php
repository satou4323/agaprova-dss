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
    
    public function generarReportePDF($datos, $nombre_archivo = 'reporte.pdf') {
        // Este es un reporte básico HTML que puede convertirse a PDF
        // En producción se usaría una librería como TCPDF o MPDF
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
        $html .= 'Sistema DSS AGAPROVA v1.0 | Generado automáticamente';
        $html .= '</p></body></html>';
        
        return $html;
    }
    
    public function obtenerEstadisticas() {
        $stats = [];
        
        // Total de lotes
        $sql = 'SELECT COUNT(*) as total FROM lotes_ganado WHERE activo = 1';
        $stats['total_lotes'] = $this->db->fetch($sql)['total'] ?? 0;
        
        // Total de cabezas
        $sql = 'SELECT SUM(cabezas) as total FROM lotes_ganado WHERE activo = 1';
        $stats['total_cabezas'] = $this->db->fetch($sql)['total'] ?? 0;
        
        // Precios promedio
        $sql = 'SELECT mercado_id, AVG(precio_kg) as promedio FROM precios WHERE activo = 1 GROUP BY mercado_id';
        $precios = $this->db->fetchAll($sql);
        foreach ($precios as $p) {
            $stats['precio_' . $p['mercado_id']] = $p['promedio'];
        }
        
        return $stats;
    }
}
