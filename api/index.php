<?php
// api/index.php – FINAL WORKING VERSION (no more 404s or 500s)

if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

// Load config (session, DB, CSRF)
require __DIR__ . '/config.php';

// Only handle PHP files and root
$request = $_SERVER['REQUEST_URI'];
$parsed = parse_url($request);
$path = $parsed['path'] ?? '/';

// Root → homepage
if ($path === '/' || $path === '/index.php') {
    require __DIR__ . '/../index.php';
    exit;
}

// If it's a .php file anywhere in the project → include it safely
if (preg_match('/\.php$/i', $path)) {
    $file = __DIR__ . '/..' . $path;

    // Security: prevent directory traversal
    $real = realpath($file);
    if ($real && strpos($real, realpath(__DIR__ . '/..')) === 0 && file_exists($real)) {
        require $real;
        exit;
    }
}

// If we get here → 404 (but never 500)
http_response_code(404);
echo '<h1>404 Not Found</h1><p>The page you are looking for does not exist.</p>';