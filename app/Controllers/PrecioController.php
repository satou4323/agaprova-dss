<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Precio;
use App\Session;

class PrecioController extends Controller {
    
    public function indexAction() {
        $sql = 'SELECT p.*, m.nombre as mercado_nombre FROM precios p
                JOIN mercados m ON p.mercado_id = m.id
                WHERE p.activo = 1 ORDER BY p.mercado_id, p.fecha_registro DESC';
        
        $precios_raw = $this->db->getConnection()->query($sql)->fetchAll();

        // Calcular delta (variación vs precio anterior) y mejor precio
        $precios = [];
        $mejor_precio = null;
        $max_precio = -1;
        $previos = []; // cache de precios anteriores por mercado

        foreach ($precios_raw as $p) {
            $mercado_id = $p['mercado_id'];
            if (!isset($previos[$mercado_id])) {
                $sql_prev = 'SELECT precio_kg FROM precios WHERE mercado_id = ? AND activo = 0 ORDER BY fecha_registro DESC, id DESC LIMIT 1';
                $prev = $this->db->fetch($sql_prev, [$mercado_id]);
                $previos[$mercado_id] = $prev ? floatval($prev['precio_kg']) : null;
            }
            $precio_anterior = $previos[$mercado_id];
            $p['delta'] = $precio_anterior !== null ? round($p['precio_kg'] - $precio_anterior, 2) : 0;
            $precios[] = $p;

            if ($p['precio_kg'] > $max_precio) {
                $max_precio = $p['precio_kg'];
                $mejor_precio = $p;
            }
        }

        $sql_mercados = 'SELECT * FROM mercados WHERE activo = 1';
        $mercados = $this->db->getConnection()->query($sql_mercados)->fetchAll();

        $sql_hist = 'SELECT p.precio_kg, p.fecha_registro, m.nombre as mercado_nombre 
                     FROM precios p
                     JOIN mercados m ON p.mercado_id = m.id
                     ORDER BY p.fecha_registro DESC LIMIT 50';
        $hist_data_raw = $this->db->getConnection()->query($sql_hist)->fetchAll();

        $sql_hist_chart = 'SELECT p.precio_kg, p.fecha_registro, m.nombre as mercado_nombre 
                           FROM precios p
                           JOIN mercados m ON p.mercado_id = m.id
                           ORDER BY p.fecha_registro ASC LIMIT 50';
        $hist_data = $this->db->getConnection()->query($sql_hist_chart)->fetchAll();
        
        $this->render('precios.index', [
            'page_title'    => 'Precios de Mercado',
            'precios' => $precios,
            'mercados' => $mercados,
            'hist_data' => json_encode($hist_data),
            'hist_data_raw' => $hist_data_raw,
            'mejor_precio' => $mejor_precio,
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