<?php
require_once 'config.php'; // This includes session_start() and the PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token'])) {
        $_SESSION['contact_error'] = "CSRF token missing. Please try again.";
        header("Location: ../index.php#contact");
        exit;
    }
    verify_csrf_token($_POST['csrf_token']);

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['contact_error'] = "Please fill in all fields.";
        header("Location: ../index.php#contact");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = "Invalid email format.";
        header("Location: ../index.php#contact");
        exit;
    }

    try {
        // Insert the message into the new table
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['contact_success'] = "Your message has been sent successfully! We will get back to you shortly.";

            // Notify all admins
            $admin_sql = "SELECT id FROM users WHERE role = 'admin'";
            $admin_stmt = $pdo->query($admin_sql);
            $admins = $admin_stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($admins as $admin) {
                $title = "New Contact Message";
                $msg_text = "You have a new message from " . htmlspecialchars($name) . ".";
                create_notification($pdo, $admin['id'], $title, $msg_text, 'admindashboard.php?section=contacts');
            }
        } else {
            $_SESSION['contact_error'] = "Something went wrong. Please try again later.";
        }
    } catch (PDOException $e) {
        error_log("Contact Form Error: " . $e->getMessage());
        $_SESSION['contact_error'] = "A database error occurred. Please try again later.";
    }

    header("Location: ../index.php#contact");
    exit;
} else {
    // Redirect if accessed directly
    header("Location: ../index.php");
    exit;
}