<?php
// DSS AGAPROVA - Punto de entrada principal

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
