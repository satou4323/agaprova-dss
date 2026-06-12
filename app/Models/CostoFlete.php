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
    
    public static function getAllHistory() {
        $instance = new static();
        $sql = 'SELECT cf.*, r.codigo, r.nombre FROM ' . $instance->table . ' cf
                JOIN rutas r ON cf.ruta_id = r.id
                ORDER BY cf.created_at DESC, cf.semana_inicio DESC';
        return $instance->db->fetchAll($sql);
    }

    public static function getKpiData() {
        $instance = new static();
        $sql = 'SELECT 
                    ROUND(AVG(cf.costo_cabeza), 2) as costo_promedio,
                    MAX(cf.costo_cabeza) as costo_max,
                    MIN(cf.costo_cabeza) as costo_min,
                    COUNT(*) as total_cambios
                FROM ' . $instance->table . ' cf
                WHERE cf.activo = 1';
        $kpi = $instance->db->fetch($sql);

        $sql_max = 'SELECT cf.costo_cabeza, r.codigo, r.nombre FROM ' . $instance->table . ' cf
                    JOIN rutas r ON cf.ruta_id = r.id
                    WHERE cf.activo = 1 ORDER BY cf.costo_cabeza DESC LIMIT 1';
        $ruta_max = $instance->db->fetch($sql_max);

        $sql_min = 'SELECT cf.costo_cabeza, r.codigo, r.nombre FROM ' . $instance->table . ' cf
                    JOIN rutas r ON cf.ruta_id = r.id
                    WHERE cf.activo = 1 ORDER BY cf.costo_cabeza ASC LIMIT 1';
        $ruta_min = $instance->db->fetch($sql_min);

        return [
            'costo_promedio' => $kpi ? floatval($kpi['costo_promedio']) : 0,
            'costo_max' => $kpi ? floatval($kpi['costo_max']) : 0,
            'costo_min' => $kpi ? floatval($kpi['costo_min']) : 0,
            'total_cambios' => $kpi ? intval($kpi['total_cambios']) : 0,
            'ruta_max_codigo' => $ruta_max ? $ruta_max['codigo'] : '—',
            'ruta_max_nombre' => $ruta_max ? $ruta_max['nombre'] : '—',
            'ruta_max_costo' => $ruta_max ? floatval($ruta_max['costo_cabeza']) : 0,
            'ruta_min_codigo' => $ruta_min ? $ruta_min['codigo'] : '—',
            'ruta_min_nombre' => $ruta_min ? $ruta_min['nombre'] : '—',
            'ruta_min_costo' => $ruta_min ? floatval($ruta_min['costo_cabeza']) : 0,
        ];
    }

    public static function getCostoPorRuta($ruta_id) {
        $instance = new static();
        $sql = 'SELECT costo_cabeza FROM ' . $instance->table . ' 
                WHERE ruta_id = ? AND activo = 1 ORDER BY semana_inicio DESC LIMIT 1';
        $data = $instance->db->fetch($sql, [$ruta_id]);
        
        return $data ? floatval($data['costo_cabeza']) : 0;
    }
}
