<?php
namespace App\Controllers;

use App\Controller;
use App\Models\{Precio, Mercado};
use App\Session;

class PrecioController extends Controller {
    
    public function indexAction() {
        $sql = 'SELECT p.*, m.nombre as mercado_nombre FROM precios p
                JOIN mercados m ON p.mercado_id = m.id
                WHERE p.activo = 1 ORDER BY p.fecha_registro DESC';
        
        $precios = $this->db->getConnection()->query($sql)->fetchAll();
        
        $sql_mercados = 'SELECT * FROM mercados WHERE activo = 1';
        $mercados = $this->db->getConnection()->query($sql_mercados)->fetchAll();

        $sql_hist = 'SELECT p.precio_kg, p.fecha_registro, m.nombre as mercado_nombre 
                     FROM precios p
                     JOIN mercados m ON p.mercado_id = m.id
                     ORDER BY p.fecha_registro ASC LIMIT 50';
        $hist_data = $this->db->getConnection()->query($sql_hist)->fetchAll();
        
        $this->render('precios.index', [
            'precios' => $precios,
            'mercados' => $mercados,
            'hist_data' => json_encode($hist_data),
            'csrf' => $this->generateCsrf()
        ]);
    }
    
    public function actualizarAction() {
        if (!$this->isPost()) {
            $this->redirect('/precio/index');
        }
        
        if (!$this->validateCsrf($this->getPost('csrf_token'))) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/precio/index');
        }
        
        $mercado_id = intval($this->getPost('mercado_id', 0));
        $precio_kg = floatval($this->getPost('precio_kg', 0));
        
        if ($precio_kg <= 0) {
            Session::flash('error', 'Precio debe ser positivo');
            $this->redirect('/precio/index');
        }
        
        // Desactivar precios anteriores del mismo mercado
        $sql_deactivate = 'UPDATE precios SET activo = 0 WHERE mercado_id = ?';
        $this->db->query($sql_deactivate, [$mercado_id]);
        
        $precio = new Precio();
        $precio->mercado_id = $mercado_id;
        $precio->precio_kg = $precio_kg;
        $precio->fecha_registro = date('Y-m-d');
        $precio->activo = 1;
        $precio->created_at = date('Y-m-d H:i:s');
        
        if ($precio->save()) {
            Session::flash('success', 'Precio actualizado correctamente');
        } else {
            Session::flash('error', 'Error al actualizar precio');
        }
        
        $this->redirect('/precio/index');
    }
}
