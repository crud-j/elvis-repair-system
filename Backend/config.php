<?php
// Centralized error reporting and session management
error_reporting(E_ALL);
// On Vercel (Production), keep display_errors OFF. On Local, you can set to 1.
ini_set('display_errors', 0); 
ini_set('log_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Verifies the CSRF token.
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        echo json_encode(["error" => "CSRF token validation failed."]);
        exit;
    }
}

// Function to parse .env file (Only runs on Localhost)
function load_env($path)
{
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load .env file from the directory (for local dev only)
load_env(__DIR__ . '/.env');

// =========================================================
// DATABASE CONNECTION SETTINGS (UPDATED FOR AIVEN + VERCEL)
// =========================================================

// Use getenv() to pull Vercel Environment Variables
define('DB_SERVER', getenv('DB_HOST') ?: 'elvis-repair-db-aiuseonly404-317b.k.aivencloud.com'); // Vercel uses 'DB_HOST' usually
define('DB_USERNAME', getenv('DB_USER') ?: 'avnadmin');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'defaultdb');
define('DB_PORT', getenv('DB_PORT') ?: '22996'); // Aiven uses a custom port (e.g., 20696)

// SSL Certificate Path (Required for Aiven)
// Ensure you upload 'ca.pem' to the same folder as this config.php file
$ssl_ca = __DIR__ . '/ca.pem'; 

// Check if we are on Vercel (Production) or Local
// If the CA file exists, we assume we need to use SSL (Aiven)
$db_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Only add SSL options if the certificate file exists
if (file_exists($ssl_ca)) {
    $db_options[PDO::MYSQL_ATTR_SSL_CA] = $ssl_ca;
    $db_options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; // Often needed for cloud DBs
}

// Attempt to connect to MySQL database
try {
    // Note: Added port= to the DSN string
    $dsn = "mysql:host=" . DB_SERVER . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $db_options);
} catch (PDOException $e) {
    error_log("Connection Error: " . $e->getMessage());
    http_response_code(500);
    // In production, usually better to show a generic message
    echo json_encode(["error" => "Database connection error."]);
    exit;
}

// PHPMailer settings
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);

/**
 * Creates a notification for a user.
 */
function create_notification($pdo, $userId, $title, $message, $link = null) {
    try {
        $sql = "INSERT INTO notifications (user_id, title, message, link) VALUES (:user_id, :title, :message, :link)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':link', $link, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Failed to create notification: " . $e->getMessage());
    }
}

/**
 * Logout function
 */
function logout() {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

/**
 * Generate invoice
 */
function generateInvoice($pdo, $appointment_id = null, $user_id, $order_id = null) {
    $invoice_html = '';
    $invoice_data_to_store = []; 

    if ($appointment_id) {
        $sql = "SELECT a.*, u.username, u.email, s.name as service_name, s.price as service_price 
                FROM appointments a 
                JOIN users u ON a.user_id = u.id 
                LEFT JOIN services s ON a.service_id = s.id 
                WHERE a.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $appointment_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $invoice_data_to_store = [
                'type' => 'Appointment',
                'id' => $appointment_id,
                'user' => ['username' => $data['username'], 'email' => $data['email']],
                'details' => [
                    'service_name' => $data['service_name'],
                    'appointment_date' => $data['appointment_date'],
                    'appointment_time' => $data['appointment_time'],
                    'amount' => $data['amount']
                ]
            ];

            $invoice_html = "<h1>Invoice for Appointment #" . $appointment_id . "</h1>";
            $invoice_html .= "<p><strong>User:</strong> {$data['username']} ({$data['email']})</p>";
            $invoice_html .= "<p><strong>Service:</strong> {$data['service_name']}</p>";
            $invoice_html .= "<p><strong>Date:</strong> {$data['appointment_date']} {$data['appointment_time']}</p>";
            $invoice_html .= "<p><strong>Amount:</strong> ₱" . number_format($data['amount'], 2) . "</p>";
        }
    } elseif ($order_id) {
        $sql = "SELECT o.id AS order_id, o.created_at, o.total_amount, o.payment_method, u.username, u.email
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $order_id]);
        $order_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order_data) {
            $sql_items = "SELECT oi.quantity, oi.price_at_purchase, i.part_name 
                          FROM order_items oi 
                          JOIN inventory i ON oi.product_id = i.id 
                          WHERE oi.order_id = :order_id";
            $stmt_items = $pdo->prepare($sql_items);
            $stmt_items->execute(['order_id' => $order_id]);
            $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

            $invoice_data_to_store = [
                'type' => 'Order',
                'id' => $order_id,
                'user' => ['username' => $order_data['username'], 'email' => $order_data['email']],
                'details' => [
                    'order_date' => $order_data['created_at'],
                    'payment_method' => $order_data['payment_method'],
                    'total_amount' => $order_data['total_amount'],
                    'items' => $order_items
                ]
            ];

            $invoice_html = "<h1>Invoice for Order #" . $order_id . "</h1>";
            $invoice_html .= "<p><strong>User:</strong> {$order_data['username']} ({$order_data['email']})</p>";
            $invoice_html .= "<p><strong>Order Date:</strong> {$order_data['created_at']}</p>";
            $invoice_html .= "<p><strong>Payment Method:</strong> {$order_data['payment_method']}</p>";
            $invoice_html .= "<h2>Items Purchased:</h2><ul>";
            foreach ($order_items as $item) {
                $item_total = $item['quantity'] * $item['price_at_purchase'];
                $invoice_html .= "<li>{$item['quantity']}x {$item['part_name']} @ ₱" . number_format($item['price_at_purchase'], 2) . " = ₱" . number_format($item_total, 2) . "</li>";
            }
            $invoice_html .= "</ul>";
            $invoice_html .= "<p><strong>Total Amount:</strong> ₱" . number_format($order_data['total_amount'], 2) . "</p>";
        }
    }

    if (!empty($invoice_data_to_store)) {
        $sql_insert = "INSERT INTO invoices (appointment_id, order_id, user_id, invoice_data) VALUES (:appt_id, :order_id, :user_id, :data)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            'appt_id' => $appointment_id,
            'order_id' => $order_id,
            'user_id' => $user_id,
            'data' => json_encode($invoice_data_to_store) // Store as JSON
        ]);
    }
}
?>