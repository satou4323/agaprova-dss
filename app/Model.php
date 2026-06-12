<?php
namespace App;

abstract class Model {
    protected $db;
    protected $table = '';
    protected $fillable = [];
    protected $attributes = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }
    
    public function __set($key, $value) {
        if (in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
    }
    
    public function save() {
        if (isset($this->attributes['id']) && $this->attributes['id']) {
            return $this->update();
        }
        return $this->insert();
    }
    
    private function insert() {
        $columns = array_keys($this->attributes);
        $values = array_values($this->attributes);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', $columns) . ') 
                VALUES (' . implode(',', $placeholders) . ')';
        
        $this->db->query($sql, $values);
        $this->attributes['id'] = $this->db->lastInsertId();
        return true;
    }
    
    private function update() {
        $id = $this->attributes['id'];
        $columns = array_filter(array_keys($this->attributes), fn($k) => $k !== 'id');
        $values = array_values(array_filter($this->attributes, fn($k, $v) => $k !== 'id', ARRAY_FILTER_USE_BOTH));
        
        $set = implode(',', array_map(fn($c) => "$c = ?", $columns));
        $sql = 'UPDATE ' . $this->table . ' SET ' . $set . ' WHERE id = ?';
        
        array_push($values, $id);
        return (bool) $this->db->query($sql, $values)->rowCount();
    }
    
    public static function find($id) {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE id = ?';
        $data = $instance->db->fetch($sql, [$id]);
        
        if ($data) {
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }
    
    public static function all() {
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table;
        $data = $instance->db->fetchAll($sql);
        
        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }
    
    public static function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $instance = new static();
        $sql = 'SELECT * FROM ' . $instance->table . ' WHERE ' . $column . ' ' . $operator . ' ?';
        $data = $instance->db->fetchAll($sql, [$value]);
        
        $models = [];
        foreach ($data as $row) {
            $model = new static();
            $model->attributes = $row;
            $models[] = $model;
        }
        return $models;
    }
    
    public static function first($column = 'id', $operator = null, $value = null) {
        if ($operator === null) {
            $models = static::all();
            return $models[0] ?? null;
        }
        
        $models = static::where($column, $operator, $value);
        return $models[0] ?? null;
    }
    
    public function delete() {
        if (!isset($this->attributes['id'])) {
            return false;
        }
        
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        return (bool) $this->db->query($sql, [$this->attributes['id']])->rowCount();
    }
    
    public function toArray() {
        return $this->attributes;
    }
    
    public function getAttributes() {
        return $this->attributes;
    }
}
