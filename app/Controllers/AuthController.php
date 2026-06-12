<?php
namespace App\Controllers;

use App\Controller;
use App\Session;
use App\Models\Usuario;

class AuthController extends Controller {
    
    public function loginAction() {
        if ($this->isPost()) {
            return $this->procesarLogin();
        }
        
        $this->render('auth.login', [
            'csrf' => $this->generateCsrf()
        ], false);
    }
    
    private function procesarLogin() {
        $csrf = $this->getPost('csrf_token');
        
        if (!$this->validateCsrf($csrf)) {
            Session::flash('error', 'Token CSRF inválido');
            $this->redirect('/auth/login');
        }
        
        $username = trim($this->getPost('username', ''));
        $password = trim($this->getPost('password', ''));
        
        if (empty($username) || empty($password)) {
            Session::flash('error', 'Usuario y contraseña requeridos');
            $this->redirect('/auth/login');
        }
        
        $usuario = Usuario::findByUsername($username);
        
        if (!$usuario || !$usuario->verifyPassword($password)) {
            Session::flash('error', 'Usuario o contraseña incorrectos');
            $this->redirect('/auth/login');
        }
        
        // Login exitoso
        Session::regenerate();
        Session::set('user_id', $usuario->id);
        Session::set('username', $usuario->username);
        Session::set('nombre', $usuario->nombre);
        
        $this->redirect('/dashboard/index');
    }
    
    public function logoutAction() {
        Session::destroy();
        $this->redirect('/auth/login');
    }
}
