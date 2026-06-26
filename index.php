<?php
// DSS AGAPROVA - Punto de entrada principal

define('BASE_DIR', __DIR__);

// Cargar configuración primero (necesitamos las constantes DB_*)
require_once BASE_DIR . '/config/config.php';

// ── Sesiones persistentes en MySQL (funciona en Railway y localhost) ─────────
if (!class_exists('App\SessionHandler')) {
    require_once BASE_DIR . '/app/SessionHandler.php';
}
$sessionHandler = new \App\SessionHandler();
session_set_save_handler($sessionHandler, true);
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
ini_set('session.cookie_httponly', 1);
// ─────────────────────────────────────────────────────────────────────────────

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
    echo '<pre>Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
}
