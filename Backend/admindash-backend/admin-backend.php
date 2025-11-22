<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require_once '/../api/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$userId = $_SESSION['id'];

try {
    switch ($action) {
        case 'getAdminDetails':
            $sql = "SELECT username, email FROM users WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($user);
            break;

        case 'getDashboardStats':
            $stats = [];

            // Total Bookings
            $stmt = $pdo->query("SELECT COUNT(*) FROM appointments");
            $stats['total_bookings'] = $stmt->fetchColumn();

            // Services In Progress
            $stmt = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'confirmed'");
            $stats['services_in_progress'] = $stmt->fetchColumn();

            // This Week's Revenue
            $stmt = $pdo->query("SELECT SUM(amount) FROM appointments WHERE status = 'completed' AND WEEK(appointment_date, 1) = WEEK(CURDATE(), 1) AND YEAR(appointment_date) = YEAR(CURDATE())");
            $weekly_revenue = $stmt->fetchColumn();
            $stats['weekly_revenue'] = $weekly_revenue ? (float)$weekly_revenue : 0;

            // This Month's Revenue
            $stmt = $pdo->query("SELECT SUM(amount) FROM appointments WHERE status = 'completed' AND MONTH(appointment_date) = MONTH(CURDATE()) AND YEAR(appointment_date) = YEAR(CURDATE())");
            $monthly_revenue = $stmt->fetchColumn();
            $stats['monthly_revenue'] = $monthly_revenue ? (float)$monthly_revenue : 0;

            echo json_encode($stats);
            break;

        case 'getLowStockParts':
            $sql = "SELECT part_name, part_id, stock_level, threshold, supplier 
                    FROM inventory 
                    WHERE stock_level <= threshold 
                    ORDER BY stock_level ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($parts);
            break;

        case 'getAtRiskCustomers':
            $sql = "SELECT 
                        u.id, 
                        u.username, 
                        u.email,
                        MAX(a.appointment_date) AS last_appointment_date,
                        (SELECT v.nickname FROM vehicles v WHERE v.user_id = u.id ORDER BY v.id DESC LIMIT 1) as last_vehicle_name
                    FROM users u
                    JOIN appointments a ON u.id = a.user_id
                    WHERE u.role = 'customer'
                    GROUP BY u.id, u.username, u.email
                    HAVING MAX(a.appointment_date) < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                    ORDER BY last_appointment_date ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($customers as &$customer) {
                $date = new DateTime($customer['last_appointment_date']);
                $now = new DateTime();
                $interval = $now->diff($date);
                $customer['overdue_period'] = $interval->format('%m months, %d days');
            }
            echo json_encode($customers);
            break;

        case 'getAllBookings':
            $search = $_GET['search'] ?? '';
            $sql = "SELECT 
                        a.id, 
                        u.username as customer_name, 
                        a.vehicle_name, 
                        t.name as technician_name, 
                        a.appointment_date, 
                        a.appointment_time, 
                        a.status, 
                        a.package_name,
                        v.plate_number,
                        v.car_photo_url,
                        v.issues
                    FROM appointments a
                    JOIN users u ON a.user_id = u.id
                    LEFT JOIN technicians t ON a.technician_id = t.id
                    LEFT JOIN vehicles v ON a.vehicle_id = v.id";
            
            $params = [];
            if (!empty($search)) {
                $sql .= " WHERE u.username LIKE :search OR a.vehicle_name LIKE :search OR t.name LIKE :search OR a.package_name LIKE :search OR a.status LIKE :search";
                $params[':search'] = "%$search%";
            }
            
            $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($bookings as &$booking) {
                if (!empty($booking['car_photo_url'])) {
                    $booking['car_photo_url'] = '../' . $booking['car_photo_url'];
                } else {
                    $booking['car_photo_url'] = '../../assets/img/placeholders/placeholder.png';
                }
            }
            echo json_encode($bookings);
            break;

        case 'getTechnicians':
            $stmt = $pdo->query("SELECT id, name, specialty FROM technicians ORDER BY name ASC");
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($bookings);
            break;

        case 'getBookingDetails':
            $bookingId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$bookingId) throw new Exception("Invalid Booking ID.");

            $sql = "SELECT a.id, a.user_id, a.technician_id, a.appointment_date, a.appointment_time, a.status, a.notes, a.package_name, u.username, u.email
                    FROM appointments a
                    JOIN users u ON a.user_id = u.id
                    WHERE a.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $bookingId]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'updateAppointmentStatus':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $appointmentId = $data['appointment_id'] ?? null;
                $newStatus = $data['status'] ?? null;

                if ($appointmentId && in_array($newStatus, ['confirmed', 'in_progress', 'cancelled', 'completed'])) {
                    $sql = "UPDATE appointments SET status = :status WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $appointmentId, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        // Fetch appointment info
                        $cust_stmt = $pdo->prepare("SELECT user_id, appointment_date FROM appointments WHERE id = :id");
                        $cust_stmt->execute(['id' => $appointmentId]);
                        $appointment_info = $cust_stmt->fetch(PDO::FETCH_ASSOC);

                        if ($appointment_info) {
                            $customerId = $appointment_info['user_id'];
                            $title = "Appointment " . ucfirst($newStatus);
                            $date_formatted = date("F j, Y", strtotime($appointment_info['appointment_date'] ?? 'a future date'));
                            
                            if ($newStatus === 'in_progress') {
                                $message = "Good news! Work on your vehicle for the appointment on {$date_formatted} is almost complete.";
                            } else {
                                $message = "Your appointment for {$date_formatted} has been updated to {$newStatus}.";
                            }

                            create_notification($pdo, $customerId, $title, $message, 'customerdashboard.php?section=appointments');

                            // Send booking status email
                            sendBookingStatusEmail($pdo, $customerId, $title, $message);

                            // If completed, update inventory if linked (optional extension)
                            if ($newStatus === 'completed') {
                                // Assume service uses parts; add logic if needed
                            }
                        }
                        echo json_encode(['success' => true]);
                    } else {
                        throw new Exception("Failed to update status.");
                    }
                } else {
                    throw new Exception("Invalid status or ID.");
                }
            }
            break;

        case 'sendReminderToCustomer':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $customerId = $data['customer_id'] ?? null;
                $customerName = $data['customer_name'] ?? 'Valued Customer';
                $vehicleName = $data['vehicle_name'] ?? 'your vehicle';

                if (!$customerId) {
                    throw new Exception("Invalid Customer ID.");
                }

                // Create a personalized notification
                $notifTitle = "We Miss You!";
                $notifMessage = "Hi {$customerName}, we've missed you! Your {$vehicleName} is due for a check-up. Book now for 10% off your next service.";
                create_notification($pdo, $customerId, $notifTitle, $notifMessage, 'customerdashboard.php?section=services');

                // Send a personalized email
                $emailSubject = "A Friendly Reminder from Elvis Auto Repair";
                sendBookingStatusEmail($pdo, $customerId, $emailSubject, $notifMessage);

                echo json_encode(['success' => true, 'message' => 'Reminder sent successfully.']);
            }
            break;

        case 'getAllCustomers':
            $stmt = $pdo->query("SELECT id, username, email FROM users WHERE role = 'customer' ORDER BY username");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getServices':
            $sql = "SELECT id, name, description, price, image_url, category FROM services ORDER BY name ASC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'addService':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $sql = "INSERT INTO services (name, description, price, image_url, category) VALUES (:name, :desc, :price, :image_url, :category)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':name' => $data['name'], ':desc' => $data['description'], ':price' => $data['price'], ':image_url' => $data['image_url'], ':category' => $data['category']]);
                echo json_encode(['success' => true, 'message' => 'Service added successfully.']);
            }
            break;

        case 'updateService':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $sql = "UPDATE services SET name = :name, description = :desc, price = :price, image_url = :image_url, category = :category WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id' => $data['id'], ':name' => $data['name'], ':desc' => $data['description'], 
                    ':price' => $data['price'], ':image_url' => $data['image_url'], ':category' => $data['category']
                ]);
                echo json_encode(['success' => true, 'message' => 'Service updated successfully.']);
            }
            break;

        case 'deleteService':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $sql = "DELETE FROM services WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $data['id']]);
                echo json_encode(['success' => true, 'message' => 'Service deleted successfully.']);
            }
            break;

       // REPLACE your existing 'getInventoryStats' case with this one:
        case 'getInventoryStats':
            $stats = [];
            
            // Low Stock
            $stmt = $pdo->query("SELECT COUNT(*) FROM inventory WHERE stock_level <= threshold AND stock_level > 0");
            $stats['low_stock'] = $stmt->fetchColumn();
            
            // Out of Stock
            $stmt = $pdo->query("SELECT COUNT(*) FROM inventory WHERE stock_level = 0");
            $stats['out_of_stock'] = $stmt->fetchColumn();
            
            // Total Parts
            $stmt = $pdo->query("SELECT COUNT(*) FROM inventory");
            $stats['total_parts'] = $stmt->fetchColumn();

            // Total Value
            $stmt = $pdo->query("SELECT SUM(stock_level * price) FROM inventory");
            $total_value = $stmt->fetchColumn();
            $stats['total_value'] = $total_value ? (float)$total_value : 0;
            
            echo json_encode($stats);
            break;

        case 'getInventory':
            $sql = "SELECT id, part_name, part_id, stock_level, threshold, supplier, price, image_url FROM inventory ORDER BY part_name ASC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'addPart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $sql = "INSERT INTO inventory (part_name, part_id, stock_level, threshold, supplier, price, image_url) VALUES (:part_name, :part_id, :stock_level, :threshold, :supplier, :price, :image_url)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':part_name' => $data['part_name'],
                    ':part_id' => $data['part_id'],
                    ':stock_level' => $data['stock_level'],
                    ':threshold' => $data['threshold'],
                    ':supplier' => $data['supplier'],
                    ':price' => $data['price'],
                    ':image_url' => $data['image_url'] ?: null
                ]);
                echo json_encode(['success' => true, 'message' => 'Part added successfully.']);
            }
            break;

        case 'updatePart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $sql = "UPDATE inventory SET part_name = :part_name, part_id = :part_id, stock_level = :stock_level, threshold = :threshold, supplier = :supplier, price = :price, image_url = :image_url WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id' => $data['id'],
                    ':part_name' => $data['part_name'],
                    ':part_id' => $data['part_id'],
                    ':stock_level' => $data['stock_level'],
                    ':threshold' => $data['threshold'],
                    ':supplier' => $data['supplier'],
                    ':price' => $data['price'],
                    ':image_url' => $data['image_url'] ?: null
                ]);
                // Check for low stock notification
                if ($data['stock_level'] <= $data['threshold']) {
                    $title = "Low Stock Alert";
                    $message = "Part {$data['part_name']} is low on stock (level: {$data['stock_level']}).";
                    create_notification($pdo, $userId, $title, $message);
                }
                echo json_encode(['success' => true, 'message' => 'Part updated successfully.']);
            }
            break;

        case 'deletePart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $sql = "DELETE FROM inventory WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $data['id']]);
                echo json_encode(['success' => true, 'message' => 'Part deleted successfully.']);
            }
            break;

        case 'getPartsForCustomer':
            $sql = "SELECT id, part_name, price, image_url, stock_level 
                    FROM inventory 
                    WHERE stock_level > 0 
                    ORDER BY part_name ASC";
            $stmt = $pdo->query($sql);
            $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($parts as &$part) {
                $part['image_url'] = $part['image_url'] ?? 'https://via.placeholder.com/150?text=No+Image';
            }
            echo json_encode($parts);
            break;

        // Add for contact messages (from truncated)
        case 'getContactMessages':
            $sql = "SELECT id, name, email, message, is_read, created_at FROM contact_messages ORDER BY created_at DESC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getOrders':
            $search = $_GET['search'] ?? '';
            $sql = "SELECT 
                        o.id,
                        u.username AS customer_name,
                        o.total_amount,
                        o.order_status,
                        o.created_at AS order_date,
                        o.payment_method,
                        o.payment_reference,
                        o.gcash_number,
                        GROUP_CONCAT(CONCAT(oi.quantity, 'x ', i.part_name) SEPARATOR '; ') AS items
                    FROM orders o
                    JOIN users u ON o.user_id = u.id
                    LEFT JOIN order_items oi ON o.id = oi.order_id
                    LEFT JOIN inventory i ON oi.product_id = i.id";

            $params = [];
            if (!empty($search)) {
                $sql .= " WHERE u.username LIKE :search OR o.order_status LIKE :search";
                $params[':search'] = "%$search%";
                if (is_numeric($search)) {
                    $sql .= " OR o.id = :id_search";
                    $params[':id_search'] = $search;
                }
            }
            $sql .= " GROUP BY o.id";
            $sql .= " ORDER BY o.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'updateOrderStatus':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                 $data = json_decode(file_get_contents('php://input'), true);
                 $orderId = $data['order_id'] ?? null;
                 $newStatus = isset($data['status']) ? strtolower($data['status']) : null; // Convert to lowercase
 
                 if ($orderId && in_array($newStatus, ['pending', 'processing', 'shipped', 'completed', 'cancelled'])) {
                     $sql = "UPDATE orders SET order_status = :status WHERE id = :id";
                     $stmt = $pdo->prepare($sql);
                     $stmt->execute([':status' => $newStatus, ':id' => $orderId]);
 
                     // Fetch user_id for notification
                     $order_stmt = $pdo->prepare("SELECT user_id FROM orders WHERE id = :id");
                     $order_stmt->execute(['id' => $orderId]);
                     $order_info = $order_stmt->fetch(PDO::FETCH_ASSOC);
 
                     if ($order_info) {
                         $customerId = $order_info['user_id'];
                         $title = "Order #{$orderId} Updated";
                         $message = "Your order status has been updated to: " . ucfirst($newStatus) . ".";
                         create_notification($pdo, $customerId, $title, $message, 'customerdashboard.php?section=invoices');
                         // Send the email notification
                         sendOrderStatusEmail($pdo, $customerId, $orderId, $newStatus);
                     }
 
                     echo json_encode(['success' => true]);
                 } else { throw new Exception("Invalid status or Order ID."); }
            }
            break;

            case 'getNotifications':
            // Fetch all notifications for admins (user_id = 1, or loop all admins)
            // This example fetches for the logged-in admin.
            $sql = "SELECT id, title, message, link, is_read, created_at 
                    FROM notifications 
                    WHERE user_id = :user_id 
                    ORDER BY created_at DESC 
                    LIMIT 15";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($notifications);
            break;

        case 'markNotificationsRead':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $notificationIds = $data['ids'] ?? [];

                if (!empty($notificationIds) && $userId) {
                    $placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
                    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND id IN ($placeholders)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array_merge([$userId], $notificationIds));
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No IDs or user session.']);
                }
            }
            break;

       // REPLACE your old 'markContactRead' case with this one:
        case 'updateContactStatus':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $data['message_id'];
                $is_read = $data['is_read']; // Will be 1 (read) or 0 (unread)
                
                $sql = "UPDATE contact_messages SET is_read = :is_read WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['is_read' => $is_read, 'id' => $id]);
                echo json_encode(['success' => true]);
            }
            break;

        // REPLACE your old 'deleteContact' case with this one:
        case 'deleteContactMessage':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $data['message_id'];
                
                $sql = "DELETE FROM contact_messages WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                echo json_encode(['success' => true]);
            }
            break;

            case 'getLoginHistory':
            $sql = "SELECT lh.login_time, lh.ip_address, u.username, u.email
                    FROM login_history lh
                    JOIN users u ON lh.user_id = u.id
                    ORDER BY lh.login_time DESC
                    LIMIT 50"; // Limit to 50 recent logins
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'updateBookingDetails':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                
                $id = $data['id'] ?? null;
                $technician_id = $data['technician_id'] ?? null;
                $date = $data['appointment_date'] ?? null;
                $time = $data['appointment_time'] ?? null;
                $status = $data['status'] ?? null;
                $notes = $data['notes'] ?? '';

                if (!$id || !$technician_id || !$date || !$time || !$status) {
                    throw new Exception("Missing required fields for update.");
                }

                $sql = "UPDATE appointments 
                        SET technician_id = :tech_id, appointment_date = :appt_date, appointment_time = :appt_time, status = :status, notes = :notes
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':tech_id' => $technician_id,
                    ':appt_date' => $date,
                    ':appt_time' => $time,
                    ':status' => $status,
                    ':notes' => $notes,
                    ':id' => $id
                ]);

                // Optional: Send notification if status changed
                // (The 'updateAppointmentStatus' case already does this, so be careful not to double-notify)
                
                echo json_encode(['success' => true, 'message' => 'Booking updated.']);
            }
            break;

        case 'getReportData':
            $reportData = [];

            // Main Stats
            $stmt_repairs = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'completed' UNION ALL SELECT COUNT(*) FROM orders WHERE order_status = 'completed'");
            $reportData['total_repairs'] = $stmt_repairs->fetchColumn();

            // Calculate total revenue from both appointments and part orders
            $stmt_revenue_appointments = $pdo->query("SELECT SUM(amount) FROM appointments WHERE status = 'completed'");
            $revenue_appointments = $stmt_revenue_appointments->fetchColumn() ?: 0;

            $stmt_revenue_orders = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE order_status = 'completed'");
            $revenue_orders = $stmt_revenue_orders->fetchColumn() ?: 0;

            $reportData['total_revenue'] = (float)$revenue_appointments + (float)$revenue_orders;

            $stmt_inv_value = $pdo->query("SELECT SUM(stock_level * price) FROM inventory");
            $inventory_value = $stmt_inv_value->fetchColumn();
            $reportData['inventory_value'] = $inventory_value ? (float)$inventory_value : 0.0;

            // Recent Revenue Report (last 30 days)
            $sql_recent = "SELECT 
                                a.appointment_date,
                                COALESCE(s.name, a.package_name) as service_name,
                                u.username as customer_name,
                                t.name as technician_name,
                                a.amount
                           FROM appointments a
                           JOIN users u ON a.user_id = u.id
                           JOIN technicians t ON a.technician_id = t.id
                           LEFT JOIN services s ON a.service_id = s.id
                           WHERE a.status = 'completed' AND a.appointment_date >= CURDATE() - INTERVAL 30 DAY
                           ORDER BY a.appointment_date DESC, a.appointment_time DESC
                           LIMIT 100";
            
            $stmt_recent = $pdo->query($sql_recent);
            $reportData['recent_revenue_report'] = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);

            // You can add more complex logic here for percentage changes vs last month if needed

            echo json_encode($reportData);
            break;

        case 'getTrainerSchedule':
            $sql = "SELECT 
                        a.appointment_date, 
                        a.appointment_time, 
                        t.name as technician_name, 
                        u.username as customer_name, 
                        a.package_name, 
                        a.status
                    FROM appointments a
                    JOIN users u ON a.user_id = u.id
                    JOIN technicians t ON a.technician_id = t.id
                    WHERE a.status IN ('pending', 'confirmed')
                    ORDER BY a.appointment_date ASC, a.appointment_time ASC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        // Add for logout (though client-side handles)
        case 'logout':
            logout();
            break;

        default:
            http_response_code(404);
            echo json_encode(["error" => "Action not found"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

/**
 * Sends an email notification to the user about their booking status.
 */
function sendBookingStatusEmail($pdo, $userId, $subject, $body) {
    $user_sql = "SELECT email, username FROM users WHERE id = :id";
    $user_stmt = $pdo->prepare($user_sql);
    $user_stmt->execute(['id' => $userId]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("Attempted to send email to non-existent user ID: $userId");
        return;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USERNAME, 'Elvis Auto Repair');
        $mail->addAddress($user['email'], $user['username']);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "Dear " . htmlspecialchars($user['username']) . ",<br><br>" . $body . "<br><br>Thank you,<br>The Elvis Auto Repair Team";
        $mail->AltBody = "Dear " . htmlspecialchars($user['username']) . ",\n\n" . strip_tags(str_replace('<br>', "\n", $body)) . "\n\nThank you,\nThe Elvis Auto Repair Team";

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

/**
 * Sends an email notification to the user about their order status.
 */
function sendOrderStatusEmail($pdo, $userId, $orderId, $newStatus) {
    $user_sql = "SELECT email, username FROM users WHERE id = :id";
    $user_stmt = $pdo->prepare($user_sql);
    $user_stmt->execute(['id' => $userId]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("Attempted to send order status email to non-existent user ID: $userId");
        return;
    }

    // Fetch order details for the email body
    $order_details_sql = "SELECT o.total_amount, o.created_at, GROUP_CONCAT(CONCAT(oi.quantity, 'x ', i.part_name) SEPARATOR ', ') AS items
                          FROM orders o
                          LEFT JOIN order_items oi ON o.id = oi.order_id
                          LEFT JOIN inventory i ON oi.product_id = i.id
                          WHERE o.id = :order_id
                          GROUP BY o.id";
    $order_details_stmt = $pdo->prepare($order_details_sql);
    $order_details_stmt->execute(['order_id' => $orderId]);
    $order_details = $order_details_stmt->fetch(PDO::FETCH_ASSOC);

    $subject = "Your Order #{$orderId} Status Update";
    $body = "Dear " . htmlspecialchars($user['username']) . ",<br><br>";
    $body .= "Your order #{$orderId} has been updated to: <strong>" . ucfirst($newStatus) . "</strong>.<br><br>";

    if ($order_details) {
        $body .= "<strong>Order Details:</strong><br>";
        $body .= "Order Date: " . date("F j, Y", strtotime($order_details['created_at'])) . "<br>";
        $body .= "Items: " . htmlspecialchars($order_details['items']) . "<br>";
        $body .= "Total Amount: ₱" . number_format($order_details['total_amount'], 2) . "<br><br>";
    }

    $body .= "You can view your order details in your dashboard.<br><br>";
    $body .= "Thank you,<br>The Elvis Auto Repair Team";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USERNAME, 'Elvis Auto Repair');
        $mail->addAddress($user['email'], $user['username']);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags(str_replace('<br>', "\n", $body));

        $mail->send();
    } catch (Exception $e) {
        error_log("Order status email for Order #{$orderId} to {$user['email']} could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>