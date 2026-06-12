<?php
namespace App\Services;

use App\Models\{Precio, CostoFlete, Bloqueo, CondicionGanado, Estacion, Clima};

class SimplexSolver {

    public function optimizar($lote_id, $cabezas, $peso_promedio, $condicion_id, $estacion_id, $hora_salida, $parametros_escenario = []) {
        $resultado = [
            'factible' => false,
            'x1' => 0, 'x2' => 0, 'x3' => 0, 'x4' => 0,
            'ganancia_total' => 0,
            'detalles' => [],
            'diagnostico' => []
        ];

        $diagnostico = [];

        // Validar restricción horaria
        $rutas_activas = $this->validarRestricciones($hora_salida);

        if (empty($rutas_activas)) {
            $diagnostico[] = 'Ninguna ruta cumple la restricción horaria (llegada antes de 08:00). Verifique la hora de salida.';
            $resultado['detalles']['diagnostico'] = $diagnostico;
            return $resultado;
        }

        // Obtener factores de eficiencia
        $factor_estacion = Estacion::getFactorPorId($estacion_id);
        $factor_condicion = CondicionGanado::getFactorPorId($condicion_id);
        $eficiencia_efectiva = $peso_promedio * $factor_estacion * $factor_condicion;

        // Obtener precios (con sobreescritura por escenario si aplica)
        $precio_sc = floatval(Precio::getPorMercado(1)?->precio_kg ?? 32);
        $precio_cb = floatval(Precio::getPorMercado(2)?->precio_kg ?? 34);
        if (isset($parametros_escenario['precio_sc'])) {
            $precio_sc = floatval($parametros_escenario['precio_sc']);
        }
        if (isset($parametros_escenario['precio_cb'])) {
            $precio_cb = floatval($parametros_escenario['precio_cb']);
        }

        // Obtener costos (con sobreescritura por escenario si aplica)
        $costo_c1 = CostoFlete::getCostoPorRuta(1);
        $costo_c2 = CostoFlete::getCostoPorRuta(2);
        $costo_c3 = CostoFlete::getCostoPorRuta(3);
        $costo_c4 = CostoFlete::getCostoPorRuta(4);
        if (isset($parametros_escenario['costo_c1'])) {
            $costo_c1 = floatval($parametros_escenario['costo_c1']);
        }
        if (isset($parametros_escenario['costo_c2'])) {
            $costo_c2 = floatval($parametros_escenario['costo_c2']);
        }
        if (isset($parametros_escenario['costo_c3'])) {
            $costo_c3 = floatval($parametros_escenario['costo_c3']);
        }
        if (isset($parametros_escenario['costo_c4'])) {
            $costo_c4 = floatval($parametros_escenario['costo_c4']);
        }

        // Restricción climática (prob_lluvia >= 0.40 bloquea ruta 3)
        $prob_lluvia = Clima::getProbabilidadLluvia();
        if (isset($parametros_escenario['prob_lluvia'])) {
            $prob_lluvia = floatval($parametros_escenario['prob_lluvia']);
        }
        $ruta3_disponible = ($prob_lluvia < 0.40) ? 1 : 0;
        if ($ruta3_disponible == 0) {
            $diagnostico[] = 'Ruta 3 (Ipati-Abapó) bloqueada por clima: probabilidad de lluvia ' . number_format($prob_lluvia * 100, 0) . '% (máx. 40%).';
        }

        // Calcular márgenes (Mi = precio * eficiencia - costo)
        $m1 = ($precio_sc * $eficiencia_efectiva) - $costo_c1;
        $m2 = ($precio_cb * $eficiencia_efectiva) - $costo_c2;
        $m3 = ($precio_sc * $eficiencia_efectiva) - $costo_c3;
        $m4 = ($precio_cb * $eficiencia_efectiva) - $costo_c4;

        // Aplicar bloqueos de rutas (con sobreescritura por escenario si aplica)
        $bloqueo_bd_r1 = Bloqueo::estasBloqueada(1) ? 0 : 1;
        $bloqueo_bd_r2 = Bloqueo::estasBloqueada(2) ? 0 : 1;
        $bloqueo_bd_r3 = Bloqueo::estasBloqueada(3) ? 0 : 1;
        $bloqueo_bd_r4 = Bloqueo::estasBloqueada(4) ? 0 : 1;

        $bloqueo_r1 = $bloqueo_bd_r1;
        $bloqueo_r2 = $bloqueo_bd_r2;
        $bloqueo_r3 = $bloqueo_bd_r3;
        $bloqueo_r4 = $bloqueo_bd_r4;
        if (isset($parametros_escenario['bloqueo_r1'])) {
            $bloqueo_r1 = $parametros_escenario['bloqueo_r1'] ? 0 : 1;
        }
        if (isset($parametros_escenario['bloqueo_r2'])) {
            $bloqueo_r2 = $parametros_escenario['bloqueo_r2'] ? 0 : 1;
        }
        if (isset($parametros_escenario['bloqueo_r3'])) {
            $bloqueo_r3 = $parametros_escenario['bloqueo_r3'] ? 0 : 1;
        }
        if (isset($parametros_escenario['bloqueo_r4'])) {
            $bloqueo_r4 = $parametros_escenario['bloqueo_r4'] ? 0 : 1;
        }

        if ($bloqueo_r1 == 0 && $bloqueo_bd_r1 == 1) {
            $diagnostico[] = 'Ruta 1 (Samaipata) bloqueada por configuración del escenario.';
        } elseif ($bloqueo_r1 == 0) {
            $diagnostico[] = 'Ruta 1 (Samaipata) bloqueada en la base de datos.';
        }
        if ($bloqueo_r2 == 0 && $bloqueo_bd_r2 == 1) {
            $diagnostico[] = 'Ruta 2 (Comarapa) bloqueada por configuración del escenario.';
        } elseif ($bloqueo_r2 == 0) {
            $diagnostico[] = 'Ruta 2 (Comarapa) bloqueada en la base de datos.';
        }
        if ($bloqueo_r3 == 0 && $bloqueo_bd_r3 == 1) {
            $diagnostico[] = 'Ruta 3 (Ipati-Abapó) bloqueada por configuración del escenario.';
        } elseif ($bloqueo_r3 == 0 && $ruta3_disponible == 0) {
            // ya se agregó diagnóstico de clima arriba
        } elseif ($bloqueo_r3 == 0) {
            $diagnostico[] = 'Ruta 3 (Ipati-Abapó) bloqueada en la base de datos.';
        }
        if ($bloqueo_r4 == 0 && $bloqueo_bd_r4 == 1) {
            $diagnostico[] = 'Ruta 4 (Aiquile) bloqueada por configuración del escenario.';
        } elseif ($bloqueo_r4 == 0) {
            $diagnostico[] = 'Ruta 4 (Aiquile) bloqueada en la base de datos.';
        }

        // Restricción climática combinada con bloqueo
        $bloqueo_r3 = $bloqueo_r3 && $ruta3_disponible;

        // Verificar márgenes por ruta
        $margen_info = [];
        $margen_info[] = 'R1 (Samaipata→SC): margen Bs ' . number_format($m1, 2) . ($m1 <= 0 ? ' (pérdida)' : '');
        $margen_info[] = 'R2 (Comarapa→CB): margen Bs ' . number_format($m2, 2) . ($m2 <= 0 ? ' (pérdida)' : '');
        $margen_info[] = 'R3 (Ipati-Abapó→SC): margen Bs ' . number_format($m3, 2) . ($m3 <= 0 ? ' (pérdida)' : '');
        $margen_info[] = 'R4 (Aiquile→CB): margen Bs ' . number_format($m4, 2) . ($m4 <= 0 ? ' (pérdida)' : '');

        // Máscara de disponibilidad
        $disponibles = [
            1 => $bloqueo_r1 && in_array(1, $rutas_activas),
            2 => $bloqueo_r2 && in_array(2, $rutas_activas),
            3 => $bloqueo_r3 && in_array(3, $rutas_activas),
            4 => $bloqueo_r4 && in_array(4, $rutas_activas)
        ];

        // Diagnóstico de disponibilidad por ruta
        $rutas_nombre = [1 => 'R1 (Samaipata)', 2 => 'R2 (Comarapa)', 3 => 'R3 (Ipati-Abapó)', 4 => 'R4 (Aiquile)'];
        foreach ($disponibles as $r => $disp) {
            if (!$disp) {
                $razones = [];
                $r_bloqueo = ${'bloqueo_r' . $r};
                if (!$r_bloqueo) {
                    if ($r == 3 && $ruta3_disponible == 0) {
                        $razones[] = 'bloqueada por clima';
                    } else {
                        $razones[] = 'bloqueada';
                    }
                }
                if (!in_array($r, $rutas_activas)) {
                    $razones[] = 'no cumple restricción horaria';
                }
                $diagnostico[] = $rutas_nombre[$r] . ' no disponible: ' . implode(', ', $razones) . '.';
            }
        }

        // Encontrar ruta con máximo margen
        $maxMargen = -PHP_INT_MAX;
        $rutaOptima = null;

        foreach ($disponibles as $ruta => $activa) {
            if (!$activa) continue;

            $margen = match($ruta) {
                1 => $m1, 2 => $m2, 3 => $m3, 4 => $m4, default => 0
            };

            if ($margen > $maxMargen) {
                $maxMargen = $margen;
                $rutaOptima = $ruta;
            }
        }

        // Si existe ruta óptima, asignar todas las cabezas
        if ($rutaOptima !== null && $maxMargen > 0) {
            $resultado['factible'] = true;
            $resultado['x' . $rutaOptima] = $cabezas;
            $ganancia = $cabezas * $maxMargen;
            $resultado['ganancia_total'] = round($ganancia, 2);

            $resultado['detalles'] = [
                'cabezas' => $cabezas,
                'peso_promedio' => $peso_promedio,
                'eficiencia_efectiva' => round($eficiencia_efectiva, 2),
                'precio_sc' => $precio_sc,
                'precio_cb' => $precio_cb,
                'costo_c1' => $costo_c1,
                'costo_c2' => $costo_c2,
                'costo_c3' => $costo_c3,
                'costo_c4' => $costo_c4,
                'margen_r1' => round($m1, 2),
                'margen_r2' => round($m2, 2),
                'margen_r3' => round($m3, 2),
                'margen_r4' => round($m4, 2),
                'ruta_optima' => $rutaOptima,
                'factor_estacion' => $factor_estacion,
                'factor_condicion' => $factor_condicion,
                'probabilidad_lluvia' => $prob_lluvia,
                'disponibles' => $disponibles,
                'diagnostico' => $diagnostico,
                'margen_info' => $margen_info
            ];
        } else {
            if ($rutaOptima === null) {
                $diagnostico[] = 'No hay rutas disponibles para asignar.';
            } else {
                $diagnostico[] = 'La mejor ruta disponible (Ruta ' . $rutaOptima . ') tiene margen no positivo (Bs ' . number_format($maxMargen, 2) . '). No es rentable.';
            }
            $resultado['detalles']['diagnostico'] = $diagnostico;
            $resultado['detalles']['margen_info'] = $margen_info;
            $resultado['detalles']['factor_estacion'] = $factor_estacion;
            $resultado['detalles']['factor_condicion'] = $factor_condicion;
            $resultado['detalles']['probabilidad_lluvia'] = $prob_lluvia;
            $resultado['detalles']['precio_sc'] = $precio_sc;
            $resultado['detalles']['precio_cb'] = $precio_cb;
            $resultado['detalles']['costo_c1'] = $costo_c1;
            $resultado['detalles']['costo_c2'] = $costo_c2;
            $resultado['detalles']['costo_c3'] = $costo_c3;
            $resultado['detalles']['costo_c4'] = $costo_c4;
            $resultado['detalles']['margen_r1'] = round($m1, 2);
            $resultado['detalles']['margen_r2'] = round($m2, 2);
            $resultado['detalles']['margen_r3'] = round($m3, 2);
            $resultado['detalles']['margen_r4'] = round($m4, 2);
            $resultado['detalles']['disponibles'] = $disponibles;
        }

        return $resultado;
    }

    private function validarRestricciones($hora_salida) {
        // Tiempo de viaje por ruta (horas)
        $tiempos = [
            1 => 6.5,  // Samaipata
            2 => 9.0,  // Comarapa
            3 => 11.0, // Ipati-Abapo
            4 => 10.0  // Aiquile
        ];

        // Convertir hora de salida a horas decimales
        list($h, $m, $s) = explode(':', $hora_salida);
        $hora_decimal = $h + ($m / 60);

        $rutas_activas = [];

        foreach ($tiempos as $ruta => $tiempo) {
            $hora_llegada = $hora_decimal + $tiempo;
            // Normalizar si cruza medianoche
            if ($hora_llegada >= 24) {
                $hora_llegada -= 24;
            }
            // Restricción: debe llegar antes de las 08:00 (8.0)
            if ($hora_llegada <= 8.0) {
                $rutas_activas[] = $ruta;
            }
        }

        return $rutas_activas;
    }
}
