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
            $ruta->id           = $row['id'];
            $ruta->codigo       = $row['codigo'];
            $ruta->nombre       = $row['nombre'];
            $ruta->origen       = $row['origen'];
            $ruta->destino      = $row['destino'];
            $ruta->tiempo_horas = $row['tiempo_horas'];
            $ruta->tipo_via     = $row['tipo_via'];
            $ruta->bloqueado    = $row['bloqueado'];
            $rutas[] = $ruta;
        }
        
        $sql = 'SELECT b.*, r.codigo, r.nombre FROM bloqueos b
                JOIN rutas r ON b.ruta_id = r.id
                ORDER BY b.fecha_inicio DESC';
        $bloqueos = $this->db->getConnection()->query($sql)->fetchAll();
        
        $this->render('bloqueos.index', [
            'bloqueos'   => $bloqueos,
            'rutas'      => $rutas,
            'page_title' => 'Rutas',
            'csrf'       => $this->generateCsrf()
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
        $accion  = trim($this->getPost('accion', 'activar'));
        
        if ($accion === 'activar') {
            $sql = 'SELECT id FROM bloqueos WHERE ruta_id = ? LIMIT 1';
            $existe = $this->db->fetch($sql, [$ruta_id]);
            
            if ($existe) {
                $sql = 'UPDATE bloqueos SET activo = 1, fecha_inicio = NOW() WHERE ruta_id = ?';
            } else {
                $sql = 'INSERT INTO bloqueos (ruta_id, activo, fecha_inicio) VALUES (?, 1, NOW())';
            }
        } else {
            $sql = 'UPDATE bloqueos SET activo = 0, fecha_fin = NOW() WHERE ruta_id = ?';
        }
        
        if ($this->db->query($sql, [$ruta_id])) {
            Session::flash('success', 'Bloqueo actualizado');
        } else {
            Session::flash('error', 'Error al actualizar bloqueo');
        }
        
        $this->redirect('/bloqueo/index');
    }

    public function crearAction() {
        $this->render('bloqueos.crear', [
            'csrf' => $this->generateCsrf()
        ]);
    }

    public function eliminarAction() {
        if (!$this->isPost()) {
            $this->redirect('/bloqueo/index');
        }

        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/bloqueo/index');
        }

        $ruta_id = intval($this->getPost('ruta_id', 0));

        if ($ruta_id <= 0) {
            Session::flash('error', 'Ruta no válida');
            $this->redirect('/bloqueo/index');
        }

        // Eliminar registros asociados primero
        $this->db->query('DELETE FROM costos_flete WHERE ruta_id = ?', [$ruta_id]);
        $this->db->query('DELETE FROM bloqueos WHERE ruta_id = ?', [$ruta_id]);
        // Eliminar la ruta
        $this->db->query('DELETE FROM rutas WHERE id = ?', [$ruta_id]);

        Session::flash('success', 'Ruta eliminada correctamente');
        $this->redirect('/bloqueo/index');
    }

    public function guardarAction() {
        if (!$this->isPost()) {
            $this->redirect('/bloqueo/index');
        }

        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/bloqueo/crear');
        }

        $nombre     = trim($this->getPost('nombre', ''));
        $codigo     = strtoupper(trim($this->getPost('codigo', '')));
        $origen     = trim($this->getPost('origen', ''));
        $destino    = trim($this->getPost('destino', ''));
        $tiempo     = floatval($this->getPost('tiempo_horas', 0));
        $tipo_via   = trim($this->getPost('tipo_via', ''));
        $mercado_id = intval($this->getPost('mercado_id', 0)) ?: null;

        if (empty($nombre) || empty($codigo) || empty($origen) || empty($destino) || $tiempo <= 0) {
            Session::flash('error', 'Todos los campos son obligatorios');
            $this->redirect('/bloqueo/crear');
        }

        $sql = 'INSERT INTO rutas (codigo, nombre, origen, destino, tiempo_horas, tipo_via, mercado_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)';

        if ($this->db->query($sql, [$codigo, $nombre, $origen, $destino, $tiempo, $tipo_via, $mercado_id])) {
            Session::flash('success', 'Ruta creada. Ahora configure el costo de flete para esta ruta.');
            $this->redirect('/costoflete/index');
        } else {
            Session::flash('error', 'Error al registrar la ruta');
            $this->redirect('/bloqueo/index');
        }
    }
}
