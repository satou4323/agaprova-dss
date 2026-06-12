<?php
namespace App\Models;

use App\Model;

class Clima extends Model {
    protected $table = 'clima';
    protected $fillable = ['probabilidad_lluvia', 'ubicacion', 'estacion_id', 'fecha_registro', 'activo', 'created_at'];
    
    public static function getActivo() {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE activo = 1 ORDER BY fecha_registro DESC LIMIT 1';
        $data = $instance->db->fetch($sql);
        
        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }
    
    public static function getProbabilidadLluvia() {
        $clima = self::getActivo();
        return $clima ? floatval($clima->probabilidad_lluvia) : 0.10;
    }
}
