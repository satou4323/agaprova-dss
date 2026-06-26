<?php
// router.php — Railway PHP built-in server router

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$root = __DIR__;

// ── 1. Archivos estáticos reales (CSS, JS, imágenes, fonts) ──────────────────
if ($uri !== '/' && file_exists($root . $uri)) {
    $ext = pathinfo($uri, PATHINFO_EXTENSION);
    $mime = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'ico'   => 'image/x-icon',
        'svg'   => 'image/svg+xml',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'pdf'   => 'application/pdf',
        'webp'  => 'image/webp',
    ];
    if (isset($mime[$ext])) {
        header('Content-Type: ' . $mime[$ext]);
        readfile($root . $uri);
        return true;
    }
    // Para archivos no mapeados (txt, json, etc.) el servidor los sirve solo
    return false;
}

// ── 2. Todo lo demás va al index.php del MVC ─────────────────────────────────
// Extraer la ruta para el router del MVC (quitar la / inicial)
$path = ltrim($uri, '/');

// Pasar como parámetro GET 'url' (como espera tu .htaccess original)
$_GET['url'] = $path;
$_REQUEST['url'] = $path;

require_once $root . '/index.php';