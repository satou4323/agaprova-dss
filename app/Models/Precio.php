<?php
namespace App\Models;

use App\Model;

class Precio extends Model {
    protected $table = 'precios';
    protected $fillable = ['mercado_id', 'precio_kg', 'fecha_registro', 'activo', 'created_at'];
    
    public static function getPreciosActivos() {
        $instance = new static();
        $sql = 'SELECT p.*, m.nombre as mercado_nombre FROM ' . $instance->table . ' p
                JOIN mercados m ON p.mercado_id = m.id
                WHERE p.activo = 1 ORDER BY p.fecha_registro DESC';
        return $instance->db->fetchAll($sql);
    }
    
    public static function getPorMercado($mercado_id) {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE mercado_id = ? AND activo = 1 ORDER BY fecha_registro DESC LIMIT 1';
        $data = $instance->db->fetch($sql, [$mercado_id]);
        
        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }
}
