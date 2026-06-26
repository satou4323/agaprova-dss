<?php
// DSS AGAPROVA - Configuración Global

// BASE_URL — corregido para Railway y servidor built-in PHP
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                 || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
                ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // En Railway el script corre desde la raíz, sin subdirectorio
    define('BASE_URL', $protocol . '://' . $host);
}

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Base de datos
if (!defined('DB_HOST')) { define('DB_HOST', getenv('DB_HOST') ?: 'localhost'); }
if (!defined('DB_USER')) { define('DB_USER', getenv('DB_USER') ?: 'root'); }
if (!defined('DB_PASS')) { define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '68387186'); }
if (!defined('DB_NAME')) { define('DB_NAME', getenv('DB_NAME') ?: 'dss_agaprova'); }
if (!defined('DB_PORT')) { define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306)); }
if (!defined('DB_CHARSET')) { define('DB_CHARSET', 'utf8mb4'); }

// Sesión
if (!defined('SESSION_NAME'))    { define('SESSION_NAME', 'AGAPROVA_SESSION'); }
if (!defined('SESSION_TIMEOUT')) { define('SESSION_TIMEOUT', 3600); }

// Seguridad
if (!defined('CSRF_TOKEN_NAME'))   { define('CSRF_TOKEN_NAME', 'csrf_token'); }
if (!defined('CSRF_TOKEN_LENGTH')) { define('CSRF_TOKEN_LENGTH', 32); }

// Rutas
if (!defined('ROUTE_CONTROLLER'))  { define('ROUTE_CONTROLLER', 'controller'); }
if (!defined('ROUTE_ACTION'))      { define('ROUTE_ACTION', 'action'); }
if (!defined('DEFAULT_CONTROLLER')){ define('DEFAULT_CONTROLLER', 'Dashboard'); }
if (!defined('DEFAULT_ACTION'))    { define('DEFAULT_ACTION', 'index'); }
if (!defined('LOGIN_CONTROLLER'))  { define('LOGIN_CONTROLLER', 'Auth'); }
if (!defined('LOGIN_ACTION'))      { define('LOGIN_ACTION', 'login'); }

// Parámetros del Modelo Matemático
if (!defined('E_INV_BASE'))          { define('E_INV_BASE', 0.58); }
if (!defined('LLUVIA_THRESHOLD'))    { define('LLUVIA_THRESHOLD', 0.40); }
if (!defined('HORA_LIMITE'))         { define('HORA_LIMITE', 8.0); }
if (!defined('CABEZAS_DEFAULT'))     { define('CABEZAS_DEFAULT', 45); }
if (!defined('PESO_DEFAULT'))        { define('PESO_DEFAULT', 420); }

// Identidad visual
if (!defined('APP_NAME'))    { define('APP_NAME', 'DSS AGAPROVA'); }
if (!defined('APP_VERSION')) { define('APP_VERSION', '1.0'); }
if (!defined('APP_SLOGAN'))  { define('APP_SLOGAN', 'Optimizacion Logistica para Ganado Bovino'); }

// Colores institucionales
if (!defined('COLOR_PRIMARY'))   { define('COLOR_PRIMARY',   '#2E7D32'); }
if (!defined('COLOR_SECONDARY')) { define('COLOR_SECONDARY', '#4a7c2e'); }
if (!defined('COLOR_ACCENT'))    { define('COLOR_ACCENT',    '#F9A825'); }
if (!defined('COLOR_DARK'))      { define('COLOR_DARK',      '#4E2A04'); }
if (!defined('COLOR_BG'))        { define('COLOR_BG',        '#f5f0e8'); }

// HTTPS
if (!defined('FORCE_HTTPS')) {
    $localHosts = ['localhost', '127.0.0.1', 'localhost:8000', 'localhost:8080'];
    define('FORCE_HTTPS', !in_array($_SERVER['HTTP_HOST'] ?? '', $localHosts));
}

// Error reporting — activado para ver errores en Railway
error_reporting(E_ALL);
ini_set('display_errors', 1);   // <-- temporal para debug
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/errors.log');

// Timezone
date_default_timezone_set('America/La_Paz');

// Directorio de logs
if (!is_dir(BASE_PATH . '/logs')) {
    mkdir(BASE_PATH . '/logs', 0755, true);
}

// Autoloader
if (!defined('APP_PATH')) { define('APP_PATH', BASE_PATH . '/app'); }
require_once APP_PATH . '/Autoloader.php';