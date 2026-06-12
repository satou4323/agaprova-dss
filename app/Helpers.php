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
        if (!\App\Session::get('user_id')) {
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
     * Valida que no haya bloqueos en ruta
     */
    public static function validateRouteBlocks($ruta_id) {
        return !\App\Models\Bloqueo::estasBloqueada($ruta_id);
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
     * Genera un token aleatorio
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
}
