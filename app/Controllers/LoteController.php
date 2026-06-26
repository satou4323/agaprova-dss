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
            'lotes'      => $lotes,
            'estaciones' => $estaciones,
            'condiciones' => $condiciones,
            'page_title' => 'Lotes de Ganado',
            'csrf'       => $this->generateCsrf()
        ]);
    }
    
    public function crearAction() {
        $estaciones = Estacion::all();
        $condiciones = CondicionGanado::all();
        
        $this->render('lotes.crear', [
            'estaciones'  => $estaciones,
            'condiciones' => $condiciones,
            'page_title'  => 'Registrar Lote',
            'csrf'        => $this->generateCsrf()
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
        
        $cabezas       = intval($this->getPost('cabezas', 0));
        $peso_promedio = floatval($this->getPost('peso_promedio_kg', 0));
        $condicion_id  = intval($this->getPost('condicion_id', 0));
        $estacion_id   = intval($this->getPost('estacion_id', 0));
        
        if ($cabezas <= 0 || $peso_promedio <= 0) {
            Session::flash('error', 'Valores inválidos');
            $this->redirect('/lote/crear');
        }
        
        $lote = new LoteGanado();
        $lote->cabezas         = $cabezas;
        $lote->peso_promedio_kg = $peso_promedio;
        $lote->condicion_id    = $condicion_id;
        $lote->estacion_id     = $estacion_id;
        $lote->hora_salida     = '20:00:00';
        $lote->fecha_registro  = date('Y-m-d');
        $lote->activo          = 1;
        $lote->created_at      = date('Y-m-d H:i:s');
        
        try { $saveOk = $lote->save(); } catch (\Exception $e) {
            error_log('LoteController save error: ' . $e->getMessage());
            $saveOk = false;
        }
        if ($saveOk) {
            $simplex   = new SimplexSolver();
            $resultado = $simplex->optimizar(
                $lote->id,
                $cabezas,
                $peso_promedio,
                $condicion_id,
                $estacion_id,
                $lote->hora_salida
            );
            
            if ($resultado['factible'] && isset($resultado['detalles']['ruta_optima'])) {
                $ruta_optima_id = intval($resultado['detalles']['ruta_optima']);
                $this->db->query(
                    'UPDATE lotes_ganado SET ruta_optima_id = ? WHERE id = ?',
                    [$ruta_optima_id, $lote->id]
                );
            }
            
            if ($resultado['factible']) {
                $this->db->query(
                    'INSERT INTO resultados_optimizacion (lote_id, fecha_calculo, x1, x2, x3, x4, ganancia_total, factible, datos_json)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $lote->id,
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
            Session::flash('error', 'Ocurrió un error al guardar. Intente nuevamente.');
            $this->redirect('/lote/crear');
        }
    }
    
    public function resultadoAction() {
        $resultado = Session::get('resultado_optimizacion', []);

        // CAMBIO 4: Si no hay datos de sesión, redirigir al formulario
        if (empty($resultado)) {
            Session::flash('info', 'Primero registra un lote para ver el resultado.');
            $this->redirect('/lote/crear');
        }

        Session::remove('resultado_optimizacion');
        
        $this->render('lotes.resultado', [
            'resultado'  => $resultado,
            'page_title' => 'Resultado Optimización',
            'csrf'       => $this->generateCsrf()
        ]);
    }

    public function editarAction($id) {
        $id   = intval($id);
        $lote = LoteGanado::findById($id);

        if (!$lote) {
            Session::flash('error', 'Lote no encontrado');
            $this->redirect('/lote/index');
        }

        $estaciones  = Estacion::all();
        $condiciones = CondicionGanado::all();

        $this->render('lotes.editar', [
            'lote'        => $lote,
            'estaciones'  => $estaciones,
            'condiciones' => $condiciones,
            'page_title'  => 'Editar Lote',
            'csrf'        => $this->generateCsrf()
        ]);
    }

    public function actualizarAction($id) {
        $id = intval($id);

        if (!$this->isPost()) {
            $this->redirect('/lote/index');
        }

        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/lote/editar/' . $id);
        }

        $cabezas      = intval($this->getPost('cabezas', 0));
        $peso         = floatval($this->getPost('peso_promedio_kg', 0));
        $condicion_id = intval($this->getPost('condicion_id', 0));
        $estacion_id  = intval($this->getPost('estacion_id', 0));

        if ($cabezas <= 0 || $peso <= 0) {
            Session::flash('error', 'Valores inválidos');
            $this->redirect('/lote/editar/' . $id);
        }

        $sql = 'UPDATE lotes_ganado SET cabezas = ?, peso_promedio_kg = ?, condicion_id = ?, estacion_id = ? WHERE id = ?';

        if ($this->db->query($sql, [$cabezas, $peso, $condicion_id, $estacion_id, $id])) {
            Session::flash('success', 'Lote actualizado correctamente');
        } else {
            Session::flash('error', 'Error al actualizar el lote');
        }

        $this->redirect('/lote/index');
    }

    public function eliminarAction($id) {
        $id = intval($id);

        if (!$this->isPost()) {
            $this->redirect('/lote/index');
        }

        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/lote/index');
        }

        $sql = 'UPDATE lotes_ganado SET activo = 0 WHERE id = ?';

        if ($this->db->query($sql, [$id])) {
            Session::flash('success', 'Lote eliminado correctamente');
        } else {
            Session::flash('error', 'Error al eliminar el lote');
        }

        $this->redirect('/lote/index');
    }
}