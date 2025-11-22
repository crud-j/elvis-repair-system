<?php
session_start();
require_once '/../api/config.php';
require_once '../../vendor/autoload.php'; // PHPMailer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

function handleException($e) {
    http_response_code(500);
    error_log("Customer Backend Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    echo json_encode(['error' => 'An internal server error occurred. Please try again later.']);
    exit;
}

function sendPurchaseConfirmationEmail($recipientEmail, $orderId, $totalAmount, $emailContent) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = SMTP_HOST;                              // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = SMTP_USERNAME;                          // SMTP username
        $mail->Password   = SMTP_PASSWORD;                          // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = SMTP_PORT;                              // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom(SMTP_USERNAME, 'MyNissan Auto Repair');
        $mail->addAddress($recipientEmail);                         // Add a recipient

        // Content
        $mail->isHTML(false);                                       // Set email format to plain text
        $mail->Subject = "Order Confirmation - MyNissan Auto Repair (Order #$orderId)";
        $mail->Body    = $emailContent;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}


set_exception_handler('handleException');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'customer') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized. Please log in again."]);
    exit;
}

$action = $_GET['action'] ?? '';
$userId = $_SESSION['id'];

try {
    switch ($action) {
        case 'getUserDetails':
            $sql = "SELECT username, email, profile_picture FROM users WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                throw new Exception("User not found.");
            }
            if (!empty($user['profile_picture'])) {
                // Assuming profile_picture in DB is like 'uploads/user_1_pic.jpg'
                // We need to make it relative to the customerdashboard.php
                // customerdashboard.php is in Frontend/customerdash-frontend/
                // uploads is in Backend/uploads/
                // So, from customerdashboard.php, path is ../../Backend/uploads/
                $user['profile_picture_url'] = '/Elvis_Repair/Backend/' . $user['profile_picture'];
            }
            echo json_encode($user);
            break;

        case 'getVehicles':
            $sql = "SELECT id, vin, nickname, make, model, year, plate_number, car_photo_url, issues FROM vehicles WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($vehicles as &$vehicle) {
                if (!empty($vehicle['car_photo_url'])) {
                    $vehicle['car_photo_url'] = '/Elvis_Repair/Backend/' . $vehicle['car_photo_url'];
                } else {
                    $vehicle['car_photo_url'] = '../../assets/img/placeholders/placeholder.png';
                }
            }
            echo json_encode($vehicles);
            break;

        case 'getVehicleDetails':
            $vehicleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$vehicleId) {
                throw new Exception("Invalid vehicle ID.");
            }
            $sql = "SELECT id, vin, nickname, make, model, year, plate_number, car_photo_url, issues FROM vehicles WHERE id = :id AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $vehicleId, ':user_id' => $userId]);
            $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($vehicle && !empty($vehicle['car_photo_url'])) {
                $vehicle['car_photo_url'] = '/Elvis_Repair/Backend/' . $vehicle['car_photo_url'];
            }
            echo json_encode($vehicle);
            break;

        case 'addVehicle':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $vin = trim($_POST['vin'] ?? '');
                $nickname = trim($_POST['nickname'] ?? '');

                if (empty($vin) || strlen($vin) !== 17) {
                    throw new Exception("A valid 17-digit VIN is required.");
                }

                // Insert new vehicle with minimal data
                $sql = "INSERT INTO vehicles (user_id, vin, nickname, make, model, year) VALUES (:user_id, :vin, :nickname, 'Unknown', 'Unknown', '2024')";
                $stmt = $pdo->prepare($sql);
                $params = [
                    ':user_id' => $userId,
                    ':vin' => $vin,
                    ':nickname' => $nickname,
                ];

                if ($stmt->execute($params)) {
                    echo json_encode(['success' => true, 'message' => 'Vehicle added successfully.']);
                } else {
                    throw new Exception("Database error while adding vehicle.");
                }
            }
            break;

                      case 'saveVehicle':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $vehicleId    = $_POST['id'] ?? null;
            $nickname     = trim($_POST['nickname'] ?? '');
            $plate_number = trim($_POST['plate_number'] ?? '');
            $make         = trim($_POST['make'] ?? 'Unknown');
            $model        = trim($_POST['model'] ?? 'Unknown');
            $year         = trim($_POST['year'] ?? date('Y'));
            $vin          = strtoupper(trim($_POST['vin'] ?? ''));
            $issues       = trim($_POST['issues'] ?? '');
            $existingPhoto = $_POST['existing_photo_url'] ?? null;

            // Validation
            if (empty($nickname)) {
                echo json_encode(['success' => false, 'message' => 'Nickname is required']);
                exit;
            }
            if (empty($vin) || strlen($vin) !== 17) {
                echo json_encode(['success' => false, 'message' => 'VIN must be exactly 17 characters']);
                exit;
            }

            $photoPath = $existingPhoto;

            // VIN Decoding Logic
            if (empty($vehicleId) || ($make === 'Unknown' && !empty($vin))) {
                $vinApiUrl = "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/$vin?format=json";
                // Use file_get_contents with a stream context to handle potential errors
                $context = stream_context_create(['http' => ['ignore_errors' => true]]);
                $vinDataJson = @file_get_contents($vinApiUrl, false, $context);
                if ($vinDataJson) {
                    $vinData = json_decode($vinDataJson, true);
                    if ($vinData && !empty($vinData['Results'][0]['Make'])) {
                        $make = ucwords(strtolower(trim($vinData['Results'][0]['Make'])));
                        $model = ucwords(strtolower(trim($vinData['Results'][0]['Model'])));
                        $year = trim($vinData['Results'][0]['ModelYear']);
                    }
                }
            }

            // Handle photo upload
            if (!empty($_FILES['car_photo']['name']) && $_FILES['car_photo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['car_photo'];
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid photo format']);
                    exit;
                }
                if ($file['size'] > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Photo too large (max 5MB)']);
                    exit;
                }

                $uploadDir = '../../uploads/vehicle_photos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $newName = 'veh_' . $userId . '_' . time() . '.' . $ext;
                $fullPath = $uploadDir . $newName;

                if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                    $photoPath = 'uploads/vehicle_photos/' . $newName;
                }
            }

            try {
                $pdo->beginTransaction();

                if ($vehicleId) {
                    // Update
                    $sql = "UPDATE vehicles SET 
                            nickname = ?, plate_number = ?, make = ?, model = ?, year = ?, 
                            vin = ?, issues = ?, car_photo_url = ?
                            WHERE id = ? AND user_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nickname, $plate_number, $make, $model, $year, $vin, $issues, $photoPath, $vehicleId, $userId]);
                } else {
                    // Insert new
                    $sql = "INSERT INTO vehicles 
                            (user_id, nickname, plate_number, make, model, year, vin, issues, car_photo_url)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$userId, $nickname, $plate_number, $make, $model, $year, $vin, $issues, $photoPath]);
                }

                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'Vehicle saved successfully!']);

            } catch (Exception $e) {
                $pdo->rollBack();
                error_log("saveVehicle error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Save failed: ' . $e->getMessage()]);
            }
            break;
        case 'deleteVehicle':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $vehicleId = $data['id'] ?? null;
                if (!$vehicleId) {
                    throw new Exception("Invalid vehicle ID.");
                }

                // First, get the photo path to delete the file
                $stmt = $pdo->prepare("SELECT car_photo_url FROM vehicles WHERE id = :id AND user_id = :user_id");
                $stmt->execute([':id' => $vehicleId, ':user_id' => $userId]);
                $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

                // Then, delete from DB
                $sql = "DELETE FROM vehicles WHERE id = :id AND user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $vehicleId, ':user_id' => $userId]);

                if ($stmt->rowCount() > 0) {
                    // If DB deletion was successful, delete the file
                    if ($vehicle && !empty($vehicle['car_photo_url'])) {
                        $photoPath = '../../Backend/' . $vehicle['car_photo_url'];
                        if (file_exists($photoPath)) {
                            unlink($photoPath);
                        }
                    }
                    echo json_encode(['success' => true, 'message' => 'Vehicle deleted.']);
                } else {
                    throw new Exception("Vehicle not found or you do not have permission to delete it.");
                }
            }
            break;

        case 'getVehicleServiceHistory':
            $vehicleId = filter_input(INPUT_GET, 'vehicle_id', FILTER_VALIDATE_INT);
            if (!$vehicleId) {
                throw new Exception("Invalid Vehicle ID.");
            }
            $sql = "SELECT s.name as service_name, a.appointment_date, a.amount, t.name as technician_name
                    FROM appointments a
                    JOIN services s ON a.service_id = s.id
                    JOIN technicians t ON a.technician_id = t.id
                    WHERE a.vehicle_id = :vehicle_id AND a.user_id = :user_id AND a.status = 'completed'
                    ORDER BY a.appointment_date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':vehicle_id' => $vehicleId, ':user_id' => $userId]);
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($history);
            break;

        case 'createAppointment':
             if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $vehicleId = $data['vehicle_id'] ?? null;
                $serviceId = $data['service_id'] ?? null;
                $technicianId = $data['technician_id'] ?? null;
                $date = $data['appointment_date'] ?? null;
                $time = $data['appointment_time'] ?? null;
                $notes = $data['notes'] ?? '';
                $paymentReference = $data['payment_reference'] ?? null;
                $gcashNumber = $data['gcash_number'] ?? null;

                if (!$vehicleId || !$serviceId || !$technicianId || !$date || !$time) {
                    throw new Exception("Missing required appointment details.");
                }

                // Fetch vehicle and service details for the appointment record
                $vehicleStmt = $pdo->prepare("SELECT nickname FROM vehicles WHERE id = :id");
                $vehicleStmt->execute([':id' => $vehicleId]);
                $vehicleName = $vehicleStmt->fetchColumn();

                $serviceStmt = $pdo->prepare("SELECT name, price FROM services WHERE id = :id");
                $serviceStmt->execute([':id' => $serviceId]);
                $serviceInfo = $serviceStmt->fetch(PDO::FETCH_ASSOC);

                if (!$serviceInfo) {
                    throw new Exception("Selected service not found.");
                }
                if (empty($paymentReference) || empty($gcashNumber)) {
                    throw new Exception("Payment details are required to book a service.");
                }

                $sql = "INSERT INTO appointments (user_id, vehicle_id, vehicle_name, service_id, technician_id, appointment_date, appointment_time, notes, amount, package_name, payment_reference, gcash_number) VALUES (:user_id, :vehicle_id, :vehicle_name, :service_id, :technician_id, :date, :time, :notes, :amount, :package_name, :payment_reference, :gcash_number)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userId,
                    ':vehicle_id' => $vehicleId,
                    ':vehicle_name' => $vehicleName,
                    ':service_id' => $serviceId,

                    ':technician_id' => $technicianId,
                    ':date' => $date,
                    ':time' => $time,
                    ':notes' => $notes,
                    ':amount' => $serviceInfo['price'],
                    ':package_name' => $serviceInfo['name'],
                    ':payment_reference' => $paymentReference,
                    ':gcash_number' => $gcashNumber
                ]);
                
                create_notification($pdo, $userId, "Appointment Booked", "Your appointment for {$vehicleName} has been booked for {$date}.");

                echo json_encode(['success' => true, 'message' => 'Appointment created successfully.']);
             }
             break;

        case 'getServiceHistory':
            $sql = "SELECT s.name as service_name, a.appointment_date, a.amount, v.nickname as vehicle_name, v.model as vehicle_model
                    FROM appointments a
                    JOIN services s ON a.service_id = s.id
                    JOIN vehicles v ON a.vehicle_id = v.id
                    WHERE a.user_id = :user_id AND a.status = 'completed'
                    ORDER BY a.appointment_date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($history);
            break;

        case 'getUpcomingAppointments':
            $sql = "SELECT a.id, COALESCE(s.name, a.package_name) as service_name, a.appointment_date, a.appointment_time, v.nickname as vehicle_name, t.name as technician_name, a.status
                    FROM appointments a
                    LEFT JOIN services s ON a.service_id = s.id
                    LEFT JOIN vehicles v ON a.vehicle_id = v.id
                    JOIN technicians t ON a.technician_id = t.id
                    WHERE a.user_id = :user_id AND a.status IN ('pending', 'confirmed', 'in_progress')
                    ORDER BY a.appointment_date ASC, a.appointment_time ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($appointments);
            break;

        case 'cancelAppointment':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $appointmentId = $data['id'] ?? null;

                if ($appointmentId) {
                    $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = :id AND user_id = :user_id AND status = 'pending'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['id' => $appointmentId, 'user_id' => $userId]);

                    if ($stmt->rowCount() > 0) {
                        create_notification($pdo, $userId, "Appointment Cancelled", "Your appointment has been cancelled.");
                        echo json_encode(['success' => true]);
                    } else {
                        throw new Exception("Cannot cancel this appointment.");
                    }
                } else {
                    throw new Exception("Invalid ID.");
                }
            }
            break;

        case 'getCustomerDashboardStats':
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM appointments WHERE user_id = :user_id AND status IN ('confirmed', 'pending')) AS upcoming_appointments,
                        (SELECT SUM(amount) FROM appointments WHERE user_id = :user_id AND status = 'confirmed') AS pending_payments,
                        (SELECT COUNT(*) FROM vehicles WHERE user_id = :user_id) AS vehicles_registered";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Ensure correct types
            $stats['upcoming_appointments'] = (int)($stats['upcoming_appointments'] ?? 0);
            $stats['pending_payments'] = (float)($stats['pending_payments'] ?? 0);
            $stats['vehicles_registered'] = (int)($stats['vehicles_registered'] ?? 0);

            echo json_encode($stats);
            break;

        case 'getServices':
            $stmt = $pdo->query("SELECT id, name, price FROM services ORDER BY name ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getTechnicians':
            $stmt = $pdo->query("SELECT id, name, specialty FROM technicians ORDER BY name ASC");
            $technicians = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            echo json_encode($technicians);
            break;

        case 'getMyLoginHistory':
            $sql = "SELECT login_time, ip_address 
                    FROM login_history 
                    WHERE user_id = :user_id
                    ORDER BY login_time DESC
                    LIMIT 20";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getNotifications':
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

                if (!empty($notificationIds)) {
                    $placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
                    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND id IN ($placeholders)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array_merge([$userId], $notificationIds));
                    echo json_encode(['success' => true]);
                }
            }
            break;

        case 'getTechnicianAvailability':
            $technicianId = filter_input(INPUT_GET, 'technician_id', FILTER_VALIDATE_INT);
            $month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT);
            $year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);

            if (!$technicianId || !$month || !$year) {
                throw new Exception("Missing required parameters: technician, month, or year.");
            }

            $allTimeSlots = [];
            for ($hour = 8; $hour <= 19; $hour++) {
                $allTimeSlots[] = sprintf('%02d:00:00', $hour);
            }

            $sql = "SELECT appointment_date, appointment_time FROM appointments 
                    WHERE technician_id = :technician_id 
                    AND YEAR(appointment_date) = :year 
                    AND MONTH(appointment_date) = :month
                    AND status IN ('confirmed', 'pending')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['technician_id' => $technicianId, 'year' => $year, 'month' => $month]);
            $appointments = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);

            $availability = [];
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateStr = sprintf('%d-%02d-%02d', $year, $month, $day);
                $bookedSlots = $appointments[$dateStr] ?? [];
                $availableSlots = array_diff($allTimeSlots, $bookedSlots);
                
                $availability[$dateStr] = [
                    'is_fully_booked' => empty($availableSlots),
                    'available_slots' => array_values($availableSlots)
                ];
            }

            echo json_encode($availability);
            break;

        case 'getPartsForStore':
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = (int)($_GET['limit'] ?? 8); // Default to 8 for a 4x2 grid
            $offset = ($page - 1) * $limit;
            $search = trim($_GET['search'] ?? '');

            $where = 'WHERE stock_level > 0';
            $params = [];

            if (!empty($search)) {
                $where .= " AND part_name LIKE :search";
                $params[':search'] = "%$search%";
            }

            // Get total count
            $totalSql = "SELECT COUNT(id) FROM inventory $where";
            $totalStmt = $pdo->prepare($totalSql);
            $totalStmt->execute($params);
            $total = $totalStmt->fetchColumn();

            // Get paginated results
            $sql = "SELECT id, part_name, price, image_url, stock_level 
                    FROM inventory 
                    $where
                    ORDER BY part_name ASC
                    LIMIT $limit OFFSET $offset";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($parts as &$part) { // Check if the URL is absolute (starts with http)
                if (empty($part['image_url'])) {
                    $part['image_url'] = '/Elvis_Repair/assets/img/placeholders/placeholder.png';
                } elseif (strpos($part['image_url'], 'http') !== 0) {
                    $part['image_url'] = '/Elvis_Repair/Backend/' . $part['image_url'];
                }
            }

            echo json_encode(['parts' => $parts, 'total' => (int)$total]);
            break;

        case 'getNewParts':
            // Assumes a `created_at` timestamp column exists on the inventory table.
            // If not, `ORDER BY id DESC` is a good alternative.
            $sql = "SELECT id, part_name, price, image_url, stock_level 
                    FROM inventory 
                    WHERE stock_level > 0 
                    ORDER BY id DESC 
                    LIMIT 4";
            $stmt = $pdo->query($sql);
            $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($parts as &$part) { // Check if the URL is absolute (starts with http)
                if (empty($part['image_url'])) {
                    $part['image_url'] = '/Elvis_Repair/assets/img/placeholders/placeholder.png';
                } elseif (strpos($part['image_url'], 'http') !== 0) {
                    $part['image_url'] = '/Elvis_Repair/Backend/' . $part['image_url'];
                }
            }
            echo json_encode($parts);
            break;

        case 'getServiceRecommendations':
            // 1. Get the makes of the user's vehicles
            $vehicle_sql = "SELECT DISTINCT make FROM vehicles WHERE user_id = :user_id";
            $vehicle_stmt = $pdo->prepare($vehicle_sql);
            $vehicle_stmt->execute([':user_id' => $userId]);
            $makes = $vehicle_stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($makes)) {
                echo json_encode([]); // No vehicles, no recommendations
                break;
            }

            // 2. Find services recommended for those makes
            $conditions = [];
            $params = [];
            foreach ($makes as $index => $make) {
                $key = ":make" . $index;
                $conditions[] = "FIND_IN_SET($key, s.recommended_for)";
                $params[$key] = $make;
            }
            $where_clause = implode(' OR ', $conditions);

            $rec_sql = "SELECT s.id, s.name, s.description, s.price, s.image_url, s.category FROM services s WHERE $where_clause ORDER BY s.name LIMIT 4";
            $rec_stmt = $pdo->prepare($rec_sql);
            $rec_stmt->execute($params);
            $recommendations = $rec_stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($recommendations as &$rec) {
                if ($rec['image_url'] && strpos($rec['image_url'], 'http') !== 0) {
                    $rec['image_url'] = '/Elvis_Repair/Backend/' . $rec['image_url'];
                }
            }
            echo json_encode($recommendations);
            break;

        case 'addToCart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $productId = $data['product_id'] ?? null;
                $productName = $data['product_name'] ?? 'Item'; // For notification
                $quantity = $data['quantity'] ?? 1;

                if (!$productId || $quantity < 1) {
                    throw new Exception("Invalid product or quantity.");
                }

                // Check if product exists and has enough stock
                $sql_check_stock = "SELECT stock_level FROM inventory WHERE id = :product_id";
                $stmt_check_stock = $pdo->prepare($sql_check_stock);
                $stmt_check_stock->execute([':product_id' => $productId]);
                $product = $stmt_check_stock->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Product not found.");
                }
                if ($product['stock_level'] < $quantity) {
                    throw new Exception("Insufficient stock for the requested quantity.");
                }

                // Check if item already in cart
                $sql_check_cart = "SELECT id, quantity FROM cart_items WHERE user_id = :user_id AND product_id = :product_id";
                $stmt_check_cart = $pdo->prepare($sql_check_cart);
                $stmt_check_cart->execute([':user_id' => $userId, ':product_id' => $productId]);
                $cartItem = $stmt_check_cart->fetch(PDO::FETCH_ASSOC);

                if ($cartItem) {
                    // Update quantity if already in cart
                    $newQuantity = $cartItem['quantity'] + $quantity;
                    if ($product['stock_level'] < $newQuantity) {
                        throw new Exception("Adding this quantity would exceed available stock.");
                    }
                    $sql_update_cart = "UPDATE cart_items SET quantity = :quantity WHERE id = :id";
                    $stmt_update_cart = $pdo->prepare($sql_update_cart);
                    $stmt_update_cart->execute([':quantity' => $newQuantity, ':id' => $cartItem['id']]);
                } else {
                    // Insert new item into cart
                    $sql_insert_cart = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
                    $stmt_insert_cart = $pdo->prepare($sql_insert_cart);
                    $stmt_insert_cart->execute([':user_id' => $userId, ':product_id' => $productId, ':quantity' => $quantity]);
                }
                echo json_encode(['success' => true, 'message' => "$productName has been added to your cart."]);
            }
            break;

        case 'getCartItems':
            $sql = "SELECT ci.id as cart_item_id, ci.product_id, ci.quantity, i.part_name, i.price, i.image_url, i.stock_level
                    FROM cart_items ci
                    JOIN inventory i ON ci.product_id = i.id
                    WHERE ci.user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($cartItems as &$item) { // Check if the URL is absolute (starts with http)
                if (empty($item['image_url'])) {
                    $item['image_url'] = '/Elvis_Repair/assets/img/placeholders/placeholder.png';
                } elseif (strpos($item['image_url'], 'http') !== 0) {
                    $item['image_url'] = '/Elvis_Repair/Backend/' . $item['image_url'];
                }
            }
            echo json_encode($cartItems);
            break;

        case 'updateCartItemQuantity':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $cartItemId = $data['cart_item_id'] ?? null;
                $quantity = $data['quantity'] ?? null;

                if (!$cartItemId || $quantity === null || $quantity < 0) {
                    throw new Exception("Invalid cart item ID or quantity.");
                }

                // Get product_id and current stock level
                $sql_get_info = "SELECT ci.product_id, i.stock_level FROM cart_items ci JOIN inventory i ON ci.product_id = i.id WHERE ci.id = :cart_item_id AND ci.user_id = :user_id";
                $stmt_get_info = $pdo->prepare($sql_get_info);
                $stmt_get_info->execute([':cart_item_id' => $cartItemId, ':user_id' => $userId]);
                $itemInfo = $stmt_get_info->fetch(PDO::FETCH_ASSOC);

                if (!$itemInfo) {
                    throw new Exception("Cart item not found or does not belong to user.");
                }

                if ($quantity > $itemInfo['stock_level']) {
                    throw new Exception("Requested quantity exceeds available stock.");
                }
                
                if ($quantity == 0) {
                    // If quantity is 0, remove the item
                    $sql = "DELETE FROM cart_items WHERE id = :cart_item_id AND user_id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':cart_item_id' => $cartItemId, ':user_id' => $userId]);
                    echo json_encode(['success' => true, 'message' => 'Item removed from cart.']);
                } else {
                    $sql = "UPDATE cart_items SET quantity = :quantity WHERE id = :cart_item_id AND user_id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':quantity' => $quantity, ':cart_item_id' => $cartItemId, ':user_id' => $userId]);
                    echo json_encode(['success' => true, 'message' => 'Cart quantity updated.']);
                }
            }
            break;

        case 'removeCartItem':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $cartItemId = $data['cart_item_id'] ?? null;

                if (!$cartItemId) {
                    throw new Exception("Invalid cart item ID.");
                }

                $sql = "DELETE FROM cart_items WHERE id = :cart_item_id AND user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':cart_item_id' => $cartItemId, ':user_id' => $userId]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'Item removed from cart.']);
                } else {
                    throw new Exception("Cart item not found or does not belong to user.");
                }
            }
            break;

        case 'getCartCount':
            $sql = "SELECT SUM(quantity) as total_items FROM cart_items WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['total_items' => (int)($result['total_items'] ?? 0)]);
            break;

        case 'checkout':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $paymentMethod = $data['payment_method'] ?? 'GCash'; // Default to GCash
                $itemsToCheckout = $data['items'] ?? null; // For direct purchase
                $paymentReference = $data['payment_reference'] ?? null;
                $gcashNumber = $data['gcash_number'] ?? null;

                $pdo->beginTransaction();
                try {
                    // 1. Determine items to process: from cart or direct purchase
                    $cartItems = [];
                    if ($itemsToCheckout) {
                        // Direct purchase: build the cart items structure from input
                        $productIds = array_column($itemsToCheckout, 'product_id');
                        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
                        $sql_cart = "SELECT id as product_id, part_name, price, stock_level FROM inventory WHERE id IN ($placeholders)";
                        $stmt_cart = $pdo->prepare($sql_cart);
                        $stmt_cart->execute(array_values($productIds));
                        $db_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
                    }

                    if (empty($cartItems) && empty($itemsToCheckout)) {
                        throw new Exception("Your cart is empty.");
                    }

                    // If direct purchase, merge quantities with DB data
                    if ($itemsToCheckout) {
                        foreach($itemsToCheckout as $item) {
                            if (isset($db_items[$item['product_id']])) {
                                $cartItems[] = array_merge($db_items[$item['product_id']], ['quantity' => $item['quantity'], 'product_id' => $item['product_id']]);
                            }
                        }
                    } else {
                         // Cart checkout: fetch from cart_items table
                        $sql_cart_items = "SELECT ci.product_id, ci.quantity, i.part_name, i.price, i.stock_level FROM cart_items ci JOIN inventory i ON ci.product_id = i.id WHERE ci.user_id = :user_id";
                        $stmt_cart_items = $pdo->prepare($sql_cart_items);
                        $stmt_cart_items->execute([':user_id' => $userId]);
                        $cartItems = $stmt_cart_items->fetchAll(PDO::FETCH_ASSOC);
                        if (empty($cartItems)) {
                            throw new Exception("Your cart is empty.");
                        }
                    }

                    $totalAmount = 0;
                    $orderItemsData = [];
                    $emailContent = "Thank you for your purchase! Here are the details of your order:\n\n";

                    // 2. Validate stock and calculate total
                    foreach ($cartItems as $item) {
                        if ($item['stock_level'] < $item['quantity']) {
                            throw new Exception("Insufficient stock for " . $item['part_name'] . ". Available: " . $item['stock_level'] . ", Requested: " . $item['quantity']);
                        }
                        $itemSubtotal = $item['price'] * $item['quantity'];
                        $totalAmount += $itemSubtotal;
                        $orderItemsData[] = [
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price_at_purchase' => $item['price'],
                            'subtotal' => $itemSubtotal
                        ];
                        $emailContent .= "- " . $item['part_name'] . " (x" . $item['quantity'] . ") @ ₱" . number_format($item['price'], 2) . " = ₱" . number_format($itemSubtotal, 2) . "\n";
                    }
                    $emailContent .= "\nTotal Amount: ₱" . number_format($totalAmount, 2) . "\n";
                    $emailContent .= "Payment Method: " . $paymentMethod . "\n";
                    $emailContent .= "Order Status: Processing\n\n";
                    $emailContent .= "We will notify you once your order has been shipped.\n";
                    $emailContent .= "Thank you for choosing MyNissan!";

                    // 3. Create new order
                    $sql_order = "INSERT INTO orders (user_id, total_amount, order_status, payment_status, payment_method, payment_reference, gcash_number) 
                                  VALUES (:user_id, :total_amount, 'processing', 'paid', :payment_method, :payment_reference, :gcash_number)";
                    $stmt_order = $pdo->prepare($sql_order);
                    $stmt_order->execute([
                        ':user_id' => $userId,
                        ':total_amount' => $totalAmount,
                        ':payment_method' => $paymentMethod,
                        ':payment_reference' => $paymentReference,
                        ':gcash_number' => $gcashNumber
                    ]);
                    $orderId = $pdo->lastInsertId();

                    // 4. Insert order items
                    $sql_order_items = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase, subtotal) 
                                        VALUES (:order_id, :product_id, :quantity, :price_at_purchase, :subtotal)";
                    $stmt_order_items = $pdo->prepare($sql_order_items);
                    foreach ($orderItemsData as $item) {
                        $stmt_order_items->execute(array_merge([':order_id' => $orderId], $item));
                    }

                    // 5. Update inventory stock
                    $sql_update_stock = "UPDATE inventory SET stock_level = stock_level - :quantity WHERE id = :product_id";
                    $stmt_update_stock = $pdo->prepare($sql_update_stock);
                    foreach ($cartItems as $item) {
                        $stmt_update_stock->execute([':quantity' => $item['quantity'], ':product_id' => $item['product_id']]);
                    }

                    // 6. Clear cart only if not a direct purchase
                    if (!$itemsToCheckout) {
                        $sql_clear_cart = "DELETE FROM cart_items WHERE user_id = :user_id";
                        $stmt_clear_cart = $pdo->prepare($sql_clear_cart);
                        $stmt_clear_cart->execute([':user_id' => $userId]);
                    }

                    // 7. Generate Invoice (assuming generateInvoice function exists and is accessible)
                    // The generateInvoice function needs to be updated to handle the new order structure
                    // For now, we'll pass the orderId and let it fetch details.
                    generateInvoice($pdo, null, $userId, $orderId);

                    // 8. Send Email Confirmation (assuming sendPurchaseConfirmationEmail function exists and is accessible)
                    $userEmail = $_SESSION['email']; // Assuming email is in session
                    sendPurchaseConfirmationEmail($userEmail, $orderId, $totalAmount, $emailContent);

                    // 9. Create Notification
                    create_notification($pdo, $userId, "Order Placed", "Your order #$orderId has been placed successfully!", 'customerdashboard.php?section=invoices');

                    $pdo->commit();
                    echo json_encode(['success' => true, 'message' => 'Checkout successful. Your order has been placed.']);

                } catch (Exception $e) {
                    $pdo->rollBack();
                    throw $e; // Re-throw to be caught by handleException
                }
            }
            break;


        case 'updateProfile':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $username = trim($data['username'] ?? '');

                if (empty($username)) {
                    throw new Exception("Username cannot be empty.");
                }

                try {
                    // Update username
                    $sql = "UPDATE users SET username = :username WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['username' => $username, 'id' => $userId]);
                } catch (PDOException $e) {
                    error_log("PDOException in updateProfile (username): " . $e->getMessage());
                    throw new Exception("Database error updating username.");
                }

                // Password update logic
                $current_password = $data['current_password'] ?? '';
                $new_password = $data['new_password'] ?? '';
                $confirm_password = $data['confirm_password'] ?? '';

                if (!empty($current_password) && !empty($new_password)) {
                    if ($new_password !== $confirm_password) {
                        throw new Exception("New passwords do not match.");
                    }
                    
                    try {
                        $sql_pass = "SELECT password FROM users WHERE id = :id";
                        $stmt_pass = $pdo->prepare($sql_pass);
                        $stmt_pass->execute(['id' => $userId]);
                        $user = $stmt_pass->fetch();
                    } catch (PDOException $e) {
                        error_log("PDOException in updateProfile (select password): " . $e->getMessage());
                        throw new Exception("Database error retrieving password.");
                    }

                    if (!$user || !password_verify($current_password, $user['password'])) {
                        throw new Exception("Your current password is not correct.");
                    }

                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    try {
                        $sql_update_pass = "UPDATE users SET password = :password WHERE id = :id";
                        $stmt_update_pass = $pdo->prepare($sql_update_pass);
                        $stmt_update_pass->execute(['password' => $hashed_password, 'id' => $userId]);
                    } catch (PDOException $e) {
                        error_log("PDOException in updateProfile (update password): " . $e->getMessage());
                        throw new Exception("Database error updating password.");
                    }

                    echo json_encode(['success' => true, 'message' => 'Profile and password updated successfully.']);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
                }
            }
            break;

        case 'uploadProfilePicture':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
                $file = $_FILES['profile_picture'];
                
                // Validation
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("File upload error.");
                }
                $check = getimagesize($file['tmp_name']);
                if ($check === false) {
                    throw new Exception("File is not an image.");
                }
                if ($file['size'] > 2000000) { // 2MB limit
                    throw new Exception("File is too large.");
                }

                $uploadDir = '../uploads/'; // Make sure this folder exists and is writable
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $fileName = 'user_' . $userId . '_pic.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    // Save relative path to DB
                    $dbPath = 'uploads/' . $fileName;
                    $sql = "UPDATE users SET profile_picture = :pic WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['pic' => $dbPath, 'id' => $userId]);
                    
                    echo json_encode(['success' => true, 'path' => '/Elvis_Repair/Backend/' . $dbPath]);
                } else {
                    throw new Exception("Failed to move uploaded file.");
                }
            } else {
                throw new Exception("No file uploaded.");
            }
            break;

        case 'getInvoices':
            $sql = "SELECT 
                        o.id AS order_id,
                        o.created_at,
                        o.total_amount,
                        o.order_status, 
                        o.payment_method,
                        o.payment_reference,
                        o.gcash_number,
                        GROUP_CONCAT(CONCAT(oi.quantity, 'x ', i.part_name, ' (₱', oi.price_at_purchase, ')') SEPARATOR '; ') AS purchased_items
                    FROM orders o
                    LEFT JOIN order_items oi ON o.id = oi.order_id
                    LEFT JOIN inventory i ON oi.product_id = i.id
                    WHERE o.user_id = :user_id
                    GROUP BY o.id
                    ORDER BY o.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'logout':
            logout();
            break;

        default:
            http_response_code(404);
            echo json_encode(["error" => "Action not found: " . htmlspecialchars($action)]);
            break;
    }
} catch (PDOException $e) {
    handleException($e);
} catch (Exception $e) {
    handleException($e);
}
?>