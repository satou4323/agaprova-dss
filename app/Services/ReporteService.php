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
        $pdf->SetAutoPageBreak(true, 25);
        
        $pdf->setImageScale(1.25);
        
        $pdf->AddPage();
        
        // ─────────────────────────────────────────────
        // ENCABEZADO DEL REPORTE
        // ─────────────────────────────────────────────
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(46, 125, 50);
        $pdf->Cell(0, 10, $titulo, 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 5, 'Generado: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
        $pdf->Cell(0, 5, 'AGAPROVA — Asociacion de Ganaderos de la Provincia de Vallegrande', 0, 1, 'L');
        $pdf->Ln(4);
        
        // ─────────────────────────────────────────────
        // BLOQUE DE TOTALES
        // ─────────────────────────────────────────────
        $total_cabezas = array_sum(array_column($datos, 'cabezas'));
        $total_lotes = count($datos);
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(46, 125, 50);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(55, 9, 'Total Lotes: ' . $total_lotes, 1, 0, 'C', true);
        $pdf->Cell(55, 9, 'Total Cabezas: ' . $total_cabezas, 1, 0, 'C', true);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(70, 9, 'Periodo: ' . (count($datos) > 0 ? date('d/m/Y', strtotime($datos[0]['fecha_registro'])) . ' - ' . date('d/m/Y', strtotime($datos[count($datos)-1]['fecha_registro'])) : '—'), 1, 1, 'C', true);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Ln(5);
        
        if (!empty($datos)) {
            // ─────────────────────────────────────────────
            // TABLA PRINCIPAL — CONFIGURACIÓN DE COLUMNAS
            // ─────────────────────────────────────────────
            // Ancho disponible: 180mm (210 - 15 - 15)
            $col_w = [12, 22, 14, 16, 24, 18, 16, 16, 28, 14];
            $col_headers = ['Lote', 'Fecha', 'Cabezas', 'Peso kg', 'Condicion', 'Estacion', 'Hora', 'Ruta', 'Mercado', 'Precio'];
            $col_align = ['C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'];
            
            $header_h = 8;
            $data_font = 7.5;
            $line_h_mm = 3.2;
            
            // ─── FUNCIÓN ANÓNIMA PARA DIBUJAR ENCABEZADO DE TABLA ───
            $drawHeader = function($pdf, $col_w, $col_headers, $header_h) {
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetFillColor(46, 125, 50);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetDrawColor(30, 100, 35);
                $x0 = $pdf->GetX();
                $y0 = $pdf->GetY();
                foreach ($col_headers as $i => $h) {
                    $pdf->MultiCell($col_w[$i], $header_h, $h, 1, 'C', true, 0, $x0 + array_sum(array_slice($col_w, 0, $i)), $y0, true, 0, false, true, $header_h, 'M');
                }
                $pdf->SetXY($x0, $y0 + $header_h);
            };
            
            // ─── DIBUJAR ENCABEZADO INICIAL ───
            $drawHeader($pdf, $col_w, $col_headers, $header_h);
            
            // ─── FILAS DE DATOS ───
            $pdf->SetTextColor(40, 40, 40);
            $fill = false;
            
            foreach ($datos as $fila) {
                $pdf->SetDrawColor(210, 210, 210);
                
                // Preparar valores de la fila
                $vals = [
                    strval($fila['id']),
                    date('d/m/Y', strtotime($fila['fecha_registro'])),
                    strval($fila['cabezas']),
                    number_format($fila['peso_promedio_kg'], 1),
                    $fila['condicion'],
                    $fila['estacion'],
                    $fila['hora_salida'],
                    $fila['ruta'] ?? '—',
                    $fila['mercado'] ?? '—',
                    $fila['precio_kg'] ? ('Bs ' . number_format($fila['precio_kg'], 2)) : '—'
                ];
                
                // Calcular altura necesaria para esta fila
                $pdf->SetFont('helvetica', '', $data_font);
                $max_lines = 1;
                foreach ($vals as $i => $v) {
                    $lines = $pdf->getNumLines($v, $col_w[$i] - 1);
                    if ($lines > $max_lines) $max_lines = $lines;
                }
                $row_h = max(5.5, $max_lines * $line_h_mm);
                
                // Detectar salto de página
                $x0 = $pdf->GetX();
                $y0 = $pdf->GetY();
                if ($y0 + $row_h > $pdf->getPageHeight() - $pdf->getFooterMargin() - 15) {
                    $pdf->AddPage();
                    $drawHeader($pdf, $col_w, $col_headers, $header_h);
                    $x0 = $pdf->GetX();
                    $y0 = $pdf->GetY();
                }
                
                // Dibujar celdas con MultiCell y posicionamiento manual
                $pdf->SetFillColor(245, 240, 232);
                foreach ($vals as $i => $v) {
                    $x = $x0 + array_sum(array_slice($col_w, 0, $i));
                    $pdf->MultiCell($col_w[$i], $row_h, $v, 1, $col_align[$i], $fill, 0, $x, $y0, true, 0, false, true, $row_h, 'M');
                }
                $pdf->SetXY($x0, $y0 + $row_h);
                $fill = !$fill;
            }
            
            $pdf->Ln(6);
            
            // ─────────────────────────────────────────────
            // RESUMEN DE COSTOS DE FLETE
            // ─────────────────────────────────────────────
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(46, 125, 50);
            $pdf->Cell(0, 7, 'Costos de Flete Vigentes (Bs/cabeza):', 0, 1, 'L');
            $pdf->Ln(1);
            
            $costos = \App\Models\CostoFlete::getCostosActivos();
            if (!empty($costos)) {
                $cw = [90, 50, 40];
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetFillColor(46, 125, 50);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetDrawColor(30, 100, 35);
                $x0 = $pdf->GetX();
                $y0 = $pdf->GetY();
                $cheaders = ['Ruta', 'Costo por Cabeza', 'Vigencia'];
                foreach ($cheaders as $i => $h) {
                    $pdf->MultiCell($cw[$i], 7, $h, 1, 'C', true, 0, $x0 + array_sum(array_slice($cw, 0, $i)), $y0, true, 0, false, true, 7, 'M');
                }
                $pdf->SetXY($x0, $y0 + 7);
                
                $pdf->SetDrawColor(200, 200, 200);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(40, 40, 40);
                foreach ($costos as $c) {
                    $vals = [$c['codigo'] . ' - ' . $c['nombre'], 'Bs ' . number_format($c['costo_cabeza'], 2), date('d/m/Y', strtotime($c['semana_inicio']))];
                    $x0 = $pdf->GetX();
                    $y0 = $pdf->GetY();
                    foreach ($vals as $i => $v) {
                        $pdf->MultiCell($cw[$i], 6, $v, 1, $i == 0 ? 'L' : 'C', false, 0, $x0 + array_sum(array_slice($cw, 0, $i)), $y0, true, 0, false, true, 6, 'M');
                    }
                    $pdf->SetXY($x0, $y0 + 6);
                }
            }
        } else {
            $pdf->SetFont('helvetica', '', 11);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell(0, 10, 'No hay datos registrados en el periodo seleccionado.', 0, 1, 'C');
        }
        
        $pdf->Ln(8);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, 'Sistema DSS AGAPROVA v' . APP_VERSION . ' | Generado automaticamente', 0, 1, 'C');
        
        return $pdf->Output('reporte_' . date('Y-m-d') . '.pdf', 'D');
    }
    
    public function generarReportePDFCompacto($datos, $rango, $titulo = 'Reporte de Lotes') {
        if (!class_exists('TCPDF')) {
            return $this->generarHTML($datos);
        }

        $pdf = new AgaprovaPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('DSS AGAPROVA');
        $pdf->SetAuthor('AGAPROVA');
        $pdf->SetTitle($titulo);
        $pdf->SetSubject('Reporte Detallado de Lotes');

        $pdf->setHeaderFont(['helvetica', '', 10]);
        $pdf->setFooterFont(['helvetica', '', 8]);

        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 25, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 25);

        $pdf->setImageScale(1.25);
        $pdf->AddPage();

        // ── ENCABEZADO ──
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(46, 125, 50);
        $pdf->Cell(0, 10, $titulo, 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 5, 'Generado: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
        $pdf->Cell(0, 5, 'AGAPROVA — Asociacion de Ganaderos de la Provincia de Vallegrande', 0, 1, 'L');
        $pdf->Ln(3);

        // ── RANGO ──
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(60, 60, 60);
        $pdf->Cell(0, 6, 'Rango del reporte: ' . date('d/m/Y', strtotime($rango['inicio'])) . ' al ' . date('d/m/Y', strtotime($rango['fin'])), 0, 1, 'L');
        $pdf->Ln(3);

        // ── TOTALES ──
        $total_cabezas = array_sum(array_column($datos, 'cabezas'));
        $total_lotes = count($datos);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(46, 125, 50);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(90, 8, 'Total Lotes: ' . $total_lotes, 1, 0, 'C', true);
        $pdf->Cell(90, 8, 'Total Cabezas: ' . $total_cabezas, 1, 1, 'C', true);
        $pdf->Ln(6);

        if (!empty($datos)) {
            $page_w = 180;
            $margin_l = 15;
            $card_bg = [252, 252, 249];
            $card_border = [46, 125, 50];
            $label_color = [100, 100, 100];
            $value_color = [40, 40, 40];
            $sep_color = [220, 220, 220];
            $font_lbl = 7.2;
            $font_val = 7.2;

            foreach ($datos as $fila) {
                // ── SALTO DE PÁGINA ──
                if ($pdf->GetY() > $pdf->getPageHeight() - $pdf->getFooterMargin() - 48) {
                    $pdf->AddPage();
                }

                $x0 = $margin_l;
                $y0 = $pdf->GetY();
                $card_h = 27;

                // ── FONDO Y BORDE ──
                $pdf->SetFillColor($card_bg[0], $card_bg[1], $card_bg[2]);
                $pdf->SetDrawColor($card_border[0], $card_border[1], $card_border[2]);
                $pdf->Rect($x0, $y0, $page_w, $card_h, 'DF');

                // ═══════════════ LINEA 1 ═══════════════
                $pdf->SetXY($x0 + 3, $y0 + 1.5);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->SetTextColor($card_border[0], $card_border[1], $card_border[2]);
                $pdf->Cell(0, 5, 'Lote #' . $fila['id'], 0, 0, 'L');

                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor($label_color[0], $label_color[1], $label_color[2]);
                $fecha_txt = date('d/m/Y', strtotime($fila['fecha_registro']));
                $pdf->Cell(0, 5, $fecha_txt, 0, 1, 'R');

                // ── Separador 1 ──
                $y1 = $y0 + 7.5;
                $pdf->SetDrawColor($sep_color[0], $sep_color[1], $sep_color[2]);
                $pdf->Line($x0 + 3, $y1, $x0 + $page_w - 3, $y1);

                // ═══════════════ LINEA 2 ═══════════════
                // 3 columnas. Cada columna = label + valor concatenado
                $col_w = ($page_w - 8) / 3;
                $row_h = 5.5;
                $y2 = $y1 + 0.8;

                $pdf->SetFont('helvetica', '', $font_val);
                $pdf->SetTextColor($value_color[0], $value_color[1], $value_color[2]);

                $pdf->MultiCell($col_w, $row_h, 'Cab: ' . $fila['cabezas'], 0, 'L', false, 0, $x0 + 3, $y2, true, 0, false, true, $row_h, 'M');
                $pdf->MultiCell($col_w, $row_h, 'Peso: ' . number_format($fila['peso_promedio_kg'], 1) . ' kg', 0, 'L', false, 0, $x0 + 3 + $col_w, $y2, true, 0, false, true, $row_h, 'M');
                $pdf->MultiCell($col_w, $row_h, 'Cond: ' . $fila['condicion'], 0, 'L', false, 0, $x0 + 3 + $col_w * 2, $y2, true, 0, false, true, $row_h, 'M');

                // ── Separador 2 ──
                $y3 = $y2 + $row_h;
                $pdf->Line($x0 + 3, $y3, $x0 + $page_w - 3, $y3);

                // ═══════════════ LINEA 3 ═══════════════
                $y4 = $y3 + 0.8;

                $pdf->MultiCell($col_w, $row_h, 'Est: ' . $fila['estacion'], 0, 'L', false, 0, $x0 + 3, $y4, true, 0, false, true, $row_h, 'M');
                $pdf->MultiCell($col_w, $row_h, 'Hora: ' . $fila['hora_salida'], 0, 'L', false, 0, $x0 + 3 + $col_w, $y4, true, 0, false, true, $row_h, 'M');
                $pdf->MultiCell($col_w, $row_h, 'Mdo: ' . ($fila['mercado'] ?? '—'), 0, 'L', false, 0, $x0 + 3 + $col_w * 2, $y4, true, 0, false, true, $row_h, 'M');

                // ── Separador 3 ──
                $y5 = $y4 + $row_h;
                $pdf->Line($x0 + 3, $y5, $x0 + $page_w - 3, $y5);

                // ═══════════════ LINEA 4 ═══════════════
                $y6 = $y5 + 0.8;

                $ruta_txt = $fila['ruta'] ?? '—';
                $precio_txt = $fila['precio_kg'] ? 'Bs ' . number_format($fila['precio_kg'], 2) : '—';
                $flete_txt = $fila['costo_cabeza'] ? 'Bs ' . number_format($fila['costo_cabeza'], 2) : '—';

                $pdf->MultiCell($col_w, $row_h, 'Ruta: ' . $ruta_txt, 0, 'L', false, 0, $x0 + 3, $y6, true, 0, false, true, $row_h, 'M');
                $pdf->MultiCell($col_w, $row_h, 'Precio: ' . $precio_txt, 0, 'L', false, 0, $x0 + 3 + $col_w, $y6, true, 0, false, true, $row_h, 'M');
                $pdf->MultiCell($col_w, $row_h, 'Flete: ' . $flete_txt, 0, 'L', false, 0, $x0 + 3 + $col_w * 2, $y6, true, 0, false, true, $row_h, 'M');

                // ── Avanzar Y ──
                $pdf->SetXY($margin_l, $y0 + $card_h + 1);
            }

            $pdf->Ln(6);

            // ── RESUMEN DE COSTOS DE FLETE ──
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(46, 125, 50);
            $pdf->Cell(0, 7, 'Costos de Flete Vigentes (Bs/cabeza):', 0, 1, 'L');
            $pdf->Ln(1);

            $costos = \App\Models\CostoFlete::getCostosActivos();
            if (!empty($costos)) {
                $cw = [90, 50, 40];
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetFillColor(46, 125, 50);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetDrawColor(30, 100, 35);
                $x0 = $pdf->GetX();
                $y0 = $pdf->GetY();
                $cheaders = ['Ruta', 'Costo por Cabeza', 'Vigencia'];
                foreach ($cheaders as $i => $h) {
                    $pdf->MultiCell($cw[$i], 7, $h, 1, 'C', true, 0, $x0 + array_sum(array_slice($cw, 0, $i)), $y0, true, 0, false, true, 7, 'M');
                }
                $pdf->SetXY($x0, $y0 + 7);
                $pdf->SetDrawColor(200, 200, 200);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(40, 40, 40);
                foreach ($costos as $c) {
                    $cvals = [$c['codigo'] . ' - ' . $c['nombre'], 'Bs ' . number_format($c['costo_cabeza'], 2), date('d/m/Y', strtotime($c['semana_inicio']))];
                    $x0 = $pdf->GetX();
                    $y0 = $pdf->GetY();
                    foreach ($cvals as $i => $v) {
                        $pdf->MultiCell($cw[$i], 6, $v, 1, $i == 0 ? 'L' : 'C', false, 0, $x0 + array_sum(array_slice($cw, 0, $i)), $y0, true, 0, false, true, 6, 'M');
                    }
                    $pdf->SetXY($x0, $y0 + 6);
                }
            }
        } else {
            $pdf->SetFont('helvetica', '', 11);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell(0, 10, 'No hay datos registrados en el periodo seleccionado.', 0, 1, 'C');
        }

        $pdf->Ln(8);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, 'Sistema DSS AGAPROVA v' . APP_VERSION . ' | Generado automaticamente', 0, 1, 'C');

        return $pdf->Output('reporte_lotes_' . date('Y-m-d') . '.pdf', 'D');
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
            
            // Punto de equilibrio: Precio_necesario = Costo / (peso_prom × E_inv_efectivo)
            $peso_prom_equilibrio = $detalles['peso_promedio'] ?? PESO_DEFAULT;
            $sensibilidad['punto_equilibrio_precio'] = ($precio_destino > 0 && $peso_prom_equilibrio > 0) ? round($costo_ruta / ($peso_prom_equilibrio * $eficiencia), 2) : 0;
            
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
        $peso_prom = $detalles['peso_promedio'] ?? PESO_DEFAULT;
        foreach ($rutas_analisis as $r => $info) {
            if ($eficiencia > 0 && $peso_prom > 0) {
                $precio_equilibrio = round($info['costo'] / ($peso_prom * $eficiencia), 2);
                $margen_actual = round(($info['precio'] * $peso_prom * $eficiencia) - $info['costo'], 2);
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
