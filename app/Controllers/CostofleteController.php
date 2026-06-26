<?php
namespace App\Controllers;

use App\Controller;
use App\Models\{CostoFlete, Ruta};
use App\Session;

class CostoFleteController extends Controller {
    
    public function indexAction() {
        $rutas = Ruta::getActivas();
        
        $sql = 'SELECT cf.*, r.codigo, r.nombre FROM costos_flete cf
                JOIN rutas r ON cf.ruta_id = r.id
                WHERE cf.activo = 1 ORDER BY cf.semana_inicio DESC';
        $costos = $this->db->getConnection()->query($sql)->fetchAll();
        
        $historial = CostoFlete::getAllHistory();
        $kpi = CostoFlete::getKpiData();
        
        $this->render('costos.index', [
            'costos'     => $costos,
            'rutas'      => $rutas,
            'historial'  => $historial,
            'kpi'        => $kpi,
            'page_title' => 'Costos de Flete',
            'csrf'       => $this->generateCsrf()
        ]);
    }
    
    public function actualizarAction() {
        if (!$this->isPost()) {
            $this->redirect('/costoflete/index');
        }
        
        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/costoflete/index');
        }
        
        $ruta_id = intval($this->getPost('ruta_id', 0));
        $costo_cabeza = floatval($this->getPost('costo_cabeza', 0));
        
        if ($costo_cabeza <= 0) {
            Session::flash('error', 'Costo debe ser positivo');
            $this->redirect('/costoflete/index');
        }
        
        // Desactivar costos anteriores de la ruta
        $sql_deactivate = 'UPDATE costos_flete SET activo = 0 WHERE ruta_id = ?';
        $this->db->query($sql_deactivate, [$ruta_id]);
        
        $costo = new CostoFlete();
        $costo->ruta_id = $ruta_id;
        $costo->costo_cabeza = $costo_cabeza;
        $costo->semana_inicio = date('Y-m-d');
        $costo->activo = 1;
        $costo->created_at = date('Y-m-d H:i:s');
        
        if ($costo->save()) {
            Session::flash('success', 'Costo de flete actualizado');
        } else {
            Session::flash('error', 'Error al actualizar costo');
        }
        
        $this->redirect('/costoflete/index');
    }
}