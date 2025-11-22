<?php
require_once '../../Backend/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token'])) {
        $message = "CSRF token missing.";
    } else {
        verify_csrf_token($_POST['csrf_token']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            $message = "Please enter email and password.";
        } else {
            $sql = "SELECT id, username, email, password, role, is_verified FROM users WHERE email = :email";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        if ($row = $stmt->fetch()) {
                            if (!$row['is_verified']) {
                                $message = "Please verify your email before logging in.";
                            } elseif (password_verify($password, $row['password'])) {
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $row['id'];
                                $_SESSION["username"] = $row['username'];
                                $_SESSION["role"] = $row['role'];

                                $ip_address = $_SERVER['REMOTE_ADDR'];
                                $log_sql = "INSERT INTO login_history (user_id, ip_address) VALUES (:user_id, :ip_address)";
                                $log_stmt = $pdo->prepare($log_sql);
                                $log_stmt->bindParam(':user_id', $row['id'], PDO::PARAM_INT);
                                $log_stmt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
                                $log_stmt->execute();
                                unset($log_stmt); // Close statement

                                if ($row['role'] == 'admin') {
                                    header("location: ../admindash-frontend/admindashboard.php");
                                } else {
                                    header("location: ../customerdash-frontend/customerdashboard.php");
                                }
                                exit;
                            } else {
                                $message = "The password you entered was not valid.";
                            }
                        }
                    } else {
                        $message = "No account found with that email.";
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
<title>Login - Elvis Auto Repair</title>
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
    .animate-glow {
      animation: glow 6s infinite ease-in-out;
    }
    @keyframes glow {
      0%, 100% { filter: blur(120px) opacity(0.3); }
      50% { filter: blur(160px) opacity(0.4); }
    }
  </style>
</head>
<body class="bg-rich-black font-display text-text-primary antialiased">
<div class="relative min-h-screen w-full flex items-center justify-center p-4 overflow-hidden">
<div class="absolute -top-1/4 -left-1/4 w-[500px] h-[500px] bg-caribbean-green rounded-full animate-glow"></div>
<div class="absolute -bottom-1/4 -right-1/4 w-[500px] h-[500px] bg-mountain-meadow rounded-full animate-glow [animation-delay:-3s]"></div>
<main class="relative z-10 w-full max-w-md">
<div class="glassmorphism rounded-2xl p-8 sm:p-12 shadow-glow-lg border border-glass-border">
<div class="text-center mb-8">
    <a href="../../index.php" class="inline-block mb-4">
        <img src="../../assets/img/Elvis.jpg" alt="Elvis Auto Repair Logo" class="w-24 h-auto mx-auto" />
    </a>
    <h1 class="text-3xl font-bold text-text-primary">Welcome Back</h1>
    <p class="mt-2 text-pistachio">Sign in to continue your journey.</p>
</div>
<?php if (!empty($message)) : ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        <span class="font-medium">Error!</span> <?php echo $message; ?>
    </div>
<?php endif; ?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6" method="POST">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
<div>
<label class="sr-only" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                person
              </span>
<input autocomplete="email" class="block w-full pl-12 pr-4 py-3 bg-black/30 border border-glass-border rounded-lg placeholder:text-pistachio/70 text-text-primary focus:ring-2 focus:ring-inset focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition-all duration-300 ease-in-out" id="email" name="email" placeholder="Email Address" required type="email"/>
</div>
</div>
<div>
<label class="sr-only" for="password">Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                lock
              </span>
<input autocomplete="current-password" class="block w-full pl-12 pr-12 py-3 bg-black/30 border border-glass-border rounded-lg placeholder:text-pistachio/70 text-text-primary focus:ring-2 focus:ring-inset focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition-all duration-300 ease-in-out" id="password" name="password" placeholder="Password" required type="password"/>
<button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-pistachio/70 hover:text-caribbean-green focus:outline-none" onclick="togglePasswordVisibility('password')">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</div>
<div class="flex items-center justify-between">
<div class="flex items-center">
<input class="h-4 w-4 rounded border-glass-border text-caribbean-green bg-black/30 focus:ring-caribbean-green focus:ring-offset-rich-black" id="remember-me" name="remember-me" type="checkbox"/>
<label class="ml-2 block text-sm text-pistachio" for="remember-me">Remember me</label>
</div>
<div class="text-sm">
<a class="font-medium text-caribbean-green hover:text-mountain-meadow transition-colors duration-200" href="forgot_password.php">Forgot password?</a>
</div>
</div>
<div>
<button class="flex w-full justify-center items-center gap-2 rounded-lg bg-caribbean-green px-3 py-3 text-base font-bold text-rich-black shadow-glow hover:bg-mountain-meadow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-caribbean-green transition-all duration-300 ease-in-out transform hover:scale-105" type="submit">
              Login
              <span class="material-symbols-outlined">
                arrow_forward
              </span>
</button>
</div>
</form>
<p class="mt-10 text-center text-sm text-pistachio">
          Not a member?
          <a class="font-semibold leading-6 text-caribbean-green hover:text-mountain-meadow transition-colors duration-200" href="register.php">Create an account</a>
</p>
<p class="mt-4 text-center text-sm text-pistachio">
          <a class="font-medium text-caribbean-green hover:text-mountain-meadow transition-colors duration-200" href="../../index.php">Back to Home</a>
</p>
</div>
</main>
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