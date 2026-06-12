<?php
namespace App\Controllers;

use App\Controller;
use App\Models\{Bloqueo, Ruta};
use App\Session;

class BloqueoController extends Controller {
    
    public function indexAction() {
        $rutas_obj = Ruta::getWithBloqueo();
        $rutas = [];
        foreach ($rutas_obj as $row) {
            $ruta = new \stdClass();
            $ruta->id = $row['id'];
            $ruta->codigo = $row['codigo'];
            $ruta->nombre = $row['nombre'];
            $ruta->origen = $row['origen'];
            $ruta->destino = $row['destino'];
            $ruta->tiempo_horas = $row['tiempo_horas'];
            $ruta->tipo_via = $row['tipo_via'];
            $ruta->bloqueado = $row['bloqueado'];
            $rutas[] = $ruta;
        }
        
        $sql = 'SELECT b.*, r.codigo, r.nombre FROM bloqueos b
                JOIN rutas r ON b.ruta_id = r.id
                ORDER BY b.fecha_inicio DESC';
        $bloqueos = $this->db->getConnection()->query($sql)->fetchAll();
        
        $this->render('bloqueos.index', [
            'bloqueos' => $bloqueos,
            'rutas' => $rutas,
            'csrf' => $this->generateCsrf()
        ]);
    }
    
    public function toggleAction() {
        if (!$this->isPost()) {
            $this->redirect('/bloqueo/index');
        }
        
        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/bloqueo/index');
        }
        
        $ruta_id = intval($this->getPost('ruta_id', 0));
        $accion = trim($this->getPost('accion', 'activar'));
        
        if ($accion === 'activar') {
            // Verificar si existe bloqueo
            $sql = 'SELECT id FROM bloqueos WHERE ruta_id = ? LIMIT 1';
            $existe = $this->db->fetch($sql, [$ruta_id]);
            
            if ($existe) {
                $sql = 'UPDATE bloqueos SET activo = 1 WHERE ruta_id = ?';
            } else {
                $sql = 'INSERT INTO bloqueos (ruta_id, activo, fecha_inicio) VALUES (?, 1, CURDATE())';
            }
        } else {
            $sql = 'UPDATE bloqueos SET activo = 0 WHERE ruta_id = ?';
        }
        
        if ($this->db->query($sql, [$ruta_id])) {
            Session::flash('success', 'Bloqueo actualizado');
        } else {
            Session::flash('error', 'Error al actualizar bloqueo');
        }
        
        $this->redirect('/bloqueo/index');
    }
}
