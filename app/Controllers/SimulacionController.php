<?php
namespace App\Controllers;

use App\Controller;
use App\Models\{LoteGanado, Ruta, Estacion, CondicionGanado, Clima};
use App\Services\SimplexSolver;
use App\Session;

class SimulacionController extends Controller {

    public function indexAction() {
        // Usar getWithBloqueo() que sí existe en el modelo Ruta
        $rutas_raw      = Ruta::getWithBloqueo();
        $rutas          = [];
        foreach ($rutas_raw as $row) {
            $obj               = new \stdClass();
            $obj->codigo       = $row['codigo'];
            $obj->nombre       = $row['nombre'];
            $obj->tiempo_horas = $row['tiempo_horas'];
            $obj->bloqueado    = $row['bloqueado'];
            $rutas[]           = $obj;
        }

        $ultimo_lote     = LoteGanado::getUltimo();
        $clima           = Clima::getActivo();
        $condiciones     = CondicionGanado::all();
        $estaciones      = Estacion::all();
        $costos_vigentes = $this->getCostosVigentes();

        $this->render('simulacion.index', [
            'rutas'           => $rutas,
            'ultimo_lote'     => $ultimo_lote,
            'clima'           => $clima,
            'condiciones'     => $condiciones,
            'estaciones'      => $estaciones,
            'costos_vigentes' => $costos_vigentes,
            'page_title'      => 'Simulaciones',
            'csrf'            => $this->generateCsrf()
        ]);
    }

    public function ejecutarAction() {
        if (!$this->isPost()) {
            $this->redirect('/simulacion/index');
        }

        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/simulacion/index');
        }

        $cabezas      = intval($this->getPost('cabezas', CABEZAS_DEFAULT));
        $peso         = floatval($this->getPost('peso_promedio_kg', PESO_DEFAULT));
        $condicion_id = intval($this->getPost('condicion_id', 1));
        $estacion_id  = intval($this->getPost('estacion_id', 1));

        if ($cabezas <= 0 || $peso <= 0) {
            Session::flash('error', 'Valores inválidos');
            $this->redirect('/simulacion/index');
        }

        $simplex   = new SimplexSolver();
        $resultado = $simplex->optimizar(
            0,
            $cabezas,
            $peso,
            $condicion_id,
            $estacion_id,
            '20:00:00'
        );

        $this->render('simulacion.resultado', [
            'resultado' => $resultado,
            'csrf'      => $this->generateCsrf()
        ]);
    }

    private function getCostosVigentes() {
        $sql = 'SELECT cf.costo_cabeza, r.codigo, r.nombre as nombre_ruta
                FROM costos_flete cf
                JOIN rutas r ON cf.ruta_id = r.id
                WHERE cf.activo = 1
                ORDER BY r.codigo ASC';
        $data = $this->db->fetchAll($sql);

        $costos = [];
        foreach ($data as $row) {
            $obj               = new \stdClass();
            $obj->codigo       = $row['codigo'];
            $obj->nombre_ruta  = $row['nombre_ruta'];
            $obj->costo_cabeza = $row['costo_cabeza'];
            $costos[]          = $obj;
        }
        return $costos;
    }
}