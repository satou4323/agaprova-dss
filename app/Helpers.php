<?php
/**
 * Helpers - Funciones utilitarias del sistema DSS AGAPROVA
 */

class Helpers {
    /**
     * Redirige a una URL
     */
    public static function redirect($url) {
        header("Location: $url");
        exit;
    }

    /**
     * Valida que el usuario esté autenticado
     */
    public static function requireAuth() {
        if (!Session::get('usuario_id')) {
            self::redirect('/auth/login');
        }
    }

    /**
     * Escapa HTML para prevenir XSS
     */
    public static function e($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida email
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Valida número
     */
    public static function isValidNumber($number) {
        return is_numeric($number) && $number > 0;
    }

    /**
     * Valida fecha en formato YYYY-MM-DD
     */
    public static function isValidDate($date) {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Formatea número a moneda
     */
    public static function formatCurrency($value) {
        return '$' . number_format($value, 2, '.', ',');
    }

    /**
     * Formatea número con decimales
     */
    public static function formatNumber($value, $decimals = 2) {
        return number_format($value, $decimals, '.', ',');
    }

    /**
     * Calcula distancia entre dos puntos usando Haversine
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Radio de la Tierra en km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * asin(sqrt($a));
        return $earthRadius * $c;
    }

    /**
     * Calcula el costo de transporte basado en distancia
     */
    public static function calculateTransportCost($distance, $costPerKm) {
        return $distance * $costPerKm;
    }

    /**
     * Calcula el tiempo de transporte (aprox 60 km/h promedio)
     */
    public static function calculateTransportTime($distance) {
        return round($distance / 60 * 60); // Retorna minutos
    }

    /**
     * Valida que no haya bloqueos en ruta
     */
    public static function validateRouteBlocks($ruta_id, $fecha) {
        $bloqueos = Bloqueo::where(['ruta_id' => $ruta_id, 'fecha' => $fecha])->get();
        return count($bloqueos) === 0;
    }

    /**
     * Obtiene el impacto del clima en el transporte
     */
    public static function getClimateImpact($condition) {
        $impacts = [
            'sunny' => 1.0,
            'cloudy' => 1.05,
            'rainy' => 1.15,
            'stormy' => 1.25,
            'snow' => 1.35
        ];
        return $impacts[$condition] ?? 1.0;
    }

    /**
     * Convierte un arreglo a JSON
     */
    public static function toJson($data) {
        return json_encode($data);
    }

    /**
     * Convierte JSON a arreglo
     */
    public static function fromJson($json) {
        return json_decode($json, true);
    }

    /**
     * Obtiene la diferencia de tiempo en horas
     */
    public static function getHoursDifference($start, $end) {
        $start = strtotime($start);
        $end = strtotime($end);
        return round(abs($end - $start) / 3600);
    }

    /**
     * Valida que la combinación de ruta y fecha no tenga bloqueos
     */
    public static function canShipOnDate($ruta_id, $fecha) {
        $bloqueo = Bloqueo::where(['ruta_id' => $ruta_id, 'fecha' => $fecha])->first();
        return !$bloqueo;
    }

    /**
     * Obtiene todas las rutas disponibles
     */
    public static function getAvailableRoutes() {
        return Ruta::getAll();
    }

    /**
     * Obtiene todas las estaciones
     */
    public static function getAvailableStations() {
        return Estacion::getAll();
    }

    /**
     * Paginación - retorna límite y offset
     */
    public static function getPagination($page = 1, $perPage = 10) {
        $page = max(1, intval($page));
        $offset = ($page - 1) * $perPage;
        return [
            'page' => $page,
            'limit' => $perPage,
            'offset' => $offset
        ];
    }

    /**
     * Sanitiza entrada de usuario
     */
    public static function sanitize($input) {
        return trim(strip_tags($input));
    }

    /**
     * Genera un slug a partir de un string
     */
    public static function slugify($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    /**
     * Obtiene la edad a partir de una fecha de nacimiento
     */
    public static function getAge($birthDate) {
        $birth = new \DateTime($birthDate);
        $today = new \DateTime();
        return $today->diff($birth)->y;
    }

    /**
     * Genera un token aleatorio
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
}
