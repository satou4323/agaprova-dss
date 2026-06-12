<?php
/**
 * Validator - Validación de datos del sistema DSS AGAPROVA
 */

class Validator {
    private $errors = [];
    private $data = [];

    public function __construct($data = []) {
        $this->data = $data;
    }

    /**
     * Valida que un campo sea requerido
     */
    public function required($field, $message = null) {
        if (empty($this->data[$field])) {
            $this->errors[$field] = $message ?? "$field es requerido";
        }
        return $this;
    }

    /**
     * Valida email
     */
    public function email($field, $message = null) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "$field debe ser un email válido";
        }
        return $this;
    }

    /**
     * Valida longitud mínima
     */
    public function minLength($field, $length, $message = null) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "$field debe tener al menos $length caracteres";
        }
        return $this;
    }

    /**
     * Valida longitud máxima
     */
    public function maxLength($field, $length, $message = null) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "$field no puede exceder $length caracteres";
        }
        return $this;
    }

    /**
     * Valida número
     */
    public function numeric($field, $message = null) {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "$field debe ser un número";
        }
        return $this;
    }

    /**
     * Valida número positivo
     */
    public function positive($field, $message = null) {
        if (!empty($this->data[$field]) && $this->data[$field] <= 0) {
            $this->errors[$field] = $message ?? "$field debe ser un número positivo";
        }
        return $this;
    }

    /**
     * Valida fecha en formato YYYY-MM-DD
     */
    public function date($field, $message = null) {
        if (!empty($this->data[$field])) {
            $d = \DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = $message ?? "$field debe ser una fecha válida (YYYY-MM-DD)";
            }
        }
        return $this;
    }

    /**
     * Valida que dos campos sean iguales
     */
    public function match($field1, $field2, $message = null) {
        if ($this->data[$field1] !== $this->data[$field2]) {
            $this->errors[$field1] = $message ?? "$field1 no coincide con $field2";
        }
        return $this;
    }

    /**
     * Valida que un valor esté en una lista
     */
    public function in($field, $values, $message = null) {
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = $message ?? "$field debe ser uno de: " . implode(', ', $values);
        }
        return $this;
    }

    /**
     * Valida rango de números
     */
    public function between($field, $min, $max, $message = null) {
        if (!empty($this->data[$field])) {
            $value = (float)$this->data[$field];
            if ($value < $min || $value > $max) {
                $this->errors[$field] = $message ?? "$field debe estar entre $min y $max";
            }
        }
        return $this;
    }

    /**
     * Valida URL
     */
    public function url($field, $message = null) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? "$field debe ser una URL válida";
        }
        return $this;
    }

    /**
     * Valida teléfono
     */
    public function phone($field, $message = null) {
        if (!empty($this->data[$field])) {
            $phone = preg_replace('/[^0-9+\-\s().]/', '', $this->data[$field]);
            if (strlen($phone) < 10) {
                $this->errors[$field] = $message ?? "$field debe ser un teléfono válido";
            }
        }
        return $this;
    }

    /**
     * Retorna si hay errores
     */
    public function fails() {
        return !empty($this->errors);
    }

    /**
     * Retorna si está válido
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Obtiene los errores
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Obtiene un error específico
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    /**
     * Obtiene el primer error
     */
    public function firstError() {
        return reset($this->errors) ?: null;
    }

    /**
     * Valida coordenadas geográficas
     */
    public function coordinates($field, $message = null) {
        if (!empty($this->data[$field])) {
            // Esperamos formato: "lat,lng"
            $parts = explode(',', $this->data[$field]);
            if (count($parts) !== 2) {
                $this->errors[$field] = $message ?? "$field debe ser coordenadas válidas (lat,lng)";
                return $this;
            }
            
            $lat = floatval(trim($parts[0]));
            $lng = floatval(trim($parts[1]));
            
            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                $this->errors[$field] = $message ?? "$field contiene coordenadas inválidas";
            }
        }
        return $this;
    }

    /**
     * Valida ganado - cantidad
     */
    public function validateGanado($cantidad, $condition) {
        $validator = new Validator([
            'cantidad' => $cantidad,
            'condition' => $condition
        ]);

        $validator->required('cantidad', 'La cantidad de ganado es requerida')
                  ->numeric('cantidad', 'La cantidad debe ser un número')
                  ->positive('cantidad', 'La cantidad debe ser mayor a 0')
                  ->required('condition', 'La condición del ganado es requerida')
                  ->in('condition', ['excelente', 'buena', 'regular', 'mala'], 'Condición inválida');

        return $validator;
    }

    /**
     * Valida precios
     */
    public function validatePrice($price) {
        $validator = new Validator(['price' => $price]);
        
        $validator->required('price', 'El precio es requerido')
                  ->numeric('price', 'El precio debe ser un número')
                  ->positive('price', 'El precio debe ser mayor a 0');

        return $validator;
    }

    /**
     * Valida ruta
     */
    public function validateRoute($origen, $destino, $distancia) {
        $validator = new Validator([
            'origen' => $origen,
            'destino' => $destino,
            'distancia' => $distancia
        ]);

        $validator->required('origen', 'El origen es requerido')
                  ->required('destino', 'El destino es requerido')
                  ->required('distancia', 'La distancia es requerida')
                  ->numeric('distancia', 'La distancia debe ser un número')
                  ->positive('distancia', 'La distancia debe ser positiva');

        return $validator;
    }
}
