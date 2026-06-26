<?php
// router.php — Railway PHP built-in server

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$root = __DIR__;

// ── Archivos estáticos (CSS, JS, imágenes, fuentes) ─────────────────────────
if ($uri !== '/' && file_exists($root . $uri)) {
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
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
        'webp'  => 'image/webp',
        'map'   => 'application/json',
    ];
    if (isset($mime[$ext])) {
        header('Content-Type: ' . $mime[$ext]);
        readfile($root . $uri);
        return true;
    }
    return false;
}

// ── Todo lo demás va al MVC ──────────────────────────────────────────────────
$_GET['url']     = ltrim($uri, '/');
$_REQUEST['url'] = $_GET['url'];

require_once $root . '/index.php';
