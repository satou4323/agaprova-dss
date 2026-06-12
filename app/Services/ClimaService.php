<?php
namespace App\Services;

use App\Models\Clima;

class ClimaService {
    
    public static function updateClima($probabilidad_lluvia, $ubicacion = 'Abapo') {
        // Desactivar clima anterior
        $db = \App\Database::getInstance();
        $db->query('UPDATE clima SET activo = 0 WHERE ubicacion = ?', [$ubicacion]);
        
        // Crear nuevo registro climático
        $clima = new Clima();
        $clima->probabilidad_lluvia = $probabilidad_lluvia;
        $clima->ubicacion = $ubicacion;
        $clima->fecha_registro = date('Y-m-d');
        $clima->activo = 1;
        $clima->created_at = date('Y-m-d H:i:s');
        
        return $clima->save();
    }
    
    public static function getInterpretacion($probabilidad) {
        if ($probabilidad < 0.20) {
            return 'Condiciones claras';
        } elseif ($probabilidad < 0.40) {
            return 'Pocas probabilidades de lluvia';
        } elseif ($probabilidad < 0.60) {
            return 'Probabilidad moderada de lluvia';
        } elseif ($probabilidad < 0.80) {
            return 'Alta probabilidad de lluvia';
        } else {
            return 'Lluvia segura';
        }
    }
    
    public static function isRuta3Bloqueada($probabilidad_lluvia) {
        return $probabilidad_lluvia >= 0.40;
    }
}
