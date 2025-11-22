<?php
require_once '/../api/config.php';

$message = '';
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if (empty($token) || empty($email)) {
    $message = "Invalid password reset link.";
}

if (isset($_SESSION['csrf_token'])) {
    $csrf_token = $_SESSION['csrf_token'];
} else {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Reset Password - Elvis Auto Repair</title>
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
    <h1 class="text-3xl font-bold text-white">Reset Your Password</h1>
    <p class="mt-2 text-pistachio">Enter your new password below.</p>
</div>
<div id="message-container" class="mb-4">
    <?php if (!empty($message)) : ?>
        <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            <span class="font-medium">Error!</span> <?php echo $message; ?>
        </div>
    <?php endif; ?>
</div>
<form id="reset-password-form" class="space-y-6">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
<input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
<input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
<div>
<label class="sr-only" for="password">New Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                lock
              </span>
<input autocomplete="new-password" class="block w-full pl-12 pr-12 py-3 bg-black/30 border border-glass-border rounded-lg placeholder:text-pistachio/70 text-text-primary focus:ring-2 focus:ring-inset focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition-all duration-300 ease-in-out" id="password" name="password" placeholder="New Password" required="" type="password"/>
<button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-pistachio/70 hover:text-caribbean-green focus:outline-none" onclick="togglePasswordVisibility('password')">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</div>
<div>
<label class="sr-only" for="confirm_password">Confirm New Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                lock_person
              </span>
<input autocomplete="new-password" class="block w-full pl-12 pr-12 py-3 bg-black/30 border border-glass-border rounded-lg placeholder:text-pistachio/70 text-text-primary focus:ring-2 focus:ring-inset focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition-all duration-300 ease-in-out" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required="" type="password"/>
<button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-pistachio/70 hover:text-caribbean-green focus:outline-none" onclick="togglePasswordVisibility('confirm_password')">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</div>
<div>
<button class="flex w-full justify-center items-center gap-2 rounded-lg bg-caribbean-green px-3 py-3 text-base font-bold text-rich-black shadow-glow hover:bg-mountain-meadow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-caribbean-green transition-all duration-300 ease-in-out transform hover:scale-105" type="submit">
              Reset Password
              <span class="material-symbols-outlined">
                refresh
              </span>
</button>
</div>
</form>
<p class="mt-10 text-center text-sm text-pistachio">
          <a class="font-medium text-caribbean-green hover:text-mountain-meadow transition-colors duration-200" href="login.php">Back to Login</a>
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

<script>
    document.getElementById('reset-password-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const messageContainer = document.getElementById('message-container');

        fetch('../../Backend/reset_password_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            messageContainer.innerHTML = ''; // Clear previous messages
            let alertClass = data.success ? 'text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800' : 'text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800';
            let alertTitle = data.success ? 'Success!' : 'Error!';
            messageContainer.innerHTML = `
                <div class="p-4 mb-4 text-sm ${alertClass} rounded-lg" role="alert">
                    <span class="font-medium">${alertTitle}</span> ${data.message}
                </div>
            `;
            if (data.success) {
                form.reset();
                // Optionally redirect to login page after a short delay
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageContainer.innerHTML = `
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                    <span class="font-medium">Error!</span> An unexpected error occurred.
                </div>
            `;
        });
    });
</script>
</body>
</html>