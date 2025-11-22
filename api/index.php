<?php
/**
 * api/index.php - Universal Vercel Forwarder (based on vercel-community/php)
 * Dynamically includes any PHP file based on REQUEST_URI, prevents 404s/downloads.
 */

// Set HTML header (prevents downloads)
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

// Include config for DB, sessions, CSRF
require __DIR__ . '/config.php';

// Get the requested path (e.g., /Frontend/auth/login.php)
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path_info = parse_url($request_uri, PHP_URL_PATH);  // Clean path without query params

// Handle root/homepage
if ($path_info === '/' || $path_info === '/index.php') {
    require __DIR__ . '/../index.php';
    return;
}

// Build relative file path (e.g., /Frontend/auth/login.php → ../Frontend/auth/login.php)
$relative_path = ltrim($path_info, '/');  // Remove leading /
$file_path = __DIR__ . '/../' . $relative_path;

// Security: Resolve and validate path (prevents directory traversal)
$file_path = realpath($file_path);
$project_root = realpath(__DIR__ . '/..');  // Project root

if (
    $file_path === false ||  // Doesn't exist
    strpos($file_path, $project_root) !== 0 ||  // Outside project root (security)
    pathinfo($file_path, PATHINFO_EXTENSION) !== 'php'  // Not a PHP file
) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 - Page Not Found</title></head><body><h1>404 - Page Not Found</h1><p>The requested resource was not found.</p></body></html>';
    return;
}

// Include and execute the PHP file
require $file_path;