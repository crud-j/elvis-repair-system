<?php
require_once '../../Backend/config.php';
require_once '../../vendor/phpmailer/phpmailer/src/Exception.php';
require_once '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token'])) {
        $message = "CSRF token missing.";
    } else {
        verify_csrf_token($_POST['csrf_token']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $password_confirm = trim($_POST['password-confirm']);

        if ($password !== $password_confirm) {
            $message = "Passwords do not match.";
        } else {
            $sql = "SELECT id FROM users WHERE email = :email";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        $message = "This email is already taken.";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

                        $sql = "INSERT INTO users (username, email, password, verification_code) VALUES (:username, :email, :password, :verification_code)";
                        if ($stmt = $pdo->prepare($sql)) {
                            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
                            $stmt->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);

                            if ($stmt->execute()) {
                                $mail = new PHPMailer(true);
                                try {
                                    $mail->isSMTP();
                                    $mail->Host = SMTP_HOST;
                                    $mail->SMTPAuth = true;
                                    $mail->Username = SMTP_USERNAME;
                                    $mail->Password = SMTP_PASSWORD;
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                    $mail->Port = SMTP_PORT;

                                    $mail->setFrom(SMTP_USERNAME, 'Elvis Auto Repair');
                                    $mail->addAddress($email, $username);

                                    $mail->isHTML(true);
                                    $mail->Subject = 'Email Verification';
                                    $mail->Body    = "Your verification code is: <b>$verification_code</b>";

                                    $mail->send();
                                    $_SESSION['message'] = 'Registration successful! Please check your email for the verification code.';
                                    header("location: verify.php?email=" . urlencode($email));
                                    exit();
                                } catch (Exception $e) {
                                    $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                            } else {
                                $message = "Something went wrong. Please try again later.";
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Register - Create Your Account</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "rich-black": "#0D0F11",
                    "dark-green": "#032221",
                    "caribbean-green": "#00DF81",
                    "mountain-meadow": "#22CC95",
                    "text-primary": "#F7F7F6",
                    "pistachio": "#A6CEC4",
                    "glass-border": "rgba(47, 166, 140, 0.2)",
                },
                fontFamily: {
                    display: ["Roboto", "sans-serif"],
                },
                borderRadius: {
                    DEFAULT: "0.75rem",
                    "xl": "1rem",
                    "2xl": "1.5rem",
                },
                boxShadow: {
                    'glow': '0 0 15px rgba(0, 223, 129, 0.1), 0 0 5px rgba(0, 223, 129, 0.08)',
                    'glow-lg': '0 0 25px rgba(0, 223, 129, 0.15), 0 0 10px rgba(0, 223, 129, 0.1)',
                }
            },
        },
      };
    </script>
<style>
        .glassmorphism {
            background-image: linear-gradient(to top right, rgba(166, 206, 196, 0.1), rgba(166, 206, 196, 0.03));
            border-color: var(--glass-border);
            backdrop-filter: blur(10px);
        }
        @keyframes pulse-glow {
            0%, 100% {
                filter: blur(120px) opacity(0.3);
            }
            50% {
                filter: blur(160px) opacity(0.4);
            }
        }
        .animate-pulse-glow {
            animation: pulse-glow 5s infinite ease-in-out;
        }
    </style>
</head>
<body class="font-display bg-rich-black text-text-primary antialiased">
<div class="min-h-screen flex items-center justify-center p-4">
<div class="absolute inset-0 z-0 overflow-hidden">
<div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-caribbean-green rounded-full animate-pulse-glow"></div>
<div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-mountain-meadow rounded-full animate-pulse-glow" style="animation-delay: 2.5s;"></div>
</div>
<div class="w-full max-w-md p-8 sm:p-10 space-y-8 rounded-2xl glassmorphism border border-glass-border shadow-glow-lg relative z-10">
<div class="text-center mb-8">
    <a href="../../index.php" class="inline-block mb-4">
        <img src="../../assets/img/Elvis.jpg" alt="Elvis Auto Repair Logo" class="w-24 h-auto mx-auto" />
    </a>
    <h1 class="text-3xl font-bold text-text-primary">Create an Account</h1>
    <p class="mt-2 text-sm text-pistachio">Join us and start your journey today.</p>
</div>
<?php if (!empty($message)) : ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        <span class="font-medium">Error!</span> <?php echo $message; ?>
    </div>
<?php endif; ?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6" method="POST">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
<div>
<label class="sr-only" for="username">Username</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                            person
                        </span>
<input autocomplete="username" class="w-full pl-12 pr-4 py-3 rounded-lg border border-glass-border bg-black/30 text-text-primary placeholder-pistachio/70 focus:ring-2 focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition duration-300" id="username" name="username" placeholder="Username" required type="text"/>
</div>
</div>
<div>
<label class="sr-only" for="email">Email address</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                            mail
                        </span>
<input autocomplete="email" class="w-full pl-12 pr-4 py-3 rounded-lg border border-glass-border bg-black/30 text-text-primary placeholder-pistachio/70 focus:ring-2 focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition duration-300" id="email" name="email" placeholder="Email address" required type="email"/>
</div>
</div>
<div>
<label class="sr-only" for="password">Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                            lock
                        </span>
<input autocomplete="new-password" class="w-full pl-12 pr-12 py-3 rounded-lg border border-glass-border bg-black/30 text-text-primary placeholder-pistachio/70 focus:ring-2 focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition duration-300" id="password" name="password" placeholder="Password" required type="password"/>
<button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-pistachio/70 hover:text-caribbean-green focus:outline-none" onclick="togglePasswordVisibility('password')">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</div>
<div>
<label class="sr-only" for="password-confirm">Confirm Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                            lock_person
                        </span>
<input autocomplete="new-password" class="w-full pl-12 pr-12 py-3 rounded-lg border border-glass-border bg-black/30 text-text-primary placeholder-pistachio/70 focus:ring-2 focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition duration-300" id="password-confirm" name="password-confirm" placeholder="Confirm Password" required type="password"/>
<button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-pistachio/70 hover:text-caribbean-green focus:outline-none" onclick="togglePasswordVisibility('password-confirm')">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</div>
<div>
<button class="flex w-full justify-center items-center gap-2 rounded-lg bg-caribbean-green px-3 py-3 text-base font-bold text-rich-black shadow-glow hover:bg-mountain-meadow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-caribbean-green transition-all duration-300 ease-in-out transform hover:scale-105" type="submit">
              Create Account
              <span class="material-symbols-outlined">
                arrow_forward
              </span>
</button>
</div>
</form>
<p class="mt-8 text-center text-sm text-pistachio">
                Already have an account?
                <a class="font-medium text-caribbean-green hover:text-mountain-meadow transition" href="login.php">
                    Log in
                </a>
</p>
<p class="mt-4 text-center text-sm text-pistachio">
                <a class="font-medium text-caribbean-green hover:text-mountain-meadow transition" href="../../index.php">
                    Back to Home
                </a>
</p>
</div>
</div>

<script>
    function togglePasswordVisibility(inputId) {
      const passwordInput = document.getElementById(inputId);
      const icon = event.currentTarget.querySelector('span');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility';
      } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility_off';
      }
    }
  </script>
</body>
</html>