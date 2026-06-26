<?php
namespace App\Models;

use App\Model;

class Ruta extends Model {
    protected $table = 'rutas';
    protected $fillable = ['codigo', 'nombre', 'origen', 'destino', 'mercado_id', 'tipo_via', 'tiempo_horas', 'activo'];
    
    public static function getActivas() {
        $instance = new static();
        $sql = 'SELECT r.*, m.nombre as mercado_nombre FROM ' . $instance->table . ' r
                LEFT JOIN mercados m ON r.mercado_id = m.id
                WHERE r.activo = 1 ORDER BY r.codigo';
        $data = $instance->db->fetchAll($sql);
        
        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }
    
    public static function getWithBloqueo() {
        $instance = new static();
        $sql = 'SELECT r.*, m.nombre as mercado_nombre, 
                COALESCE(b.activo, 0) as bloqueado
                FROM ' . $instance->table . ' r
                LEFT JOIN mercados m ON r.mercado_id = m.id
                LEFT JOIN bloqueos b ON r.id = b.ruta_id AND b.activo = 1
                WHERE r.activo = 1 ORDER BY r.codigo';
        return $instance->db->fetchAll($sql);
    }
}
