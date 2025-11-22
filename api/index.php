<?php
// api/index.php

// Set header to return JSON (standard for APIs)
header('Content-Type: application/json');

// Include the database configuration from the parent directory
// We use __DIR__ . '/../config.php' because config.php is one folder up
require __DIR__ . '/../config.php';

$response = [];

try {
    // 1. Check Database Connection
    if ($pdo) {
        $response['status'] = 'success';
        $response['message'] = 'Connected to Aiven Database successfully!';
        
        // 2. Run a quick query to prove data access works
        // We'll check the server version and count the users
        $stmt = $pdo->query("SELECT VERSION() as version");
        $version = $stmt->fetch();
        $response['database_version'] = $version['version'];

        $stmtUsers = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmtUsers->fetch();
        $response['user_count'] = $userCount['count'];
    } else {
        $response['status'] = 'error';
        $response['message'] = 'PDO object is null.';
    }

} catch (Exception $e) {
    // Catch any errors and show them
    http_response_code(500);
    $response['status'] = 'error';
    $response['message'] = 'Connection failed: ' . $e->getMessage();
}

// Output the JSON response
echo json_encode($response);
?>