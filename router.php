<?php
// Router para el servidor embebido de PHP (emula el .htaccess de Apache)
// Si el archivo solicitado existe fisicamente (css, js, imagenes, etc.), servirlo directo.
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // El servidor sirve el archivo estatico tal cual
}

// Todo lo demas va a index.php, pasando la ruta en el parametro 'url' (igual que el .htaccess)
$_GET['url'] = ltrim($uri, '/');
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
