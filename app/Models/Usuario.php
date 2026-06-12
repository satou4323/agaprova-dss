<?php
namespace App\Models;

use App\Model;

class Usuario extends Model {
    protected $table = 'usuarios';
    protected $fillable = ['username', 'password_hash', 'nombre', 'created_at'];
    
    public static function findByUsername($username) {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE username = ?';
        $data = $instance->db->fetch($sql, [$username]);
        
        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }
    
    public function verifyPassword($password) {
        return password_verify($password, $this->attributes['password_hash'] ?? '');
    }
}
