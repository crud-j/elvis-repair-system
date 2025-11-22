<?php
/* --------------------------------------------------------------
   Backend/forgot_password_handler.php
   -------------------------------------------------------------- */
ob_start();                                   // <-- prevent any stray output
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

require_once __DIR__ . '/../api/config.php';         // <-- correct path

// ---------- PHPMailer ----------
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.'
];

/* --------------------------------------------------------------
   Helper: send JSON and exit
   -------------------------------------------------------------- */
function jsonExit(array $data): void {
    ob_end_clean();               // discard any output before JSON
    echo json_encode($data);
    exit;
}

/* --------------------------------------------------------------
   Main logic
   -------------------------------------------------------------- */
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = 'Invalid request method.';
        jsonExit($response);
    }

    // ----- CSRF -----
    if (empty($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $response['message'] = 'Invalid CSRF token.';
        jsonExit($response);
    }

    $email = trim($_POST['email'] ?? '');
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        jsonExit($response);
    }

    // ----- Does the user exist? -----
    $sql = "SELECT id, username FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // **Security best-practice**: always say “link sent” even if email not found
    $linkSent = false;

    if ($user) {
        $userId   = (int)$user['id'];
        $username = $user['username'];

        // ----- Create token -----
        $token    = bin2hex(random_bytes(32));
        $expires  = date('Y-m-d H:i:s', time() + 3600);   // 1 hour

        // Delete any old token for this email first
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")
            ->execute([$email]);

        // Insert new token
        $ins = $pdo->prepare(
            "INSERT INTO password_resets (user_id, email, token, expires_at)
             VALUES (:uid, :email, :token, :exp)"
        );
        $ins->execute([
            ':uid'   => $userId,
            ':email' => $email,
            ':token' => $token,
            ':exp'   => $expires
        ]);

        // ----- Build reset URL (protocol-aware) -----
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base     = $protocol . '://' . $_SERVER['HTTP_HOST'];
        $resetUrl = $base . '/Frontend/auth/reset_password.php?token=' .
                    urlencode($token) . '&email=' . urlencode($email);

        // ----- Send mail -----
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USERNAME, 'Elvis Auto Repair');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset – Elvis Auto Repair';

        $mail->Body = <<<HTML
            <p>Hello <strong>{$username}</strong>,</p>
            <p>We received a request to reset your password.</p>
            <p style="margin:20px 0;">
                <a href="{$resetUrl}"
                   style="background:#00DF81;color:#0D0F11;padding:12px 24px;
                          text-decoration:none;border-radius:8px;font-weight:bold;">
                    Reset Password
                </a>
            </p>
            <p><small>This link expires in <strong>1 hour</strong>.</small></p>
            <p>If you didn’t request this, just ignore this email.</p>
            <hr>
            <small>Elvis Auto Repair &copy; {{year}}</small>
HTML;
        $mail->AltBody = "Hello {$username},\n\nClick this link to reset your password: {$resetUrl}\n\nIt expires in 1 hour.\n\nIgnore if you didn’t request it.";

        if ($mail->send()) {
            $linkSent = true;
        } else {
            error_log('PHPMailer error: ' . $mail->ErrorInfo);
        }
    }

    // ----- Unified success message (hides existence) -----
    $response['success'] = true;
    $response['message'] = 'If the email is registered, a reset link has been sent.';
    jsonExit($response);

} catch (Exception $e) {
    error_log('forgot_password_handler.php exception: ' . $e->getMessage());
    $response['message'] = 'Server error – please try again later.';
    jsonExit($response);
}
?>