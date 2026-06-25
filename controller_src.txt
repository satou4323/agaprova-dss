<?php
namespace App;

abstract class Controller {
    protected $db;
    protected $view_data = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
        Session::init();
    }
    
    protected function render($view, $data = [], $useLayout = true) {
        $this->view_data = array_merge($this->view_data, $data);
        $view_path = BASE_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($view_path)) {
            die("Vista no encontrada: {$view}");
        }
        
        extract($this->view_data);
        ob_start();
        include $view_path;
        $content = ob_get_clean();
        
        if ($useLayout) {
            // Cargar layout
            $layout_path = BASE_PATH . '/views/layouts/main.php';
            if (file_exists($layout_path)) {
                ob_start();
                include $layout_path;
                echo ob_get_clean();
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }
    
    protected function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }
    
    protected function validateCsrf($token) {
        $session_token = Session::get(CSRF_TOKEN_NAME);
        return hash_equals($session_token ?? '', $token ?? '');
    }
    
    protected function generateCsrf() {
        if (!Session::has(CSRF_TOKEN_NAME)) {
            Session::set(CSRF_TOKEN_NAME, bin2hex(random_bytes(CSRF_TOKEN_LENGTH)));
        }
        return Session::get(CSRF_TOKEN_NAME);
    }
    
    protected function getPost($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    protected function getGet($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
    
    protected function getRequest($key, $default = null) {
        return $_REQUEST[$key] ?? $default;
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}
