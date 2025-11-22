<?php
require_once '../../Backend/config.php';

$message = '';
$error = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $verification_code = trim($_POST['verification_code']);

    if (empty($email) || empty($verification_code)) {
        $error = "Please enter the verification code.";
    } else {
        $sql = "SELECT * FROM users WHERE email = :email AND verification_code = :verification_code";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    // Verification code is correct, update user's status
                    $sql_update = "UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = :email";
                    if ($stmt_update = $pdo->prepare($sql_update)) {
                        $stmt_update->bindParam(":email", $email, PDO::PARAM_STR);
                        if ($stmt_update->execute()) {
                            $_SESSION['login_message'] = 'Email verified successfully! You can now log in.';
                            header("location: login.php");
                            exit();
                        } else {
                            $error = "Oops! Something went wrong. Please try again later.";
                        }
                    }
                } else {
                    $error = "Invalid verification code.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}

$email_from_url = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Verify Email - Elvis Auto Repair</title>
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
</style>
</head>
<body class="bg-rich-black font-display text-text-primary antialiased">
<div class="relative min-h-screen w-full flex items-center justify-center p-4">
<main class="relative z-10 w-full max-w-md">
<div class="glassmorphism rounded-2xl p-8 sm:p-12 shadow-lg border border-glass-border">
<div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-white">Verify Your Email</h1>
    <p class="mt-2 text-pistachio">A verification code has been sent to your email.</p>
</div>
<?php if (!empty($message)) : ?>
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
<?php if (!empty($error)) : ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif; ?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6" method="POST">
<input type="hidden" name="email" value="<?php echo $email_from_url; ?>" />
<div>
<label class="sr-only" for="verification_code">Verification Code</label>
<input class="block w-full px-4 py-3 bg-black/30 border border-glass-border rounded-lg placeholder:text-pistachio/70 text-text-primary focus:ring-2 focus:ring-inset focus:ring-caribbean-green" id="verification_code" name="verification_code" placeholder="Enter 6-digit code" required="" type="text"/>
</div>
<div>
<button class="flex w-full justify-center items-center gap-2 rounded-lg bg-caribbean-green px-3 py-3 text-base font-bold text-rich-black shadow-lg hover:bg-mountain-meadow" type="submit">Verify Account</button>
</div>
</form>
</div>
</main>
</div>
</body>
</html>