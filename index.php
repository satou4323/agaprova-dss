<?php
// DSS AGAPROVA - Punto de entrada principal

// ── Configuración de sesión para Railway (PHP built-in server) ───────────────
$sessionPath = sys_get_temp_dir() . '/agaprova_sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
ini_set('session.save_path', $sessionPath);
ini_set('session.save_handler', 'files');
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
session_name('AGAPROVA_SESS');
// ─────────────────────────────────────────────────────────────────────────────

define('BASE_DIR', __DIR__);

// Cargar configuración
require_once BASE_DIR . '/config/config.php';

// Cargar autoloader de Composer (para TCPDF)
$vendorAutoload = BASE_DIR . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

// Autoloader PSR-4
spl_autoload_register(function($class) {
    $prefix = 'App\\';
    $base_dir = BASE_PATH . '/app/';
    
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    return false;
});

// Inicializar aplicación
try {
    $router = new \App\Router();
    $router->dispatch();
} catch (Exception $e) {
    error_log('Application Error: ' . $e->getMessage());
    http_response_code(500);
    die('Error interno del servidor');
}