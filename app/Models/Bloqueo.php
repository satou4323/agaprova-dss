<?php
namespace App\Models;

use App\Model;

class Bloqueo extends Model {
    protected $table = 'bloqueos';
    protected $fillable = ['ruta_id', 'activo', 'fecha_inicio', 'fecha_fin', 'created_at'];
    
    public static function getBloqueosActivos() {
        $instance = new static();
        $sql = 'SELECT b.*, r.codigo, r.nombre FROM ' . $instance->table . ' b
                JOIN rutas r ON b.ruta_id = r.id
                WHERE b.activo = 1 ORDER BY b.fecha_inicio DESC';
        return $instance->db->fetchAll($sql);
    }
    
    public static function estasBloqueada($ruta_id) {
        $instance = new static();
        $sql = 'SELECT activo FROM ' . $instance->table . ' 
                WHERE ruta_id = ? AND activo = 1 LIMIT 1';
        $data = $instance->db->fetch($sql, [$ruta_id]);
        
        return $data ? true : false;
    }
}
