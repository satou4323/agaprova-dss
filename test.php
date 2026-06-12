<?php
echo "=== CONFIG ===\n";
$cfg = file_get_contents(__DIR__ . '/config/config.php');
preg_match_all('/define\("(BASE_URL|DB_HOST|DB_USER|DB_PASS|DB_NAME|DB_PORT)",\s*"(.*?)"\)/', $cfg, $m);
$keys = $m[1];
$vals = $m[2];
for ($i = 0; $i < count($keys); $i++) {
    echo $keys[$i] . " = " . $vals[$i] . "\n";
}

echo "\n=== REWRITEBASE ===\n";
$ht = file_get_contents(__DIR__ . '/.htaccess');
preg_match('/RewriteBase\s+(.+)/', $ht, $m2);
echo ($m2[1] ?? 'NO ENCONTRADO') . "\n";

echo "\n=== TEST MYSQL ===\n";
try {
    $h = $vals[1]; $u = $vals[2]; $p = $vals[3]; $d = $vals[4]; $po = $vals[5];
    $con = new PDO("mysql:host=$h;port=$po;dbname=$d", $u, $p);
    echo "MYSQL CONECTA OK\n";
} catch (Exception $e) {
    echo "ERROR BD: " . $e->getMessage() . "\n";
}
