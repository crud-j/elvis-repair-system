<?php
require_once '/../api/config.php';

$message = '';

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
<title>Forgot Password - Elvis Auto Repair</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <h1 class="text-3xl font-bold text-white">Forgot Your Password?</h1>
    <p class="mt-2 text-pistachio">Enter your email to receive a reset link.</p>
</div>

<form id="forgot-password-form" class="space-y-6">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
<div>
<label class="sr-only" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-caribbean-green/70">
                mail
              </span>
<input autocomplete="email" class="block w-full pl-12 pr-4 py-3 bg-black/30 border border-glass-border rounded-lg placeholder:text-pistachio/70 text-text-primary focus:ring-2 focus:ring-inset focus:ring-caribbean-green focus:border-caribbean-green focus:outline-none transition-all duration-300 ease-in-out" id="email" name="email" placeholder="Email Address" required="" type="email"/>
</div>
</div>
<div>
<button class="flex w-full justify-center items-center gap-2 rounded-lg bg-caribbean-green px-3 py-3 text-base font-bold text-rich-black shadow-glow hover:bg-mountain-meadow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-caribbean-green transition-all duration-300 ease-in-out transform hover:scale-105" type="submit">
              Send Reset Link
              <span class="material-symbols-outlined">
                send
              </span>
</button>
</div>
</form>
<p class="mt-10 text-center text-sm text-pistachio">
          Remember your password?
          <a class="font-medium text-caribbean-green hover:text-mountain-meadow transition-colors duration-200" href="login.php">Login here</a>
</p>
</div>
</main>
</div>

<script>
    document.getElementById('forgot-password-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        fetch('../../Backend/forgot_password_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // If response is not OK, it might not be JSON, so handle as text
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonColor: '#00DF81'
                });
                form.reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                    confirmButtonColor: '#00DF81'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again later. ' + error.message,
                confirmButtonColor: '#00DF81'
            });
        });
    });
</script>
</body>
</html>