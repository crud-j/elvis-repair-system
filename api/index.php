<?php
/**
 * api/index.php - Dynamic Forwarder for Vercel PHP (based on vercel-community/php examples)
 * Routes all requests to correct PHP files while preventing downloads.
 */

// Prevent downloads: Set HTML header FIRST
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

// Include config for DB, sessions, CSRF (from api/)
require __DIR__ . '/config.php';

// Dynamic routing: Map URL to file path
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path_info = parse_url($request_uri, PHP_URL_PATH);
$file_path = null;

// Homepage
if ($path_info === '/' || $path_info === '/index.php') {
    $file_path = __DIR__ . '/../index.php';
}
// Auth pages (e.g., /login.php → Frontend/auth/login.php)
elseif (preg_match('/^\/(login|register|forgot_password|reset_password|verify)\.php$/', $path_info, $matches)) {
    $file_name = $matches[1] . '.php';
    $file_path = __DIR__ . '/../Frontend/auth/' . $file_name;
}
// Backend handlers (e.g., /booking-handler.php → Backend/booking-handler.php)
elseif (preg_match('/^\/(booking-handler|contact-handler|forgot_password_handler|get-availability|logout|reset_password_handler|seed_services)\.php$/', $path_info, $matches)) {
    $file_name = $matches[1] . '.php';
    $file_path = __DIR__ . '/../Backend/' . $file_name;
}
// Dashboards (e.g., /admindashboard.php → Frontend/admindash-frontend/admindashboard.php)
elseif (preg_match('/^\/(admindashboard|customerdashboard)\.php$/', $path_info, $matches)) {
    $dash_type = str_replace('dashboard', 'dash-', $matches[1]);
    $file_path = __DIR__ . '/../Frontend/' . $dash_type . '-frontend/' . $matches[1] . '.php';
}
// Backend dashboard handlers (e.g., /admin-backend.php → Backend/admindash-backend/admin-backend.php)
elseif (preg_match('/^\/(admin|customer)-backend\.php$/', $path_info, $matches)) {
    $type = $matches[1];
    $file_path = __DIR__ . '/../Backend/' . $type . 'dash-backend/' . $type . '-backend.php';
}
// Fallback: 404 if no match
else {
    http_response_code(404);
    echo '<h1>404 - Page Not Found</h1>';
    return;
}

// Include the target file if it exists
if ($file_path && file_exists($file_path)) {
    require $file_path;
} else {
    http_response_code(404);
    echo '<h1>404 - File Not Found</h1>';
}