<?php
namespace App;

class Session {
    private static $initialized = false;
    
    public static function init() {
        if (!self::$initialized) {
            session_name(SESSION_NAME);
            session_start();
            self::$initialized = true;
            self::validateTimeout();
        }
    }
    
    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::init();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::init();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::init();
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        self::init();
        session_destroy();
        session_unset();
    }
    
    public static function regenerate() {
        self::init();
        session_regenerate_id(true);
    }
    
    private static function validateTimeout() {
        $timeout = SESSION_TIMEOUT;
        $current = time();
        $lastActivity = self::get('last_activity', $current);
        
        if ($current - $lastActivity > $timeout) {
            self::destroy();
            return;
        }
        
        self::set('last_activity', $current);
    }
    
    public static function hasFlash($key) {
        return self::has('flash_' . $key);
    }
    
    public static function flash($key, $value = null) {
        self::init();
        if ($value === null) {
            $value = self::get('flash_' . $key);
            self::remove('flash_' . $key);
            return $value;
        }
        self::set('flash_' . $key, $value);
    }
}
