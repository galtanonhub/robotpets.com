<?php
// Router for PHP's built-in dev server: serves real files/scripts,
// sends everything else to the styled 404 page.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && (is_file($file) || is_dir($file))) {
    return false; // let the built-in server handle it
}
if ($path === '/' || $path === '/index.php') {
    require __DIR__ . '/index.php';
    return true;
}
require __DIR__ . '/404.php';
