<?php
namespace App\Controllers;

use App\Controller;
use App\Models\{LoteGanado, Estacion, CondicionGanado};
use App\Services\SimplexSolver;
use App\Session;

class LoteController extends Controller {
    
    public function indexAction() {
        $lotes = LoteGanado::getActivosConRuta();
        $estaciones = Estacion::all();
        $condiciones = CondicionGanado::all();
        
        $this->render('lotes.index', [
            'lotes' => $lotes,
            'estaciones' => $estaciones,
            'condiciones' => $condiciones,
            'csrf' => $this->generateCsrf()
        ]);
    }
    
    public function crearAction() {
        $estaciones = Estacion::all();
        $condiciones = CondicionGanado::all();
        
        $this->render('lotes.crear', [
            'estaciones' => $estaciones,
            'condiciones' => $condiciones,
            'csrf' => $this->generateCsrf()
        ]);
    }
    
    public function guardarAction() {
        if (!$this->isPost()) {
            $this->redirect('/lote/crear');
        }
        
        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/lote/crear');
        }
        
        $cabezas = intval($this->getPost('cabezas', 0));
        $peso_promedio = floatval($this->getPost('peso_promedio_kg', 0));
        $condicion_id = intval($this->getPost('condicion_id', 0));
        $estacion_id = intval($this->getPost('estacion_id', 0));
        $hora_salida = trim($this->getPost('hora_salida', '20:00:00'));
        
        if ($cabezas <= 0 || $peso_promedio <= 0) {
            Session::flash('error', 'Valores inválidos');
            $this->redirect('/lote/crear');
        }
        
        $lote = new LoteGanado();
        $lote->cabezas = $cabezas;
        $lote->peso_promedio_kg = $peso_promedio;
        $lote->condicion_id = $condicion_id;
        $lote->estacion_id = $estacion_id;
        $lote->hora_salida = $hora_salida;
        $lote->fecha_registro = date('Y-m-d');
        $lote->activo = 1;
        $lote->created_at = date('Y-m-d H:i:s');
        
        if ($lote->save()) {
            // Ejecutar Simplex
            $simplex = new SimplexSolver();
            $resultado = $simplex->optimizar(
                $lote->id,
                $cabezas,
                $peso_promedio,
                $condicion_id,
                $estacion_id,
                $hora_salida
            );
            
            // Guardar ruta óptima asignada en el lote
            if ($resultado['factible'] && isset($resultado['detalles']['ruta_optima'])) {
                $ruta_optima_id = intval($resultado['detalles']['ruta_optima']);
                $this->db->query('UPDATE lotes_ganado SET ruta_optima_id = ? WHERE id = ?', [$ruta_optima_id, $lote->id]);
            }
            
            // Persistir resultados completos en resultados_optimizacion
            if ($resultado['factible']) {
                $this->db->query(
                    'INSERT INTO resultados_optimizacion (fecha_calculo, x1, x2, x3, x4, ganancia_total, factible, datos_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        date('Y-m-d'),
                        $resultado['x1'],
                        $resultado['x2'],
                        $resultado['x3'],
                        $resultado['x4'],
                        $resultado['ganancia_total'],
                        $resultado['factible'] ? 1 : 0,
                        json_encode($resultado['detalles'])
                    ]
                );
            }
            
            Session::flash('success', 'Lote registrado. Resultado de optimización disponible.');
            Session::set('resultado_optimizacion', $resultado);
            $this->redirect('/lote/resultado');
        } else {
            Session::flash('error', 'Error al guardar el lote');
            $this->redirect('/lote/crear');
        }
    }
    
    public function resultadoAction() {
        $resultado = Session::get('resultado_optimizacion', []);
        Session::remove('resultado_optimizacion');
        
        $this->render('lotes.resultado', [
            'resultado' => $resultado,
            'csrf' => $this->generateCsrf()
        ]);
    }
}
