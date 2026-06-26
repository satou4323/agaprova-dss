<?php
namespace App;

class SessionHandler implements \SessionHandlerInterface {
    private $db;
    private $lifetime;

    public function open($path, $name): bool {
        $this->lifetime = 3600;
        try {
            $this->db = new \mysqli(
                getenv('DB_HOST') ?: DB_HOST,
                getenv('DB_USER') ?: DB_USER,
                getenv('DB_PASS') !== false ? getenv('DB_PASS') : DB_PASS,
                getenv('DB_NAME') ?: DB_NAME,
                (int)(getenv('DB_PORT') ?: DB_PORT)
            );
            if ($this->db->connect_error) {
                error_log('SessionHandler DB error: ' . $this->db->connect_error);
                return false;
            }
            return true;
        } catch (\Exception $e) {
            error_log('SessionHandler exception: ' . $e->getMessage());
            return false;
        }
    }

    public function close(): bool {
        if ($this->db) $this->db->close();
        return true;
    }

    public function read($id): string {
        $id = $this->db->real_escape_string($id);
        $result = $this->db->query(
            "SELECT session_data FROM php_sessions 
             WHERE session_id = '$id' AND session_expiry > " . time()
        );
        if ($result && $row = $result->fetch_assoc()) {
            return $row['session_data'];
        }
        return '';
    }

    public function write($id, $data): bool {
        $id     = $this->db->real_escape_string($id);
        $data   = $this->db->real_escape_string($data);
        $expiry = time() + $this->lifetime;
        $this->db->query(
            "REPLACE INTO php_sessions (session_id, session_data, session_expiry)
             VALUES ('$id', '$data', $expiry)"
        );
        return true;
    }

    public function destroy($id): bool {
        $id = $this->db->real_escape_string($id);
        $this->db->query("DELETE FROM php_sessions WHERE session_id = '$id'");
        return true;
    }

    public function gc($max_lifetime): int|false {
        $this->db->query("DELETE FROM php_sessions WHERE session_expiry < " . time());
        return $this->db->affected_rows;
    }
}
