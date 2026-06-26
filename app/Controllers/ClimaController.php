<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Clima;
use App\Services\ClimaService;
use App\Session;

class ClimaController extends Controller {
    
    public function indexAction() {
        $clima_actual = Clima::getActivo();
        
        $sql = 'SELECT * FROM clima WHERE activo = 1 ORDER BY fecha_registro DESC LIMIT 10';
        $historico = $this->db->getConnection()->query($sql)->fetchAll();
        
        $this->render('clima.index', [
            'clima_actual' => $clima_actual,
            'historico'    => $historico,
            'page_title'   => 'Clima',
            'csrf'         => $this->generateCsrf()
        ]);
    }
    
    public function actualizarAction() {
        if (!$this->isPost()) {
            $this->redirect('/clima/index');
        }
        
        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/clima/index');
        }
        
        $probabilidad = floatval($this->getPost('probabilidad_lluvia', 0));
        
        if ($probabilidad < 0 || $probabilidad > 1) {
            Session::flash('error', 'Probabilidad debe estar entre 0 y 1');
            $this->redirect('/clima/index');
        }
        
        try {
            $ok = ClimaService::updateClima($probabilidad);
        } catch (\Exception $e) {
            error_log('ClimaController update error: ' . $e->getMessage());
            $ok = false;
        }
        if ($ok) {
            $interpretacion = ClimaService::getInterpretacion($probabilidad);
            Session::flash('success', 'Clima actualizado. ' . $interpretacion);
        } else {
            Session::flash('error', 'Ocurrió un error al actualizar. Intente nuevamente.');
        }
        
        $this->redirect('/clima/index');
    }
}