<?php
namespace App\Models;

use App\Model;

class CostoFlete extends Model {
    protected $table = 'costos_flete';
    protected $fillable = ['ruta_id', 'costo_cabeza', 'semana_inicio', 'activo', 'created_at'];
    
    public static function getCostosActivos() {
        $instance = new static();
        $sql = 'SELECT cf.*, r.codigo, r.nombre FROM ' . $instance->table . ' cf
                JOIN rutas r ON cf.ruta_id = r.id
                WHERE cf.activo = 1 ORDER BY cf.semana_inicio DESC';
        return $instance->db->fetchAll($sql);
    }
    
    public static function getCostoPorRuta($ruta_id) {
        $instance = new static();
        $sql = 'SELECT costo_cabeza FROM ' . $instance->table . ' 
                WHERE ruta_id = ? AND activo = 1 ORDER BY semana_inicio DESC LIMIT 1';
        $data = $instance->db->fetch($sql, [$ruta_id]);
        
        return $data ? floatval($data['costo_cabeza']) : 0;
    }
}
