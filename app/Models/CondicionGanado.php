<?php
namespace App\Models;

use App\Model;

class CondicionGanado extends Model {
    protected $table = 'condiciones_ganado';
    protected $fillable = ['nombre', 'factor', 'descripcion'];
    
    public static function getFactorPorId($id) {
        $instance = new static();
        $sql = 'SELECT factor FROM ' . $instance->table . ' WHERE id = ?';
        $data = $instance->db->fetch($sql, [$id]);
        
        return $data ? floatval($data['factor']) : 1.0;
    }
}
