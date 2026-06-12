<?php
namespace App\Models;

use App\Model;

class EscenarioSimulacion extends Model {
    protected $table = 'escenarios_simulacion';
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'datos_json', 'created_at'];
    
    public static function findByCodigo($codigo) {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE codigo = ?';
        $data = $instance->db->fetch($sql, [$codigo]);
        
        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }
    
    public static function getEscenarios() {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' ORDER BY codigo ASC';
        $data = $instance->db->fetchAll($sql);
        
        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }
    
    public function getDatos() {
        return json_decode($this->attributes['datos_json'] ?? '{}', true);
    }
}
