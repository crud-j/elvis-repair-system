<?php
require_once '/../api/config.php'; // Includes session_start()

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Require Composer's autoloader

// Check if the user is logged in. Booking requires a user account.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION['error_message'] = "You must be logged in to book an appointment. Please log in or create an account.";
    header("location: ../Frontend/auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token'])) {
        $_SESSION['booking_error'] = "CSRF token missing. Please try again.";
        header("location: ../index.php?booking=error#booking");
        exit;
    }
    verify_csrf_token($_POST['csrf_token']);

    // --- Form Data Retrieval and Sanitization ---
    $userId = $_SESSION['id'];
    $technician_id = filter_input(INPUT_POST, 'technician_id', FILTER_VALIDATE_INT);
    $vehicle_name_input = htmlspecialchars(trim($_POST['vehicle_name']), ENT_QUOTES, 'UTF-8');
    $appointment_date = trim($_POST['date']);
    $appointment_time = trim($_POST['time']); // e.g., "08:00:00"
    $notes = htmlspecialchars(trim($_POST['notes']), ENT_QUOTES, 'UTF-8');
    $package_name = htmlspecialchars(trim($_POST['package_name']), ENT_QUOTES, 'UTF-8');
    // The amount is now verified on the server, not taken from a hidden input.
    $payment_reference = htmlspecialchars(trim($_POST['payment_reference']), ENT_QUOTES, 'UTF-8');
    $gcash_name = htmlspecialchars(trim($_POST['gcash_name']), ENT_QUOTES, 'UTF-8');

    // --- Server-Side Validation ---
    $errors = [];

    // 1. Authoritative pricing. Fetch price from the database.
    $price_stmt = $pdo->prepare("SELECT price FROM services WHERE name = :name");
    $price_stmt->execute([':name' => $package_name]);
    $service_data = $price_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service_data) {
        $errors[] = "Invalid service package selected.";
    } else {
        // Set the amount based on the server's price list.
        $server_verified_amount = (float)$service_data['price'];
    }

    if (empty($vehicle_name_input)) {
        $errors[] = "Please enter your vehicle details.";
    }

    // 2. Validate date and time format and ensure it's in the future.
    $appointment_datetime_str = $appointment_date . ' ' . $appointment_time;
    $appointment_datetime = DateTime::createFromFormat('Y-m-d H:i:s', $appointment_datetime_str);

    if ($appointment_datetime === false || $appointment_datetime->format('Y-m-d H:i:s') !== $appointment_datetime_str) {
        $errors[] = "Invalid date format. Please use YYYY-MM-DD.";
    } else {
        $today = new DateTime();
        $selectedDate = DateTime::createFromFormat('Y-m-d', $appointment_date);
        if ($selectedDate < $today) {
            $errors[] = "Appointment date cannot be in the past.";
        }
    }

    // 3. Check if technician exists
    if ($technician_id === false) {
        $errors[] = "Invalid technician selected.";
    } else {
        $sql_check_tech = "SELECT id FROM technicians WHERE id = :technician_id";
        $stmt_check_tech = $pdo->prepare($sql_check_tech);
        $stmt_check_tech->execute([':technician_id' => $technician_id]);
        if ($stmt_check_tech->rowCount() == 0) {
            $errors[] = "The selected technician does not exist.";
        }
    }

    // 4. Check if the time slot is already booked for that technician
    if (empty($errors)) { // Only check if other data is valid
        $sql_check_slot = "SELECT id FROM appointments WHERE technician_id = :technician_id AND appointment_date = :appointment_date AND appointment_time = :appointment_time AND status != 'cancelled'";
        $stmt_check_slot = $pdo->prepare($sql_check_slot);
        $stmt_check_slot->execute([
            'technician_id' => $technician_id,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time
        ]);
        if ($stmt_check_slot->rowCount() > 0) {
            $errors[] = "Sorry, this time slot has just been booked. Please select another time.";
        }
    }

    // 5. Check for empty payment details
    if (empty($payment_reference)) {
        $errors[] = "GCash reference number is required.";
    }

    if (empty($gcash_name)) {
        $errors[] = "GCash account name is required.";
    }

    if (!empty($errors)) {
        $_SESSION['booking_error'] = implode('<br>', $errors);
        header("location: ../index.php?booking=error#booking");
        exit;
    }

    // --- Database Insertion ---
    $sql = "INSERT INTO appointments (user_id, vehicle_name, technician_id, appointment_date, appointment_time, notes, package_name, amount, payment_reference, gcash_name, status) 
            VALUES (:user_id, :vehicle_name, :technician_id, :appointment_date, :appointment_time, :notes, :package_name, :amount, :payment_reference, :gcash_name, :status)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":vehicle_name", $vehicle_name_input, PDO::PARAM_STR);
        $stmt->bindParam(":technician_id", $technician_id, PDO::PARAM_INT);
        $stmt->bindParam(":appointment_date", $appointment_date, PDO::PARAM_STR);
        $stmt->bindParam(":appointment_time", $appointment_time, PDO::PARAM_STR);
        $stmt->bindParam(":notes", $notes, PDO::PARAM_STR);
        $stmt->bindParam(":package_name", $package_name, PDO::PARAM_STR); // The name from the form
        $stmt->bindParam(":amount", $server_verified_amount, PDO::PARAM_STR); // The SERVER-VERIFIED amount
        $stmt->bindParam(":payment_reference", $payment_reference, PDO::PARAM_STR); 
        $stmt->bindParam(":gcash_name", $gcash_name, PDO::PARAM_STR);
        
        $status = 'pending'; // Default status for new bookings
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // --- Success ---

            // --- Send Confirmation Email ---
            // First, get user's username and email
            $user_sql = "SELECT username, email FROM users WHERE id = :user_id";
            $user_stmt = $pdo->prepare($user_sql);
            $user_stmt->execute(['user_id' => $userId]);
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $mail = new PHPMailer(true);
                try {
                    // Server settings from your config.php (you need to define these)
                    $mail->isSMTP();
                    $mail->Host       = SMTP_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = SMTP_USERNAME;
                    $mail->Password   = SMTP_PASSWORD;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = SMTP_PORT;

                    // Recipients
                    $mail->setFrom('no-reply@elvisautorepair.com', 'Elvis Auto Repair');
                    $mail->addAddress($user['email'], $user['username']);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Booking is Confirmed!';
                    $email_body = "
                        <h2>Booking Confirmation</h2>
                        <p>Hello " . htmlspecialchars($user['username']) . ",</p>
                        <p>Your appointment at Elvis Auto Repair has been successfully booked. Here are the details:</p>
                        <ul>
                            <li><strong>Vehicle:</strong> " . htmlspecialchars($vehicle_name_input) . "</li>
                            <li><strong>Package:</strong> " . htmlspecialchars($package_name) . "</li>
                            <li><strong>Date:</strong> " . htmlspecialchars($appointment_date) . "</li>
                            <li><strong>Time:</strong> " . htmlspecialchars(date('h:i A', strtotime($appointment_time))) . "</li>
                            <li><strong>Amount Paid:</strong> PHP " . number_format($server_verified_amount, 2) . "</li>
                        </ul>
                        <p>We look forward to servicing your vehicle!</p>
                        <p>Sincerely,<br>The Elvis Auto Repair Team</p>
                    ";
                    $mail->Body = $email_body;

                    $mail->send();
                } catch (Exception $e) {
                    // Optional: Log email error, but don't stop the user flow
                    error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                }
            }

            // Set a session variable to show a success message on the index page
            // Notify all admins about the new booking
            $admin_sql = "SELECT id FROM users WHERE role = 'admin'";
            $admin_stmt = $pdo->query($admin_sql);
            $admins = $admin_stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($admins as $admin) {
                $title = "New Booking Received";
                $message = "A new appointment has been booked for " . htmlspecialchars($appointment_date) . ".";
                create_notification($pdo, $admin['id'], $title, $message, 'admindashboard.php?section=bookings');
            }

            $_SESSION['booking_success'] = "Your appointment has been successfully booked! A confirmation will be sent to you shortly.";
            
            // Redirect back to the booking section of the index page
            header("location: ../index.php?booking=success#booking");
            exit();
        } else {
            // --- Database Error ---
            $_SESSION['booking_error'] = "Oops! Something went wrong with the database. Please try again later.";
            header("location: ../index.php?booking=error#booking");
            exit;
        }

        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);

} else {
    // If not a POST request, redirect to home
    header("location: ../index.php");
    exit;
}
?>