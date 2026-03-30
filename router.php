<?php
/**
 * router.php
 *
 * Development router for PHP's built-in web server.
 * This file is ONLY used when running via "php -S localhost:8000 router.php".
 * It is NOT used in production (Apache handles routing via public/.htaccess).
 *
 * What it does:
 *   1. If the request is for a real static file (CSS, JS, image), serve it directly.
 *   2. Everything else is forwarded to public/index.php (the front controller).
 *
 * Usage (VSCode launch.json handles this automatically):
 *   php -S localhost:8000 -t public router.php
 */

$requestUri  = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Build the absolute path to the requested file inside /public
$publicPath = __DIR__ . '/public' . $requestPath;

// If the request maps to a real file (CSS, JS, image, etc.), serve it directly.
// PHP's built-in server needs this hint - it won't auto-serve static files
// when a router script is present.
if ($requestPath !== '/' && file_exists($publicPath) && !is_dir($publicPath)) {
    return false; // Let PHP serve the file natively
}

// Otherwise, forward to the front controller.
require_once __DIR__ . '/public/index.php';