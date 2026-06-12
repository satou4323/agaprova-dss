<?php
namespace App;

class Router {
    private $routes = [];
    private $controller = null;
    private $action = null;
    private $params = [];
    
    public function dispatch() {
        $uri = $this->getUri();
        $parts = array_filter(explode('/', $uri), 'strlen');
        
        // Obtener controlador y acción de la URL
        $this->controller = !empty($parts) ? ucfirst(array_shift($parts)) : DEFAULT_CONTROLLER;
        $this->action = !empty($parts) ? array_shift($parts) : DEFAULT_ACTION;
        $this->params = array_values($parts);
        
        // Verificar autenticación
        if ($this->controller !== 'Auth') {
            $this->requireAuth();
        }
        
        $this->loadController();
    }
    
    private function getUri() {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $basePath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($path, $basePath) === 0) {
            $uri = substr($path, strlen($basePath));
        } else {
            $uri = $path;
        }
        return trim($uri, '/');
    }
    
    private function requireAuth() {
        if (!Session::has('user_id')) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }
    
    private function loadController() {
        $controllerName = 'App\\Controllers\\' . $this->controller . 'Controller';
        
        if (!class_exists($controllerName)) {
            $this->error404();
        }
        
        $controller = new $controllerName();
        $action = $this->action . 'Action';
        
        if (!method_exists($controller, $action)) {
            $this->error404();
        }
        
        call_user_func_array([$controller, $action], $this->params);
    }
    
    private function error404() {
        header('HTTP/1.0 404 Not Found');
        die('Página no encontrada');
    }
}
