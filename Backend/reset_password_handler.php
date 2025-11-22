<?php
require_once '/../api/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token'])) {
        $response['message'] = "CSRF token missing.";
        echo json_encode($response);
        exit;
    }
    verify_csrf_token($_POST['csrf_token']);

    $token = trim($_POST['token']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($token) || empty($email) || empty($password) || empty($confirm_password)) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit;
    }

    if ($password !== $confirm_password) {
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    // Validate password strength (optional, but recommended)
    if (strlen($password) < 8) {
        $response['message'] = "Password must have at least 8 characters.";
        echo json_encode($response);
        exit;
    }

    // Check token validity
    $sql = "SELECT user_id, expires_at FROM password_resets WHERE token = :token AND email = :email";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $userId = $row['user_id'];
                $expiresAt = strtotime($row['expires_at']);

                if (time() < $expiresAt) {
                    // Token is valid and not expired, update password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $update_sql = "UPDATE users SET password = :password WHERE id = :id";
                    if ($update_stmt = $pdo->prepare($update_sql)) {
                        $update_stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
                        $update_stmt->bindParam(":id", $userId, PDO::PARAM_INT);
                        if ($update_stmt->execute()) {
                            // Invalidate token after use
                            $delete_sql = "DELETE FROM password_resets WHERE token = :token";
                            $delete_stmt = $pdo->prepare($delete_sql);
                            $delete_stmt->bindParam(":token", $token, PDO::PARAM_STR);
                            $delete_stmt->execute(); // No need to check success, just try to clean up

                            $response['success'] = true;
                            $response['message'] = "Your password has been reset successfully.";
                        } else {
                            $response['message'] = "Could not update password. Please try again.";
                        }
                    }
                } else {
                    $response['message'] = "Password reset token has expired.";
                }
            } else {
                $response['message'] = "Invalid or expired password reset token.";
            }
        } else {
            $response['message'] = "Oops! Something went wrong. Please try again later.";
        }
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>