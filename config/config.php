<?php
// DSS AGAPROVA - Configuración Global

define('BASE_URL', 'http://localhost/agaprova-dss');
define('BASE_PATH', dirname(dirname(__FILE__)));

// Base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '77345226');
define('DB_NAME', 'dss_agaprova');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Sesion
define('SESSION_NAME', 'AGAPROVA_SESSION');
define('SESSION_TIMEOUT', 3600); // 1 hora

// Seguridad
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LENGTH', 32);

// Rutas
define('ROUTE_CONTROLLER', 'controller');
define('ROUTE_ACTION', 'action');
define('DEFAULT_CONTROLLER', 'Dashboard');
define('DEFAULT_ACTION', 'index');
define('LOGIN_CONTROLLER', 'Auth');
define('LOGIN_ACTION', 'login');

// Identidad visual
define('APP_NAME', 'DSS AGAPROVA');
define('APP_VERSION', '1.0');
define('APP_SLOGAN', 'Optimización Logística para Ganado Bovino');

// Colores institucionales
define('COLOR_PRIMARY', '#2E7D32');
define('COLOR_SECONDARY', '#4a7c2e');
define('COLOR_ACCENT', '#F9A825');
define('COLOR_DARK', '#4E2A04');
define('COLOR_BG', '#f5f0e8');

// Permitir HTTPS en producción
if (!in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8000'])) {
    define('FORCE_HTTPS', true);
} else {
    define('FORCE_HTTPS', false);
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en producción
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/errors.log');

// Timezone
date_default_timezone_set('America/La_Paz');

// Archivos de logging
if (!is_dir(BASE_PATH . '/logs')) {
    mkdir(BASE_PATH . '/logs', 0755, true);
}

// Cargar autoloader
define('APP_PATH', BASE_PATH . '/app');
require_once APP_PATH . '/Autoloader.php';
