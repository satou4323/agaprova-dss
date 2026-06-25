<?php
namespace App\Models;

use App\Model;

class LoteGanado extends Model {
    protected $table = 'lotes_ganado';
    protected $fillable = ['cabezas', 'peso_promedio_kg', 'condicion_id', 'estacion_id', 'usuario_id', 'ruta_optima_id', 'hora_salida', 'fecha_registro', 'activo', 'created_at'];
    
    public static function getActivos() {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE activo = 1 ORDER BY fecha_registro DESC';
        $data = $instance->db->fetchAll($sql);
        
        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }
    
    public static function getUltimo() {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE activo = 1 ORDER BY created_at DESC LIMIT 1';
        $data = $instance->db->fetch($sql);
        
        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }
    
    public static function getActivosConRuta() {
        $instance = new static();
        $sql = 'SELECT l.*, r.codigo as ruta_codigo, r.nombre as ruta_nombre 
                FROM ' . $instance->table . ' l
                LEFT JOIN rutas r ON l.ruta_optima_id = r.id
                WHERE l.activo = 1 ORDER BY l.fecha_registro DESC';
        $data = $instance->db->fetchAll($sql);
        
        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }

    public static function findById($id) {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE id = ? AND activo = 1 LIMIT 1';
        $data = $instance->db->fetch($sql, [intval($id)]);

        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }

    public static function getUltimos($limite = 5) {
        $instance = new static();
        $sql = 'SELECT l.*, r.codigo as ruta_codigo, r.nombre as ruta_nombre
                FROM ' . $instance->table . ' l
                LEFT JOIN rutas r ON l.ruta_optima_id = r.id
                WHERE l.activo = 1
                ORDER BY l.id DESC
                LIMIT ' . intval($limite);
        $data = $instance->db->fetchAll($sql);

        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }
}