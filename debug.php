<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug DSS AGAPROVA</h2>";

// Variables de entorno
echo "<h3>Variables BD:</h3>";
echo "DB_HOST: " . getenv('DB_HOST') . "<br>";
echo "DB_PORT: " . getenv('DB_PORT') . "<br>";
echo "DB_NAME: " . getenv('DB_NAME') . "<br>";
echo "DB_USER: " . getenv('DB_USER') . "<br>";
echo "DB_PASS: " . (getenv('DB_PASS') ? '***OK***' : 'VACIO') . "<br>";

// Test conexión PDO
echo "<h3>Test Conexión:</h3>";
try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: 3306;
    $name = getenv('DB_NAME') ?: 'railway';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    
    $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "<span style='color:green'>✅ Conexión BD exitosa</span><br>";
    
    // Verificar tabla sesiones
    $stmt = $pdo->query("SHOW TABLES LIKE 'php_sessions'");
    $exists = $stmt->fetch();
    echo "Tabla php_sessions: " . ($exists ? "<span style='color:green'>✅ existe</span>" : "<span style='color:red'>❌ NO existe</span>") . "<br>";
    
    // Listar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tablas en BD:</h3>";
    foreach ($tables as $t) echo "- $t<br>";
    
} catch (Exception $e) {
    echo "<span style='color:red'>❌ Error: " . $e->getMessage() . "</span><br>";
}

// Test sesión
echo "<h3>Test Sesión:</h3>";
define('BASE_DIR', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/SessionHandler.php';
$sh = new \App\SessionHandler();
session_set_save_handler($sh, true);
session_start();
$_SESSION['test'] = 'ok_' . time();
echo "Session ID: " . session_id() . "<br>";
echo "Session test: " . $_SESSION['test'] . "<br>";
echo "<span style='color:green'>✅ Sesión funcionando</span><br>";
