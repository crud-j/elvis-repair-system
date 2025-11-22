<?php
require_once __DIR__ . '/../api/config.php';
 // This will start the session
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Elvis AutoRepair — Home</title>
  <!-- CSS (relative paths for localhost) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>

  <!-- Tailwind CSS for About Us, Services, and Membership -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700;900&family=Noto+Sans:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

  <!-- SweetAlert2 for better alerts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Inline Styles and Scripts -->
  <style type="text/tailwindcss">
    @layer base {
      :root, html.dark {
        --background: #0D0F11;
        --dark-green: #032221;
        --bangladesh-green: #02624C;
        --mountain-meadow: #22CC95;
        --caribbean-green: #00DF81;
        --text-primary: #F7F7F6;
        --pine: #062B23;
        --basil: #084F3A;
        --frog: #178D69;
        --mint: #2FA68C;
        --stone: #707D7D;
        --pistachio: #A6CEC4;
        --glass-bg: rgba(166, 206, 196, 0.05);
        --glass-border: rgba(47, 166, 140, 0.2);
      }
    }
    body {
        font-family: 'Roboto', sans-serif;
        background-color: var(--background);
        color: var(--text-primary);
    }
    .glass-card {
        background-image: linear-gradient(to top right, rgba(166, 206, 196, 0.1), rgba(166, 206, 196, 0.03));
        border-color: var(--glass-border);
        backdrop-filter: blur(10px);
    }
    .glow-button {
        background-color: var(--caribbean-green);
        color: var(--background);
        box-shadow: 0 0 15px rgba(0, 223, 129, 0.1), 0 0 5px rgba(0, 223, 129, 0.08);
    }
    .glow-button:hover {
        background-color: var(--mountain-meadow);
        box-shadow: 0 0 25px rgba(0, 223, 129, 0.15), 0 0 10px rgba(0, 223, 129, 0.1);
    }
    .secondary-text {
        color: var(--pistachio);
    }
    .main-header {
        color: var(--text-primary);
    }
    html {
      scroll-behavior: smooth;
    }
  </style>
  <script >
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "rich-black": "var(--background)",
            "dark-green": "var(--dark-green)",
            "bangladesh-green": "var(--bangladesh-green)",
            "mountain-meadow": "var(--mountain-meadow)",
            "caribbean-green": "var(--caribbean-green)",
            "text-primary": "var(--text-primary)",
            "pine": "var(--pine)",
            "basil": "var(--basil)",
            "frog": "var(--frog)",
            "mint": "var(--mint)",
            "stone": "var(--stone)",
            "pistachio": "var(--pistachio)"
          },
          fontFamily: {
            "display": ["Roboto", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.5rem",
            "lg": "0.75rem",
            "xl": "1rem",
            "full": "9999px"
          },
          animation: {
            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
            'fade-in': 'fadeIn 0.8s ease-out forwards'
          },
          boxShadow: {
            'glass': '0 4px 30px rgba(0, 0, 0, 0.1)',
            'glass-dark': '0 4px 30px rgba(0, 0, 0, 0.2)'
          },
          keyframes: {
            fadeInUp: {
              '0%': {
                opacity: '0',
                transform: 'translateY(20px)'
              },
              '100%': {
                opacity: '1',
                transform: 'translateY(0)'
              },
            },
            fadeIn: {
              '0%': {
                opacity: '0'
              },
              '100%': {
                opacity: '1'
              },
            },
          },
        },
      },
    }
  </script>

</head>

<body class="font-display text-text-primary antialiased relative bg-rich-black">
  <!-- ---------- HEADER ---------- -->
  <header id="site-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 py-4 px-6 bg-transparent">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between gap-6">
      <!-- Brand -->
      <a href="#" class="flex items-center gap-2 text-text-primary font-bold text-base">
        <img src="assets/img/Elvis.jpg" alt="AutoCarePro logo" class="w-20 h-auto" />
        <span class="hidden sm:inline font-bold text-lg">Elvis<span class="text-caribbean-green">Auto Repair</span></span>
      </a>

      <!-- Navigation -->
      <nav class="hidden md:block">
        <ul class="flex items-center gap-8">
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#home">Home</a></li>
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#about">About</a></li>
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#services">Services</a></li>
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#coaching">Technicians</a></li>
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#membership">Plans</a></li>
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#booking">Booking</a></li>
          <li><a class="nav-link nav-link-underline text-white/85 hover:text-white text-sm font-medium" href="#contact">Contact</a></li>
        </ul>
      </nav>

      <!-- Auth Buttons / User Dropdown (glassmorphism) -->
      <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) : ?>
        <!-- User Dropdown -->
        <div class="hidden md:block relative">
          <button id="user-dropdown-toggle" class="flex items-center gap-3 px-4 py-2 rounded-full border border-glass-border glass-card text-text-primary text-sm font-semibold hover:bg-dark-green transition">
            <span class="material-symbols-outlined">account_circle</span>
            <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
            <span class="material-symbols-outlined">arrow_drop_down</span>
          </button>
          <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2 w-56 rounded-xl glass-card border border-glass-border shadow-lg py-2 z-50">
            <a href="Frontend/customerdash-frontend/customerdashboard.php" class="flex items-center gap-3 px-4 py-2 text-sm text-pistachio hover:bg-dark-green transition">
              <span class="material-symbols-outlined">dashboard</span>
              <span>Go to Dashboard</span>
            </a>
            <div class="border-t border-glass-border my-2"></div>
            <a href="Backend/logout.php" class="flex items-center gap-3 px-4 py-2 text-sm text-pistachio hover:bg-dark-green transition">
              <span class="material-symbols-outlined">logout</span>
              <span>Logout</span>
            </a>
          </div>
        </div>
      <?php else : ?>
        <!-- Login/Sign Up Buttons -->
        <div class="hidden md:flex items-center gap-3">
          <a href="Frontend/auth/login.php" class="px-5 py-2 rounded-full border border-glass-border glass-card text-text-primary text-sm font-semibold uppercase hover:text-text-primary transition">Login</a>
          <a href="Frontend/auth/register.php" class="px-5 py-2 rounded-full glow-button text-sm font-semibold uppercase shadow-lg hover:scale-105 transition">Sign Up</a>
        </div>
      <?php endif; ?>

  </header>

  <!-- Mobile menu button -->
  <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-text-primary border border-glass-border glass-card">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <path d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>
  </div>

  <!-- Mobile menu -->
  <nav id="mobile-menu" class="hidden absolute top-full left-0 right-0 bg-rich-black/90 backdrop-blur-md border-t border-glass-border z-50 flex flex-col gap-2 p-4 md:hidden">
    <a href="#home" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Home</a>
    <a href="#about" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">About Us</a>
    <a href="#services" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Services</a>
    <a href="#technicians" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Technicians</a>
    <a href="#membership" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Plans</a>
    <a href="#booking" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Booking</a>
    <a href="#contact" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Contact</a>
    <a href="Frontend/auth/login.php" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Login</a>
    <a href="Frontend/auth/register.php" class="nav-link py-3 px-4 text-text-primary border-b border-glass-border hover:bg-dark-green transition rounded-md">Sign Up</a>
  </nav>
  </header>

  <script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });

    (function() {
      const toggle = document.getElementById('user-dropdown-toggle');
      const menu = document.getElementById('user-dropdown-menu');
      if (!toggle || !menu) return;

      const closeMenu = () => {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
      };
      const openMenu = () => {
        menu.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
      };

      toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      });

      // close on outside click
      document.addEventListener('click', function(e) {
        if (!menu.classList.contains('hidden')) {
          if (!menu.contains(e.target) && !toggle.contains(e.target)) closeMenu();
        }
      });

      // close on escape
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMenu();
      });
    })();
  </script>

  <!-- ---------- HERO ---------- -->
<section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Image Slideshow Background -->
    <div class="absolute inset-0 z-0 w-full h-full">
        <?php
        $hero_images = glob('assets/img/HeroImage/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        foreach ($hero_images as $index => $image) {
            // The 'active' class on the first image makes it visible initially
            $active_class = ($index == 0) ? 'active' : '';
            echo "<img src='" . htmlspecialchars($image) . "' alt='Hero background image " . ($index + 1) . "' class='hero-slide " . $active_class . "' />";
        }
        ?>
    </div>
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/70 z-10"></div>

    <!-- Background Glow Effects -->
    <div class="absolute -left-40 top-1/2 -translate-y-1/2 w-[420px] h-[420px] rounded-full blur-[160px] bg-caribbean-green/20 -z-10"></div>
    <div class="absolute -right-40 top-1/2 -translate-y-1/2 w-[420px] h-[420px] rounded-full blur-[160px] bg-mountain-meadow/10 -z-10"></div>

    <!-- Hero Content -->
    <div class="relative z-20 text-center px-6 space-y-8" data-aos="fade-up">
        <h1 class="font-display text-text-primary text-[clamp(40px,7vw,80px)] font-black uppercase leading-tight tracking-tighter drop-shadow-lg">
            Precision Repair<br>
            <span class="bg-gradient-to-r from-caribbean-green to-mountain-meadow bg-clip-text text-transparent">Peak Performance</span>
        </h1>
        <p class="text-pistachio max-w-2xl mx-auto text-lg sm:text-xl leading-relaxed">
            Trust Elvis Auto Repair's expert technicians and modern equipment to get you back on the road safely and efficiently.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
            <a href="#booking" class="w-full sm:w-auto flex items-center justify-center gap-3 px-8 py-4 rounded-full text-lg font-bold text-rich-black bg-gradient-to-r from-caribbean-green to-mountain-meadow shadow-lg shadow-caribbean-green/20 hover:scale-105 hover:shadow-xl hover:shadow-caribbean-green/30 transition-all duration-300">
                <span class="material-symbols-outlined">calendar_month</span>
                Schedule a Service
            </a>
            <a href="#services" class="w-full sm:w-auto flex items-center justify-center gap-3 px-8 py-4 rounded-full text-lg font-semibold text-text-primary border-2 border-glass-border bg-white/10 backdrop-blur-md hover:scale-105 hover:bg-white/20 hover:border-caribbean-green/80 transition-all duration-300">
                <span class="material-symbols-outlined">construction</span>
                Explore Services
            </a>
        </div>
    </div>
</section>

<!-- Styles and Script for Hero Slideshow -->
<style>
    .hero-slide {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1.5s ease-in-out;
    }
    .hero-slide.active {
        opacity: 1;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-slide');
    let currentSlide = 0;
    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }, 5000); // Change image every 5 seconds
});
</script>

 <!-- ---------- ABOUT US (Redesigned) ---------- -->
<section id="about" class="relative py-24 sm:py-32 overflow-hidden bg-rich-black">
  <!-- Background elements -->
  <div class="absolute inset-0 -z-20">
    <!-- Grid pattern -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,223,129,0.08)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,223,129,0.08)_1px,transparent_1px)] bg-[size:36px_36px] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_50%,#000_60%,transparent_100%)]"></div>
    <!-- Glow -->
    <div class="absolute top-1/2 left-1/2 w-[600px] h-[600px] -translate-x-1/2 -translate-y-1/2 bg-gradient-to-tr from-caribbean-green/20 to-mountain-meadow/10 rounded-full blur-[180px] animate-pulse"></div>
  </div>

  <div class="container mx-auto px-6 relative z-10">
    <!-- Header -->
    <div class="max-w-3xl mx-auto text-center mb-16 sm:mb-20" data-aos="fade-up">
      <h2 class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-caribbean-green to-mountain-meadow bg-clip-text text-transparent mb-4">
        About Elvis Auto Repair
      </h2>
      <p class="text-lg sm:text-xl text-pistachio font-medium tracking-wide">
        Driven by Excellence • Powered by Innovation
      </p>
      <div class="w-24 h-[3px] bg-gradient-to-r from-caribbean-green to-mountain-meadow mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
      <!-- Left Column: Image & Key Strengths -->
      <div class="space-y-8">
        <div class="relative group" data-aos="fade-right" data-aos-delay="100">
          <div class="overflow-hidden rounded-3xl shadow-[0_0_50px_-10px_rgba(0,223,129,0.2)]">
            <img src="assets\img\Elvis.jpg" alt="Elvis Auto Repair workshop with modern equipment" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700" />
          </div>
          <div class="absolute inset-0 rounded-3xl bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center" data-aos="fade-up" data-aos-delay="200">
          <div class="glass-card border border-glass-border rounded-xl p-4">
            <span class="material-symbols-outlined text-caribbean-green text-4xl">engineering</span>
            <p class="font-semibold text-text-primary mt-2">Expert Technicians</p>
          </div>
          <div class="glass-card border border-glass-border rounded-xl p-4">
            <span class="material-symbols-outlined text-caribbean-green text-4xl">verified</span>
            <p class="font-semibold text-text-primary mt-2">Genuine Parts</p>
          </div>
          <div class="glass-card border border-glass-border rounded-xl p-4">
            <span class="material-symbols-outlined text-caribbean-green text-4xl">schedule</span>
            <p class="font-semibold text-text-primary mt-2">Efficient Service</p>
          </div>
        </div>
      </div>

      <!-- Right Column: Text Content -->
      <div class="space-y-8" data-aos="fade-left" data-aos-delay="150">
        <div class="glass-card border border-glass-border rounded-3xl p-8 lg:p-10 shadow-xl transition-all duration-500 hover:border-caribbean-green/40 hover:shadow-[0_0_40px_-10px_rgba(0,223,129,0.3)]">
          <p class="text-lg leading-relaxed text-pistachio">
            <span class="font-semibold text-caribbean-green">Elvis Auto Repair</span> is a leading service hub dedicated to delivering top-tier automotive care. We combine decades of hands-on experience with the latest technology to serve our community with unparalleled commitment and precision.
          </p>
          <p class="mt-6 text-lg leading-relaxed text-pistachio">
            Our <span class="font-semibold text-caribbean-green">mission</span> is to provide reliable, transparent, and high-quality repairs that keep you safe on the road. Our <span class="font-semibold text-caribbean-green">vision</span> is to be the most trusted and technologically advanced auto repair shop in the region.
          </p>
          <p class="mt-6 text-lg leading-relaxed text-pistachio">
            We are pioneering the future of auto service with our intuitive <span class="font-semibold text-caribbean-green">cloud-based Service Management System</span>, allowing for seamless online booking, real-time service tracking, and digital invoicing.
          </p>

          <div class="mt-10 pt-6 border-t border-glass-border">
            <a href="#services" class="inline-block bg-gradient-to-r from-caribbean-green to-mountain-meadow text-rich-black font-bold py-3 px-8 rounded-full shadow-lg hover:scale-105 hover:shadow-[0_0_25px_-5px_rgba(0,223,129,0.4)] transition-all duration-300">
              Explore Our Services
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


  <!-- ---------- MOTIVATION CAROUSEL ---------- -->
<div class="relative w-full overflow-hidden bg-rich-black py-14">
  <!-- Gradient Fade Edges -->
  <div class="pointer-events-none absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-rich-black via-rich-black/80 to-transparent z-10"></div>
  <div class="pointer-events-none absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-rich-black via-rich-black/80 to-transparent z-10"></div>

  <!-- Scrolling Wrapper -->
  <div class="flex w-max animate-marquee">
    <!-- Quotes Set 1 -->
    <div class="flex space-x-16 px-20 text-xl md:text-3xl font-semibold uppercase tracking-wide 
                text-transparent bg-gradient-to-r from-mountain-meadow via-caribbean-green to-bangladesh-green
                bg-clip-text drop-shadow-[0_0_12px_rgba(0,255,170,0.4)]">
      <span>Genuine Nissan Service</span>
      <span>Certified Technicians</span>
      <span>Precision Maintenance</span>
      <span>Customer Satisfaction Guaranteed</span>
      <span>Trusted by Central Luzon</span>
      <span>Drive with Confidence</span>
    </div>

    <!-- Quotes Set 2 -->
    <div class="flex space-x-16 px-20 text-xl md:text-3xl font-semibold uppercase tracking-wide 
                text-transparent bg-gradient-to-r from-mountain-meadow via-caribbean-green to-bangladesh-green
                bg-clip-text drop-shadow-[0_0_12px_rgba(0,255,170,0.4)]">
      <span>Genuine Nissan Service</span>
      <span>Certified Technicians</span>
      <span>Precision Maintenance</span>
      <span>Customer Satisfaction Guaranteed</span>
      <span>Trusted by Central Luzon</span>
      <span>Drive with Confidence</span>
    </div>
  </div>
</div>

<!-- Animation -->
<style>
  @keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  .animate-marquee {
    animation: marquee 30s linear infinite;
  }
</style>

  <!-- Gallery -->
  <section class="py-24 relative overflow-hidden bg-rich-black">
    <!-- Background with Grid + Green Gradient Glow -->
    <div class="absolute inset-0 -z-10">
      <!-- Grid Pattern -->
      <div class="absolute inset-0 h-full w-full 
                bg-rich-black
                bg-[linear-gradient(to_right,rgba(0,223,129,0.1)_1px,transparent_1px),
                    linear-gradient(to_bottom,rgba(0,223,129,0.1)_1px,transparent_1px)] 
                bg-[size:18px_28px]">
      </div>
      <!-- Green Spotlight Gradient -->
      <div class="absolute inset-0 h-full w-full 
                [background:radial-gradient(60%_40%_at_50%_5%,rgba(0,223,129,0.2)_0%,transparent_90%)]">
      </div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
      <!-- Title -->
      <div class="text-center mb-16" data-aos="fade-up">
        <h3 class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-mountain-meadow to-caribbean-green mb-4 main-header">
          Our Workshop in Action
        </h3>
        <p class="text-lg secondary-text max-w-2xl mx-auto">
          See our technicians at work, using cutting-edge tools to keep your car in top condition.
        </p>
      </div>

      <!-- Gallery Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card -->
        <div class="group relative overflow-hidden rounded-2xl shadow-xl border border-glass-border
                  transform transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-caribbean-green/20"
          data-aos="fade-up" data-aos-delay="100">
          <img src="assets/img/Gallery/gallery-1.png" alt="Engine Repair" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110" />
        </div>

        <div class="group relative overflow-hidden rounded-2xl shadow-xl border border-glass-border
                  transform transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-caribbean-green/20"
          data-aos="fade-up" data-aos-delay="200">
          <img src="assets/img/Gallery/gallery-2.png" alt="Oil Change" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110" />
        </div>

        <div class="group relative overflow-hidden rounded-2xl shadow-xl border border-glass-border
                  transform transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-caribbean-green/20"
          data-aos="fade-up" data-aos-delay="300">
          <img src="assets/img/Gallery/gallery-3.png" alt="Brake Service" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110" />
        </div>

        <div class="group relative overflow-hidden rounded-2xl shadow-xl border border-glass-border
                  transform transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-caribbean-green/20"
          data-aos="fade-up" data-aos-delay="400">
          <img src="assets/img/Gallery/gallery-4.png" alt="Tire Replacement" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110" />
        </div>

        <div class="group relative overflow-hidden rounded-2xl shadow-xl border border-glass-border
                  transform transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-caribbean-green/20"
          data-aos="fade-up" data-aos-delay="500">
          <img src="assets/img/Gallery/gallery-5.png" alt="Diagnostics" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110" />
        </div>

        <div class="group relative overflow-hidden rounded-2xl shadow-xl border border-glass-border
                  transform transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-caribbean-green/20"
          data-aos="fade-up" data-aos-delay="600">
          <img src="assets/img/Gallery/gallery-6.png" alt="Customer Service" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110" />
        </div>
      </div>
    </div>
  </section>

<!-- ---------- SERVICES ---------- -->
<section id="services" class="relative py-24 sm:py-32 overflow-hidden bg-rich-black">
  <!-- Background Elements -->
  <div class="absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,223,129,0.07)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,223,129,0.07)_1px,transparent_1px)] bg-[size:30px_30px] [mask-image:radial-gradient(ellipse_at_center,transparent_20%,#0D0F11_100%)]"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-caribbean-green/10 via-transparent to-transparent blur-[160px]"></div>
  </div>

  <div class="container mx-auto px-6 relative z-10">
    <!-- Section Header -->
    <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-caribbean-green to-mountain-meadow bg-clip-text text-transparent mb-4">
        Our Services
      </h2>
      <p class="text-lg sm:text-xl text-pistachio">
        Comprehensive car repair and maintenance, tailored for your vehicle's needs.
      </p>
      <div class="w-24 h-[3px] bg-gradient-to-r from-caribbean-green to-mountain-meadow mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- PHP Data for Services -->
    <?php
        try {
            // Fetch services from the database
            $stmt = $pdo->query("SELECT name, description, price, image_url, category FROM services ORDER BY id");
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Map database columns to the structure your frontend expects
            $services = array_map(function($service) {
                return [
                    'name' => $service['name'],
                    'desc' => $service['description'],
                    'price' => number_format($service['price']),
                    'img' => $service['image_url'] ?: 'assets/img/placeholders/placeholder.png', // Fallback image
                    'category' => $service['category'] ?: 'maintenance', // Fallback category
                ];
            }, $services);
        } catch (PDOException $e) {
            // If database fails, use an empty array to prevent errors
            $services = [];
            error_log("Could not fetch services for index.php: " . $e->getMessage());
        }
    ?>

    <!-- Filter Buttons -->
    <div class="flex justify-center flex-wrap gap-3 mb-12" data-aos="fade-up" data-aos-delay="100">
      <button class="service-filter-btn active" data-filter="all">All</button>
      <button class="service-filter-btn" data-filter="maintenance">Maintenance</button>
      <button class="service-filter-btn" data-filter="repair">Repair</button>
      <button class="service-filter-btn" data-filter="diagnostics">Diagnostics</button>
    </div>

    <!-- Services Grid -->
    <div id="services-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($services as $index => $service) : ?>
        <div class="service-card glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:border-caribbean-green/40 hover:shadow-[0_0_40px_-10px_rgba(0,223,129,0.3)] hover:-translate-y-2 group" data-category="<?php echo $service['category']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3 + 1) * 100; ?>">
          <div class="relative">
            <img alt="<?php echo $service['name']; ?>" class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-105" src="<?php echo $service['img']; ?>" />
            <div class="absolute inset-0 bg-gradient-to-t from-rich-black/70 to-transparent"></div>
            <div class="absolute top-4 right-4 bg-caribbean-green text-rich-black font-bold text-sm px-3 py-1 rounded-full shadow-lg">
              ₱<?php echo $service['price']; ?>
            </div>
            <div class="absolute top-4 left-4 bg-black/50 text-pistachio font-semibold text-xs px-3 py-1 rounded-full backdrop-blur-sm">
              <?php echo ucfirst($service['category']); ?>
            </div>
          </div>
          <div class="p-6 flex flex-col">
            <h3 class="text-xl font-bold text-text-primary"><?php echo $service['name']; ?></h3>
            <p class="mt-2 text-base text-pistachio flex-grow"><?php echo $service['desc']; ?></p>
            <a href="#booking" class="mt-6 w-full text-center bg-white/10 border border-glass-border text-text-primary font-semibold py-3 px-6 rounded-full hover:bg-caribbean-green hover:text-rich-black hover:border-caribbean-green transition-all duration-300">
              Book Now
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Facilities & Equipment -->
    <div class="mt-24 text-center" data-aos="fade-up">
      <h2 class="text-3xl sm:text-4xl font-bold text-text-primary mb-4">Facilities & Equipment</h2>
      <p class="mt-4 max-w-3xl mx-auto text-lg text-pistachio">
        Our workshop is equipped with state-of-the-art tools to provide efficient and accurate service.
      </p>
    </div>

    <div class="mt-16 max-w-5xl mx-auto" data-aos="fade-up" data-aos-delay="100">
      <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8 text-left">
        <li class="flex items-start gap-4">
          <span class="material-symbols-outlined text-caribbean-green text-3xl mt-1">memory</span>
          <div>
            <h4 class="text-lg font-semibold text-text-primary">Advanced Diagnostic Machines</h4>
            <p class="text-pistachio text-sm">Accurate engine, transmission, and electrical system troubleshooting.</p>
          </div>
        </li>
        <li class="flex items-start gap-4">
          <span class="material-symbols-outlined text-caribbean-green text-3xl mt-1">precision_manufacturing</span>
          <div>
            <h4 class="text-lg font-semibold text-text-primary">Hydraulic Lifts & Tools</h4>
            <p class="text-pistachio text-sm">Multi-point hydraulic lifts and factory-calibrated tools for precision and safety.</p>
          </div>
        </li>
        <li class="flex items-start gap-4">
          <span class="material-symbols-outlined text-caribbean-green text-3xl mt-1">verified</span>
          <div>
            <h4 class="text-lg font-semibold text-text-primary">Genuine Parts Inventory</h4>
            <p class="text-pistachio text-sm">We use certified parts to guarantee compatibility, reliability, and performance.</p>
          </div>
        </li>
        <li class="flex items-start gap-4">
          <span class="material-symbols-outlined text-caribbean-green text-3xl mt-1">lounge</span>
          <div>
            <h4 class="text-lg font-semibold text-text-primary">Comfortable Customer Lounge</h4>
            <p class="text-pistachio text-sm">Enjoy a modern waiting area with complimentary beverages and high-speed Wi-Fi.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>


<!-- ---------- TECHNICIANS (Redesigned) ---------- -->
<section id="technicians" class="relative overflow-hidden py-24 sm:py-32 bg-rich-black">
  <!-- Background Elements -->
  <div class="absolute inset-0 -z-10">
    <style>
      .service-filter-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        line-height: 1.25rem;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-border);
        color: var(--pistachio);
        transition: all 0.3s;
      }
      .service-filter-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
      }
      .service-filter-btn.active {
        background-color: var(--caribbean-green);
        color: var(--rich-black);
        border-color: var(--caribbean-green);
      }
    </style>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,223,129,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,223,129,0.06)_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_at_center,transparent_30%,#0D0F11_100%)]"></div>
    <div class="absolute top-0 left-0 w-1/2 h-full bg-gradient-to-r from-caribbean-green/10 via-transparent to-transparent blur-[160px]"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-mountain-meadow/10 via-transparent to-transparent blur-[160px]"></div>
  </div>

  <div class="container mx-auto px-6 relative z-10">
    <!-- Section Heading -->
    <div class="mb-20 text-center" data-aos="fade-up">
      <h2 class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-caribbean-green to-mountain-meadow bg-clip-text text-transparent mb-4">
        Meet Our Technicians
      </h2>
      <p class="mx-auto mt-4 max-w-2xl text-lg sm:text-xl text-pistachio">
        A team of certified professionals dedicated to providing top-tier automotive service with precision and care.
      </p>
      <div class="w-24 h-[3px] bg-gradient-to-r from-caribbean-green to-mountain-meadow mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- Technician Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <!-- Technician Card 1: John Doe -->
      <div class="glass-card border border-glass-border rounded-2xl overflow-hidden transition-all duration-300 hover:border-caribbean-green/40 hover:shadow-[0_0_40px_-10px_rgba(0,223,129,0.3)] hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
        <div class="relative">
          <img src="assets\img\technicians\Technician.jpg" alt="John Doe, Engine Specialist" class="w-full h-64 object-cover object-center">
          <div class="absolute inset-0 bg-gradient-to-t from-rich-black/70 to-transparent"></div>
          <div class="absolute bottom-0 left-0 p-6">
            <h3 class="text-2xl font-bold text-text-primary">John Doe</h3>
            <p class="font-semibold text-caribbean-green">Nissan Master Tech</p>
          </div>
        </div>
        <div class="p-6 space-y-6">
          <div class="grid grid-cols-2 gap-4 text-center">
            <div class="bg-rich-black/50 border border-glass-border rounded-lg p-3">
              <p class="text-sm font-bold text-text-primary">12+ Years</p>
              <p class="text-xs text-pistachio">Experience</p>
            </div>
            <div class="bg-rich-black/50 border border-glass-border rounded-lg p-3">
              <div class="flex items-center justify-center gap-1 text-yellow-400">
                <span class="material-symbols-outlined text-lg">star</span> 4.8 <span class="text-xs text-pistachio ml-1">(48)</span>
              </div>
              <p class="text-xs text-pistachio">Customer Rating</p>
            </div>
          </div>
          <div class="space-y-3 text-pistachio">
            <h4 class="font-semibold text-text-primary border-b border-glass-border pb-2">Specialties</h4>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Engine Diagnostics & Repair</p>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Transmission Services</p>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Performance Tuning</p>
          </div>
          <a href="#booking" class="block w-full text-center bg-gradient-to-r from-caribbean-green to-mountain-meadow text-rich-black font-bold py-3 px-6 rounded-full shadow-lg hover:scale-105 hover:shadow-[0_0_25px_-5px_rgba(0,223,129,0.4)] transition-all duration-300">Book with John</a>
        </div>
      </div>

      <!-- Technician Card 2: Jane Smith -->
      <div class="glass-card border border-glass-border rounded-2xl overflow-hidden transition-all duration-300 hover:border-caribbean-green/40 hover:shadow-[0_0_40px_-10px_rgba(0,223,129,0.3)] hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
        <div class="relative">
          <img src="assets\img\technicians\Technician.jpg" alt="Jane Smith, Electrical Expert" class="w-full h-64 object-cover object-center">
          <div class="absolute inset-0 bg-gradient-to-t from-rich-black/70 to-transparent"></div>
          <div class="absolute bottom-0 left-0 p-6">
            <h3 class="text-2xl font-bold text-text-primary">Jane Smith</h3>
            <p class="font-semibold text-caribbean-green">Electrical & A/C Expert</p>
          </div>
        </div>
        <div class="p-6 space-y-6">
          <div class="grid grid-cols-2 gap-4 text-center">
            <div class="bg-rich-black/50 border border-glass-border rounded-lg p-3">
              <p class="text-sm font-bold text-text-primary">8 Years</p>
              <p class="text-xs text-pistachio">Experience</p>
            </div>
            <div class="bg-rich-black/50 border border-glass-border rounded-lg p-3">
              <div class="flex items-center justify-center gap-1 text-yellow-400">
                <span class="material-symbols-outlined text-lg">star</span> 4.9 <span class="text-xs text-pistachio ml-1">(62)</span>
              </div>
              <p class="text-xs text-pistachio">Customer Rating</p>
            </div>
          </div>
          <div class="space-y-3 text-pistachio">
            <h4 class="font-semibold text-text-primary border-b border-glass-border pb-2">Specialties</h4>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Advanced Electrical Diagnostics</p>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Air Conditioning Systems</p>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Hybrid & EV Systems</p>
          </div>
          <a href="#booking" class="block w-full text-center bg-gradient-to-r from-caribbean-green to-mountain-meadow text-rich-black font-bold py-3 px-6 rounded-full shadow-lg hover:scale-105 hover:shadow-[0_0_25px_-5px_rgba(0,223,129,0.4)] transition-all duration-300">Book with Jane</a>
        </div>
      </div>

      <!-- Technician Card 3: Miguel Reyes -->
      <div class="glass-card border border-glass-border rounded-2xl overflow-hidden transition-all duration-300 hover:border-caribbean-green/40 hover:shadow-[0_0_40px_-10px_rgba(0,223,129,0.3)] hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
        <div class="relative">
          <img src="assets\img\technicians\Technician.jpg" alt="Miguel Reyes, Suspension Specialist" class="w-full h-64 object-cover object-center">
          <div class="absolute inset-0 bg-gradient-to-t from-rich-black/70 to-transparent"></div>
          <div class="absolute bottom-0 left-0 p-6">
            <h3 class="text-2xl font-bold text-text-primary">Miguel Reyes</h3>
            <p class="font-semibold text-caribbean-green">Suspension & Alignment Pro</p>
          </div>
        </div>
        <div class="p-6 space-y-6">
          <div class="grid grid-cols-2 gap-4 text-center">
            <div class="bg-rich-black/50 border border-glass-border rounded-lg p-3">
              <p class="text-sm font-bold text-text-primary">10 Years</p>
              <p class="text-xs text-pistachio">Experience</p>
            </div>
            <div class="bg-rich-black/50 border border-glass-border rounded-lg p-3">
              <div class="flex items-center justify-center gap-1 text-yellow-400">
                <span class="material-symbols-outlined text-lg">star</span> 4.7 <span class="text-xs text-pistachio ml-1">(39)</span>
              </div>
              <p class="text-xs text-pistachio">Customer Rating</p>
            </div>
          </div>
          <div class="space-y-3 text-pistachio">
            <h4 class="font-semibold text-text-primary border-b border-glass-border pb-2">Specialties</h4>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Brake Systems & ABS</p>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Wheel Alignment & Balancing</p>
            <p class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-caribbean-green text-base">check_circle</span>Suspension Tuning</p>
          </div>
          <a href="#booking" class="block w-full text-center bg-gradient-to-r from-caribbean-green to-mountain-meadow text-rich-black font-bold py-3 px-6 rounded-full shadow-lg hover:scale-105 hover:shadow-[0_0_25px_-5px_rgba(0,223,129,0.4)] transition-all duration-300">Book with Miguel</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ---------- PLANS & PRICING (Redesigned) ---------- -->
<section id="membership" class="relative py-24 sm:py-32 overflow-hidden bg-rich-black">
  <!-- Background Elements -->
  <div class="absolute inset-0 -z-10">
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-gradient-to-tr from-caribbean-green/20 to-transparent rounded-full blur-[160px] animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-gradient-to-bl from-mountain-meadow/10 to-transparent rounded-full blur-[180px] animate-pulse [animation-delay:2s]"></div>
  </div>

  <div class="container mx-auto px-6 relative z-10">
    <!-- Section Header -->
    <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-caribbean-green to-mountain-meadow bg-clip-text text-transparent mb-4">
        Service Plans
      </h2>
      <p class="text-lg sm:text-xl text-pistachio">
        Choose the perfect plan to keep your vehicle in peak condition.
      </p>
      <div class="w-24 h-[3px] bg-gradient-to-r from-caribbean-green to-mountain-meadow mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- Toggle Switch -->
    <div class="flex justify-center items-center gap-4 mb-12" data-aos="fade-up" data-aos-delay="100">
      <span class="font-semibold text-pistachio" id="plan-type-service">Pay per Service</span>
      <label for="plan-toggle" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="plan-toggle" class="sr-only peer">
        <div class="w-14 h-8 bg-black/50 border border-glass-border rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-caribbean-green"></div>
      </label>
      <span class="font-semibold text-pistachio" id="plan-type-annual">Annual Plans</span>
    </div>

    <!-- Pricing Cards Grid -->
    <?php
    $plans = [
        [
            'id' => 'basic-care', 'name' => 'Basic Care', 'description' => 'Essential maintenance for optimal performance.',
            'price_service' => '1,500', 'price_annual' => '5,500', 'is_popular' => false,
            'features' => ['Standard Oil Change', 'Fluid Top-ups', 'Tire Pressure Check', '21-Point Inspection']
        ],
        [
            'id' => 'comprehensive', 'name' => 'Comprehensive', 'description' => 'Complete peace of mind with extensive checks and priority service.',
            'price_service' => '4,500', 'price_annual' => '15,000', 'is_popular' => true,
            'features' => ['Everything in Basic, plus:', 'Full Synthetic Oil Change', 'Brake & Suspension Check', 'Tire Rotation & Balancing', 'Computer Diagnostic Scan', 'Priority Service Lane']
        ],
        [
            'id' => 'ultimate', 'name' => 'Ultimate', 'description' => 'The all-inclusive package for total vehicle care.',
            'price_service' => 'Custom', 'price_annual' => '25,000', 'is_popular' => false,
            'features' => ['Everything in Comprehensive, plus:', 'Full Interior & Exterior Detailing', 'A/C System Cleaning', 'Free Towing Service (1x/year)', 'Exclusive Member Discounts']
        ],
        [
            'id' => 'walk-in', 'name' => 'Walk-In Service', 'description' => 'Pay per visit, flexible and convenient for quick checks.',
            'price_service' => '500', 'price_annual' => 'N/A', 'is_popular' => false,
            'features' => ['Basic Diagnostics', 'Minor Repairs Consultation']
        ]
    ];
    ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

      <!-- Plan 1: Basic -->
      <?php foreach ($plans as $index => $plan) : ?>
      <?php if ($plan['id'] !== 'walk-in') : // Exclude walk-in from main pricing cards ?>
      <div class="pricing-card glass-card p-8 rounded-2xl flex flex-col border border-glass-border transition-all duration-300 hover:border-caribbean-green/40 hover:shadow-[0_0_40px_-10px_rgba(0,223,129,0.2)] hover:-translate-y-2
        <?php echo $plan['is_popular'] ? 'bg-dark-green/50 border-2 border-caribbean-green relative shadow-[0_0_60px_-15px_rgba(0,223,129,0.4)] transform lg:scale-105 animate-pulse-glow' : ''; ?>"
        data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
        <?php if ($plan['is_popular']) : ?>
        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-caribbean-green to-mountain-meadow text-rich-black text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Most Popular</div>
        <?php endif; ?>
        <h3 class="text-2xl font-bold text-text-primary mb-2"><?php echo $plan['name']; ?></h3>
        <p class="text-pistachio mb-6 flex-grow"><?php echo $plan['description']; ?></p>
        <div class="mb-6">
          <span class="text-5xl font-extrabold text-text-primary" data-price-service="<?php echo $plan['price_service']; ?>" data-price-annual="<?php echo $plan['price_annual']; ?>">
            <?php echo $plan['price_service'] === 'Custom' ? 'Custom' : '₱' . $plan['price_service']; ?>
          </span>
          <span class="text-sm text-pistachio ml-1" data-period-service="/service" data-period-annual="/year">
            <?php echo $plan['price_service'] === 'Custom' ? '/quote' : '/service'; ?>
          </span>
        </div>
        <a href="#booking" class="w-full text-center
          <?php echo $plan['is_popular'] ? 'bg-gradient-to-r from-caribbean-green to-mountain-meadow text-rich-black font-bold shadow-lg hover:shadow-[0_0_25px_-5px_rgba(0,223,129,0.4)]' : 'bg-white/10 border border-glass-border text-text-primary font-semibold hover:bg-caribbean-green hover:text-rich-black hover:border-caribbean-green'; ?>
          py-3 px-6 rounded-full hover:scale-105 transition-all duration-300">
          <?php echo $plan['id'] === 'ultimate' ? 'Contact for Quote' : 'Select Plan'; ?>
        </a>
        <ul class="space-y-4 text-pistachio mt-8 text-sm">
          <?php foreach ($plan['features'] as $feature) : ?>
          <li class="flex items-center gap-3">
            <?php if (!str_contains($feature, 'Everything in')) : ?>
            <span class="material-symbols-outlined text-caribbean-green">check_circle</span>
            <?php endif; ?>
            <?php echo $feature; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>

    </div>
  </section>

  <!-- Service with Technician -->
  <div class="mt-20 max-w-5xl mx-auto px-6" data-aos="fade-up"></div>

  <!-- Style for Pulse Glow Animation -->
  <style>
    @keyframes pulse-glow {
      0%, 100% {
        border-color: var(--caribbean-green);
        box-shadow: 0 0 60px -15px rgba(0, 223, 129, 0.4);
      }
      50% {
        border-color: var(--mountain-meadow);
        box-shadow: 0 0 70px -10px rgba(34, 204, 149, 0.6);
      }
    }
    .animate-pulse-glow {
      animation: pulse-glow 3s ease-in-out infinite;
    }
  </style>

  <!-- Smooth Scroll Script -->
  <script>
    document.querySelectorAll('a.scroll-to-booking').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('#booking').scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  </script>

  <!-- FAQs -->
  <div class="mt-24 max-w-4xl mx-auto" data-aos="fade-up">
    <h2 class="text-3xl sm:text-4xl font-bold text-text-primary text-center mb-12 main-header">Frequently Asked Questions</h2>
    <div class="space-y-4" id="faq-container">

      <!-- FAQ Item -->
      <div class="faq-item glass-card rounded-lg overflow-hidden transition-all duration-500">
        <button class="faq-toggle w-full flex justify-between items-center p-6 focus:outline-none" aria-expanded="false">
          <span class="font-semibold text-lg text-text-primary">How often should I service my car?</span>
          <svg class="faq-icon w-6 h-6 text-caribbean-green transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </button>
        <div class="faq-answer max-h-0 opacity-0 transform translate-y-2 overflow-hidden transition-all duration-500 ease-in-out px-6 secondary-text">
          <p class="py-4">We recommend servicing your car every 6 months or 10,000 kilometers, whichever comes first, to maintain optimal performance.</p>
        </div>
      </div>

      <!-- FAQ Item -->
      <div class="faq-item glass-card rounded-lg overflow-hidden transition-all duration-500">
        <button class="faq-toggle w-full flex justify-between items-center p-6 focus:outline-none" aria-expanded="false">
          <span class="font-semibold text-lg text-text-primary">Do you offer warranty on repairs?</span>
          <svg class="faq-icon w-6 h-6 text-caribbean-green transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </button>
        <div class="faq-answer max-h-0 opacity-0 transform translate-y-2 overflow-hidden transition-all duration-500 ease-in-out px-6 secondary-text">
          <p class="py-4">Yes, we provide a 6-month warranty on all repairs and parts replaced at our workshop.</p>
        </div>
      </div>

      <!-- FAQ Item -->
      <div class="faq-item glass-card rounded-lg overflow-hidden transition-all duration-500">
        <button class="faq-toggle w-full flex justify-between items-center p-6 focus:outline-none" aria-expanded="false">
          <span class="font-semibold text-lg text-text-primary">What payment methods do you accept?</span>
          <svg class="faq-icon w-6 h-6 text-caribbean-green transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </button>
        <div class="faq-answer max-h-0 opacity-0 transform translate-y-2 overflow-hidden transition-all duration-500 ease-in-out px-6 secondary-text">
          <p class="py-4">We accept cash, credit/debit cards, and GCash for convenient and secure payments.</p>
        </div>
      </div>

    </div>
  </div>

  <!-- Glow Animation -->
  <style>
    @keyframes glow {

      0%,
      100% {
        box-shadow: 0 0 10px rgba(0, 223, 129, 0.5);
      }

      50% {
        box-shadow: 0 0 20px rgba(0, 223, 129, 0.9);
      }
    }

    .glow-active {
      animation: glow 1.5s ease-in-out infinite;
    }
  </style>

  <!-- FAQ Script -->
  <script>
    document.querySelectorAll('.faq-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const answer = button.nextElementSibling;
        const icon = button.querySelector('.faq-icon');
        const parent = button.closest(".faq-item");
        const isOpen = button.getAttribute("aria-expanded") === "true";

        // Close all
        document.querySelectorAll('.faq-answer').forEach(a => {
          a.style.maxHeight = null;
          a.style.opacity = 0;
          a.style.transform = "translateY(0.5rem)";
        });
        document.querySelectorAll('.faq-toggle').forEach(b => b.setAttribute("aria-expanded", "false"));
        document.querySelectorAll('.faq-icon').forEach(i => i.classList.remove("rotate-45"));
        document.querySelectorAll('.faq-item').forEach(item => item.classList.remove("ring-2", "ring-caribbean-green/80", "bg-dark-green/50", "glow-active"));

        // Open clicked if not already open
        if (!isOpen) {
          button.setAttribute("aria-expanded", "true");
          answer.style.maxHeight = answer.scrollHeight + "px";
          answer.style.opacity = 1;
          answer.style.transform = "translateY(0)";
          icon.classList.add("rotate-45");
          parent.classList.add("ring-2", "ring-caribbean-green/80", "bg-dark-green/50", "glow-active");
        }
      });
    });
  </script>

  <!-- Booking and Contact -->
<section id="booking" class="relative py-24 sm:py-32 overflow-hidden bg-rich-black">
  <div class="absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,223,129,0.07)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,223,129,0.07)_1px,transparent_1px)] bg-[size:30px_30px] [mask-image:radial-gradient(ellipse_at_center,transparent_20%,#0D0F11_100%)]"></div>
    <div class="absolute bottom-0 left-0 w-1/2 h-full bg-gradient-to-r from-caribbean-green/10 via-transparent to-transparent blur-[160px]"></div>
  </div>

  <div class="container mx-auto px-6 relative z-10">
    <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-caribbean-green to-mountain-meadow bg-clip-text text-transparent mb-4">Get In Touch</h2>
      <p class="text-lg sm:text-xl text-pistachio">Ready for top-tier service? Book an appointment or send us a message.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
      <!-- Left Column: Booking Form -->
      <div class="space-y-8 glass-card p-8 rounded-2xl border border-glass-border shadow-lg" data-aos="fade-right" id="booking-form-container">
        <div class="flex items-center gap-4">
          <span class="material-symbols-outlined text-caribbean-green text-4xl">calendar_month</span>
          <div>
            <h3 class="text-2xl font-bold text-text-primary">Book an Appointment</h3>
            <p class="text-pistachio">Schedule your service with our expert technicians.</p>
          </div>
        </div>
        <?php
            if (isset($_SESSION['booking_success'])) {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Booking Successful!',
                                text: '" . addslashes(htmlspecialchars($_SESSION['booking_success'])) . "',
                                background: '#0D0F11',
                                color: '#F7F7F6',
                                confirmButtonColor: '#00DF81',
                                confirmButtonText: 'Awesome!'
                            });
                        });
                      </script>";
                unset($_SESSION['booking_success']);
            }
            if (isset($_SESSION['booking_error'])) {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Booking Failed',
                                text: '" . addslashes(htmlspecialchars($_SESSION['booking_error'])) . "',
                                background: '#0D0F11',
                                color: '#F7F7F6',
                                confirmButtonColor: '#00DF81'
                            });
                        });
                      </script>";
                unset($_SESSION['booking_error']);
            }
        ?>
        <form class="space-y-6" id="booking-form-element" action="Backend/booking-handler.php" method="POST" novalidate>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="relative">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">person</span>
              <input class="form-input w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" id="name" name="name" placeholder="Full Name" required type="text" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" />
            </div>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">email</span>
              <input class="form-input w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="email" name="email" placeholder="Email Address" required type="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" />
            </div>
          </div>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">phone</span>
            <input class="form-input w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" placeholder="Phone number" name="phone" type="tel" />
          </div>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">engineering</span>
            <select class="form-select w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 secondary-text focus:ring-2 focus:ring-caribbean-green focus:border-transparent" name="technician_id" id="technician_id" required>
              <option value="" disabled selected>Select Technician</option>
              <?php
                $tech_sql = "SELECT id, name FROM technicians ORDER BY name ASC";
                $tech_stmt = $pdo->query($tech_sql);
                while ($tech = $tech_stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($tech['id']) . '">' . htmlspecialchars($tech['name']) . '</option>';
                }
              ?>
            </select>
          </div>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">directions_car</span>
            <input class="form-input w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" placeholder="Enter Vehicle (e.g., 2021 Toyota Fortuner)" name="vehicle_name" id="vehicle_name" required type="text" />
          </div>
          <input type="hidden" name="amount" id="amount-hidden" value="">
          <input type="hidden" name="package_name" id="package-name-hidden" value="">
          <input type="hidden" name="payment_reference" id="payment-reference-hidden" value="">
          <input type="hidden" name="gcash_name" id="gcash-name-hidden" value="">
          <div class="bg-black/50 rounded-lg border border-gray-700 p-4 shadow-inner">
            <div class="flex items-center justify-between mb-4">
              <button class="p-2 rounded-full hover:bg-gray-700 transition-colors btn-hover" type="button" id="prev-month">
                <span class="material-symbols-outlined text-white">chevron_left</span>
              </button>
              <div class="text-lg font-bold text-text-primary" id="month-year"></div>
              <button class="p-2 rounded-full hover:bg-gray-700 transition-colors btn-hover" type="button" id="next-month">
                <span class="material-symbols-outlined text-white">chevron_right</span>
              </button>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center secondary-text text-sm font-medium mb-2">
              <div>Sun</div>
              <div>Mon</div>
              <div>Tue</div>
              <div>Wed</div>
              <div>Thu</div>
              <div>Fri</div>
              <div>Sat</div>
            </div>
            <div class="grid grid-cols-7 gap-2 text-center" id="calendar-days"></div>
            <input type="hidden" name="date" id="selected-date-input" required>
          </div>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">schedule</span>
            <select class="form-select w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 secondary-text focus:ring-2 focus:ring-caribbean-green focus:border-transparent glass-dark" id="time" name="time" required>
              <option disabled value="">Select Time</option>
            </select>
          </div>
          <div class="relative">
            <span class="absolute left-4 top-4 material-symbols-outlined text-caribbean-green">notes</span>
            <textarea class="form-textarea w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" placeholder="Additional Notes (e.g., car issues, preferred time)" name="notes" rows="4"></textarea>
          </div>
          <button id="booking-submit-btn" class="w-full rounded-lg glow-button px-6 py-4 text-lg font-bold tracking-wide transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-dark focus:ring-caribbean-green btn-hover" type="button">
            Proceed to Payment
          </button>
        </form>
      </div>

      <!-- Right Column: Contact & Location -->
      <div class="space-y-8 lg:space-y-12" data-aos="fade-left" data-aos-delay="100">
        <!-- Contact Form -->
        <div id="contact" class="space-y-8 glass-card p-8 rounded-2xl border border-glass-border shadow-lg">
          <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-caribbean-green text-4xl">mail</span>
            <div>
              <h3 class="text-2xl font-bold text-text-primary">Contact Us</h3>
              <p class="text-pistachio">Have questions? Send us a message.</p>
            </div>
          </div>
          <?php
              if (isset($_SESSION['contact_success'])) {
                  echo "<div class='p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800' role='alert'>
                          <span class='font-medium'>Success!</span> " . htmlspecialchars($_SESSION['contact_success']) . "
                        </div>";
                  unset($_SESSION['contact_success']);
              }
              if (isset($_SESSION['contact_error'])) {
                  echo "<div class='p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800' role='alert'>
                          <span class='font-medium'>Error!</span> " . htmlspecialchars($_SESSION['contact_error']) . "
                        </div>";
                  unset($_SESSION['contact_error']);
              }
          ?>
          <form class="space-y-6" action="Backend/contact-handler.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">person</span>
              <input class="form-input w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" placeholder="Your Name" required type="text" name="name" />
            </div>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-caribbean-green">email</span>
              <input class="form-input w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" placeholder="Your Email" required type="email" name="email" />
            </div>
            <div class="relative">
              <span class="absolute left-4 top-4 material-symbols-outlined text-caribbean-green">notes</span>
              <textarea class="form-textarea w-full pl-12 rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent" placeholder="Your Message" required rows="5" name="message"></textarea>
            </div>
            <button class="w-full rounded-lg glow-button px-6 py-3 text-base font-bold tracking-wide transition-all duration-300 hover:scale-105" type="submit">Send Message</button>
          </form>
        </div>

        <!-- Location & Details -->
        <div class="space-y-8 glass-card p-8 rounded-2xl border border-glass-border shadow-lg">
          <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-caribbean-green text-4xl">location_on</span>
            <div>
              <h3 class="text-2xl font-bold text-text-primary">Our Location</h3>
              <p class="text-pistachio">Bunsuran 1st, Pandi, Bulacan, Philippines</p>
            </div>
          </div>
          <a href="https://www.google.com/maps/search/?api=1&query=14.863831,120.943946" target="_blank" rel="noopener noreferrer" class="group relative block">
            <div class="h-80 w-full rounded-xl overflow-hidden shadow-lg border border-glass-border" id="map">
               <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
          <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
          <script>
    var map = L.map('map').setView([14.86015, 120.86982], 16); // Centered on Bunsuran 1st, Pandi, Bulacan
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([14.86015, 120.86982]).addTo(map)
        .bindPopup('<strong>Elvis Auto Repair</strong><br>Bunsuran 1st, Pandi, Bulacan, Philippines')
        .openPopup();
</script>
            </div>
            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl">
              <span class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 text-black font-bold"><span class="material-symbols-outlined">open_in_new</span> View on Google Maps</span>
            </div>
          </a>
          <div class="space-y-4 pt-4 border-t border-glass-border">
            <a class="flex items-center gap-4 text-pistachio hover:text-caribbean-green transition-colors" href="mailto:almoiteelvis7@gmail.com">
              <span class="material-symbols-outlined text-2xl text-caribbean-green">email</span>
              <span>almoiteelvis7@gmail.com</span>
            </a>
            <a class="flex items-center gap-4 text-pistachio hover:text-caribbean-green transition-colors" href="tel:+639123456789">
              <span class="material-symbols-outlined text-2xl text-caribbean-green">call</span>
              <span>+63 912 345 6789</span>
            </a>
            <a class="flex items-center gap-4 text-pistachio hover:text-caribbean-green transition-colors" href="https://www.facebook.com/profile.php?id=100064267264520" rel="noopener noreferrer" target="_blank">
              <svg class="w-7 h-7 text-caribbean-green" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v7.045C18.343 21.128 22 16.991 22 12z"></path>
              </svg>
              <span>facebook</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Hidden Confirmation & Payment Modals (no change in structure, just moved) -->
    <div class="hidden space-y-8 glass-card p-8 rounded-xl border border-glass-border shadow-lg backdrop-blur-lg" data-aos="fade-up" id="confirmation-container">
      <div class="text-center space-y-6">
        <div class="flex justify-center">
          <div class="bg-green-500/20 p-4 rounded-full animate-bounce">
            <span class="material-symbols-outlined text-green-400 text-5xl">check_circle</span>
          </div>
        </div>
        <h2 class="text-4xl font-bold text-text-primary">Booking Confirmed!</h2>
        <p class="text-lg secondary-text">Thank you, <span class="font-semibold text-text-primary" id="conf-name"></span>! Your service has been successfully booked. A confirmation email has been sent to <span class="font-semibold text-text-primary" id="conf-email"></span>.</p>
      </div>
      <div class="border-t border-gray-700 my-6"></div>
      <div class="space-y-6 text-lg">
        <h3 class="text-2xl font-bold text-text-primary"><i class="fa-solid fa-calendar-check mr-2"></i>Booking Summary</h3>
        <div class="space-y-4">
          <div class="flex justify-between">
            <span class="text-gray-400">Service:</span>
            <span class="font-medium text-white" id="conf-service"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Date:</span>
            <span class="font-medium text-white" id="conf-date"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Time:</span>
            <span class="font-medium text-white">To be confirmed</span>
          </div>
        </div>
      </div>
      <div class="pt-6">
        <button class="w-full rounded-lg glow-button px-1 py-1 text-lg font-bold tracking-wide transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-dark focus:ring-caribbean-green btn-hover" id="new-booking-btn">Make Another Booking</button>
      </div>
    </div>
    <div id="payment-modal" class="fixed inset-0 z-50 items-center justify-center bg-rich-black/70 backdrop-blur-sm p-4 hidden">
      <div class="glass-card w-full max-w-4xl rounded-2xl p-8 border shadow-2xl shadow-black/50 relative">
        <button id="close-payment-modal" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10">
          <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="text-2xl font-bold text-white mb-6">Complete Your Booking</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
          <div class="md:col-span-3">
            <div class="space-y-4">
              <h4 class="text-lg font-semibold text-text-primary mb-3">1. Select Package</h4>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="package-selector-modal">
                <?php
                  $services_sql = "SELECT id, name, price FROM services WHERE price > 0 ORDER BY price ASC";
                  $services_stmt = $pdo->query($services_sql);
                  $first = true;
                  while ($service = $services_stmt->fetch(PDO::FETCH_ASSOC)) {
                      $is_checked = $first ? 'checked' : '';
                      $active_class = $first ? 'border-caribbean-green bg-caribbean-green/10' : 'border-glass-border bg-black/30 hover:border-stone-600';
                      echo '
                    <label class="cursor-pointer p-4 rounded-lg border-2 text-center transition-colors ' . $active_class . '"
                      data-price="' . htmlspecialchars($service['price']) . '"
                      data-package-name="' . htmlspecialchars($service['name']) . '">
                      <input type="radio" name="package_selection" value="' . htmlspecialchars($service['id']) . '" class="hidden" ' . $is_checked . ' />
                      <p class="font-semibold text-text-primary text-sm">' . htmlspecialchars($service['name']) . '</p>
                      <p class="text-base font-bold text-caribbean-green mt-1">₱' . number_format($service['price'], 2) . '</p>
                    </label>
                  '; $first = false; } ?>
              </div>
              <div class="pt-4">
                <h4 class="text-lg font-semibold text-text-primary mb-3">2. Payment Details</h4>
                <div>
                  <label for="modal-payment-ref" class="block text-sm font-medium text-gray-400 mb-2">GCash Reference Number</label>
                  <input id="modal-payment-ref" type="text" name="payment_reference" required placeholder="Enter GCash reference number" class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent">
                </div>
                <div class="mt-4">
                  <label for="modal-gcash-name" class="block text-sm font-medium text-gray-400 mb-2">GCash Account Name</label>
                  <input id="modal-gcash-name" type="text" name="gcash_name" required placeholder="Enter GCash account name" class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-4 placeholder-gray-500 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent">
                </div>
              </div>
            </div>
          </div>
          <div class="md:col-span-2 bg-stone-900/50 rounded-xl p-6 flex flex-col">
            <h4 class="text-lg font-semibold text-text-primary mb-4">Payment Instructions</h4>
            <div class="text-center bg-stone-800/50 p-4 rounded-lg">
              <p class="text-sm text-stone-300 mb-2">Scan to pay with GCash</p>
              <img src="img/gcash-qr.jpg" alt="GCash QR Code" class="w-40 h-40 mx-auto rounded-lg mb-2 border-2 border-stone-700" />
              <p class="text-sm text-stone-400">or send to <strong class="text-text-primary">0917-123-4567</strong></p>
              <p class="text-xs text-stone-500 mt-3">Enter the reference number and your GCash name to confirm.</p>
            </div>
            <div class="mt-auto pt-6 border-t border-stone-700">
              <button id="confirm-and-pay-btn" class="w-full rounded-lg glow-button px-6 py-4 text-lg font-bold tracking-wide transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-dark focus:ring-caribbean-green btn-hover">Confirm & Pay</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Map initialization needs to be inside the DOMContentLoaded listener
      // or called after the #map element is in the DOM.
      document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('map')) {
          var map = L.map('map').setView([14.863831, 120.943946], 15);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
          }).addTo(map);
          L.marker([14.863831, 120.943946]).addTo(map).bindPopup('Elvis Auto Repair').openPopup();
        }
      });
    </script>
  </div>
</section>

  <!-- ---------- FOOTER ---------- -->
<footer class="relative bg-gradient-to-b from-rich-black via-dark-green to-black text-gray-300 pt-24 pb-8 overflow-hidden">
  <!-- Gradient Glow Background -->
  <div class="absolute inset-0 -z-10">
    <div class="absolute -top-32 -left-24 w-[28rem] h-[28rem] rounded-full 
                bg-gradient-to-r from-caribbean-green/30 to-mountain-meadow/20 opacity-40 blur-[160px]"></div>
    <div class="absolute bottom-0 right-0 w-[24rem] h-[24rem] rounded-full 
                bg-gradient-to-r from-bangladesh-green/20 to-dark-green/30 opacity-30 blur-[140px]"></div>
  </div>

  <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12" data-aos="fade-up">
    <!-- Brand -->
    <div data-aos="fade-up" data-aos-delay="100">
      <h3 class="text-2xl font-bold text-anti-flash-white mb-4">
        Nissan <span class="text-caribbean-green">Baliuag</span>
      </h3>
      <p class="text-gray-400 mb-6">
        Authorized Nissan dealership and service center in Bulacan — delivering factory-standard repairs, genuine parts, and certified expertise.
      </p>
      <div class="flex gap-4">
        <a href="https://www.facebook.com/nissanbaliuag" 
           class="hover:text-caribbean-green transition transform hover:scale-110">
          <i class="fab fa-facebook text-2xl"></i>
        </a>
      </div>
    </div>

    <!-- Quick Links -->
    <div data-aos="fade-up" data-aos-delay="200">
      <h4 class="text-white font-semibold text-lg mb-4">Quick Links</h4>
      <ul class="space-y-3">
        <li><a href="#home" class="hover:text-caribbean-green transition-colors">Home</a></li>
        <li><a href="#about" class="hover:text-caribbean-green transition-colors">About Us</a></li>
        <li><a href="#services" class="hover:text-caribbean-green transition-colors">Services</a></li>
        <li><a href="#membership" class="hover:text-caribbean-green transition-colors">Plans</a></li>
        <li><a href="#booking" class="hover:text-caribbean-green transition-colors">Booking</a></li>
        <li><a href="#contact" class="hover:text-caribbean-green transition-colors">Contact</a></li>
      </ul>
    </div>

    <!-- Support -->
    <div data-aos="fade-up" data-aos-delay="300">
      <h4 class="text-white font-semibold text-lg mb-4">Support</h4>
      <ul class="space-y-3">
        <li><a href="#faq-container" class="hover:text-caribbean-green transition-colors">FAQs</a></li>
        <li><a href="#" class="hover:text-caribbean-green transition-colors">Privacy Policy</a></li>
        <li><a href="#" class="hover:text-caribbean-green transition-colors">Terms of Service</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div data-aos="fade-up" data-aos-delay="400">
      <h4 class="text-white font-semibold text-lg mb-4">Get in Touch</h4>
      <p class="mb-2 flex items-center">
        <i class="fas fa-map-marker-alt mr-2 text-caribbean-green"></i>
        Bunsuran 1st, Pandi, Bulacan, Philippines
      </p>
      <p class="mb-2 flex items-center">
        <i class="fas fa-phone mr-2 text-caribbean-green"></i> +63 912 345 6789
      </p>
      <p class="flex items-center">
        <i class="fas fa-envelope mr-2 text-caribbean-green"></i> service@nissanbaliuag.com
      </p>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="border-t border-gray-700 mt-12 pt-6 text-center text-sm text-gray-500" data-aos="fade-up">
    © 2025 Nissan Baliuag — All rights reserved.  
    <span class="block text-caribbean-green/80 mt-1">Driven by Excellence | Powered by Innovation</span>
  </div>
</footer>


  <!-- ---------- CHATBOT ---------- -->
  <div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Window -->
    <div id="chat-window" class="hidden w-80 h-[28rem] bg-rich-black/80 backdrop-blur-xl border border-glass-border rounded-2xl shadow-2xl shadow-black/50 flex flex-col transition-all duration-300 ease-in-out transform origin-bottom-right scale-95 opacity-0 -translate-y-4 mb-4">
      <!-- Header -->
      <div class="flex items-center justify-between p-3 border-b border-glass-border flex-shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-dark-green flex items-center justify-center border border-glass-border">
            <span class="material-symbols-outlined text-caribbean-green animate-pulse">smart_toy</span>
          </div>
          <h3 class="font-bold text-text-primary text-base">Auto Assistant</h3>
        </div>
        <button id="close-chat-btn" class="p-1 rounded-full hover:bg-white/10 transition-colors">
          <span class="material-symbols-outlined text-pistachio">close</span>
        </button>
      </div>
      <!-- Messages -->
      <div id="chat-messages" class="flex-1 p-4 space-y-5 overflow-y-auto min-h-0">
        <!-- Messages will be injected here by JS -->
      </div>
      <!-- Suggestions -->
      <div id="chat-suggestions" class="p-3 border-t border-glass-border flex flex-wrap gap-2 justify-center">
        <!-- Suggestion prompts will be injected here by JS -->
      </div>
    </div>

    <!-- Chatbot Toggle Button -->
    <button id="chatbot-toggle" class="w-16 h-16 flex items-center justify-center rounded-full bg-gradient-to-r from-mountain-meadow to-caribbean-green text-rich-black shadow-lg hover:scale-110 transition-all duration-300 animate-pulse-glow relative btn-hover">
      <span id="chat-icon-open" class="material-symbols-outlined text-3xl transition-transform duration-300">smart_toy</span>
      <span id="chat-icon-close" class="material-symbols-outlined text-3xl absolute transition-transform duration-300 scale-0">close</span>
    </button>
  </div>

  <style>
    @keyframes bubble-in {
      0% { opacity: 0; transform: translateY(10px) scale(0.95); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .bot-message {
      background-color: rgba(0, 223, 129, 0.1);
      border: 1px solid rgba(0, 223, 129, 0.2);
      color: var(--pistachio);
      padding: 0.75rem;
      border-radius: 1rem 1rem 1rem 0.25rem;
      max-width: 85%;
      align-self: flex-start;
      animation: bubble-in 0.4s ease-out forwards;
      position: relative;
    }
    .bot-message::after {
      content: '';
      position: absolute;
      left: -8px;
      bottom: 0;
      width: 0;
      height: 0;
      border-bottom: 12px solid rgba(0, 223, 129, 0.1);
      border-left: 12px solid transparent;
    }
    .user-message {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid var(--glass-border);
      color: var(--text-primary);
      padding: 0.75rem;
      border-radius: 1rem 1rem 0.25rem 1rem;
      max-width: 85%;
      align-self: flex-end;
      animation: bubble-in 0.4s ease-out forwards;
    }
    .suggestion-chip {
      background-color: transparent;
      border: 1px solid var(--glass-border);
      color: var(--pistachio);
      padding: 0.4rem 0.9rem;
      border-radius: 9999px;
      cursor: pointer;
      transition: all 0.2s ease-in-out;
    }
    .suggestion-chip:hover {
      background-color: var(--caribbean-green);
      color: var(--rich-black);
      border-color: var(--caribbean-green);
    }
  </style>
  <style>
    .thinking-dots span {
      animation: blink 1.4s infinite both;
    }
    .thinking-dots span:nth-child(2) { animation-delay: 0.2s; }
    .thinking-dots span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes blink {
      0% { opacity: 0.2; }
      20% { opacity: 1; }
      100% { opacity: 0.2; }
    }
  </style>

  <!-- Back to Top Button -->
  <div class="group fixed bottom-6 right-24 z-50">
    <button id="backToTop" class="opacity-0 pointer-events-none w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-r from-mountain-meadow to-caribbean-green text-rich-black shadow-lg hover:scale-110 transition-all duration-500 animate-pulse-glow relative btn-hover">
      <i class="fas fa-arrow-up"></i>
      <span class="absolute -left-20 top-1/2 -translate-y-1/2 opacity-0 translate-x-2 group-hover:translate-x-0 group-hover:opacity-100 bg-gradient-to-r from-mountain-meadow to-caribbean-green text-rich-black text-xs rounded px-2 py-1 whitespace-nowrap transition-all duration-300 shadow-lg tooltip-glow">Back to Top</span>
    </button>
  </div>

  <script>
    // Consolidated Scripts
    // ---------- CHATBOT SCRIPT ----------
    document.addEventListener("DOMContentLoaded", () => {
      const chatbotToggle = document.getElementById('chatbot-toggle');
      const chatWindow = document.getElementById('chat-window');
      const closeChatBtn = document.getElementById('close-chat-btn');
      const chatMessages = document.getElementById('chat-messages');
      const chatSuggestions = document.getElementById('chat-suggestions');
      const chatIconOpen = document.getElementById('chat-icon-open');
      const chatIconClose = document.getElementById('chat-icon-close');

      let userName = localStorage.getItem('chatbotUserName') || '';
      let chatState = userName ? 'main' : 'greeting'; // Check for name on load

      const qaPairs = {
        "How do I book?": "You can book an appointment by scrolling to the <a href='#booking' class='text-caribbean-green underline'>Booking section</a>, filling out the form, and completing the payment. Our system will guide you through selecting a technician, date, and time.",
        "What are your hours?": "Our workshop is open from 8:00 AM to 5:00 PM, Monday to Saturday. We are closed on Sundays.",
        "Where are you located?": "We are located at Bunsuran 1st, Pandi, Bulacan. You can find an interactive map in the <a href='#contact' class='text-caribbean-green underline'>Contact section</a>.",
        "How can I contact you?": "You can call us at <a href='tel:+639123456789' class='text-caribbean-green underline'>+63 912 345 6789</a>, email us at <a href='mailto:almoiteelvis7@gmail.com' class='text-caribbean-green underline'>almoiteelvis7@gmail.com</a>, or use the contact form on our website."
      };

      const initialSuggestions = Object.keys(qaPairs);

      const toggleChatbot = (forceClose = false) => {
        const isHidden = chatWindow.classList.contains('hidden');
        if (forceClose || !isHidden) {
          chatWindow.classList.add('scale-95', 'opacity-0', '-translate-y-4');
          setTimeout(() => chatWindow.classList.add('hidden'), 300);
          chatIconOpen.classList.remove('scale-0');
          chatIconClose.classList.add('scale-0');
        } else {
          chatWindow.classList.remove('hidden');
          setTimeout(() => chatWindow.classList.remove('scale-95', 'opacity-0', '-translate-y-4'), 10);
          chatIconOpen.classList.add('scale-0');
          chatIconClose.classList.remove('scale-0');
        }
      };

      const addMessage = (message, type) => {
        const messageDiv = document.createElement('div');
        messageDiv.className = type === 'bot' ? 'bot-message' : 'user-message';
        messageDiv.innerHTML = message; // Use innerHTML to render links
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      };

      const showSuggestions = (suggestions) => {
        chatSuggestions.innerHTML = '';
        suggestions.forEach(suggestion => {
          const chip = document.createElement('button');
          chip.className = 'suggestion-chip text-sm';
          chip.textContent = suggestion;
          chip.onclick = () => handleSuggestionClick(suggestion);
          chatSuggestions.appendChild(chip);
        });
      };

      const handleSuggestionClick = (question) => {
        addMessage(question, 'user');
        chatSuggestions.innerHTML = ''; // Clear suggestions

        // Add thinking indicator as a new message
        const thinkingDiv = document.createElement('div');
        thinkingDiv.id = 'thinking-indicator';
        thinkingDiv.className = 'bot-message flex'; // Use flex to align items
        thinkingDiv.innerHTML = `
          <div class="thinking-dots flex items-center gap-1">
            <span class="w-1.5 h-1.5 bg-pistachio rounded-full"></span>
            <span class="w-1.5 h-1.5 bg-pistachio rounded-full"></span>
            <span class="w-1.5 h-1.5 bg-pistachio rounded-full"></span>
          </div>
        `;
        chatMessages.appendChild(thinkingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        setTimeout(() => {
          document.getElementById('thinking-indicator')?.remove(); // Remove thinking indicator

          const answer = qaPairs[question];
          if (question === "That's not my name.") {
            chatState = 'greeting';
            userName = '';
            localStorage.removeItem('chatbotUserName'); // Clear stored name
            initChat(); // Restart the process
          } else if (answer) {
            addMessage(answer, 'bot');
            const suggestionsWithReset = [...initialSuggestions, "That's not my name."];
            setTimeout(() => showSuggestions(suggestionsWithReset), 500);
          }
        }, 1000); // Simulate thinking
      };

      const askForName = () => {
        addMessage("Hello! I'm the Auto Assistant. What should I call you?", 'bot');
        chatSuggestions.innerHTML = `
          <div class="flex gap-2 p-1 w-full">
            <input type="text" id="chatbot-name-input" placeholder="Enter your name..." class="flex-grow bg-black/50 border border-glass-border rounded-full px-4 py-2 text-sm text-text-primary focus:ring-caribbean-green focus:outline-none transition-all duration-300">
            <button id="chatbot-name-submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-caribbean-green text-rich-black hover:scale-110 transition-transform">
              <span class="material-symbols-outlined">send</span>
            </button>
          </div>
        `;
        chatState = 'asking_name';
        const nameInput = document.getElementById('chatbot-name-input');
        const nameSubmit = document.getElementById('chatbot-name-submit');
        nameInput.focus();

        const handleNameSubmission = () => {
          const name = nameInput.value.trim();
          if (name) {
            userName = name;
            localStorage.setItem('chatbotUserName', name); // Save name to localStorage
            addMessage(name, 'user');
            chatState = 'main';
            setTimeout(() => {
              addMessage(`Nice to meet you, ${userName}! How can I help you today?`, 'bot');
              const suggestionsWithReset = [...initialSuggestions, "That's not my name."];
              qaPairs["That's not my name."] = "My apologies! Let's try that again.";
              showSuggestions(suggestionsWithReset);
            }, 500);
          }
        };

        nameSubmit.addEventListener('click', handleNameSubmission);
        nameInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') handleNameSubmission();
        });
      };

      // Event Listeners
      chatbotToggle.addEventListener('click', () => toggleChatbot());
      closeChatBtn.addEventListener('click', () => toggleChatbot(true));

      // Close chat if user clicks outside of it
      document.addEventListener('click', function(event) {
        const isClickOnSuggestion = event.target.closest('.suggestion-chip');
        const isClickInsideChat = chatWindow.contains(event.target);
        const isClickOnToggle = chatbotToggle.contains(event.target);
        if (!isClickInsideChat && !isClickOnToggle && !isClickOnSuggestion && !chatWindow.classList.contains('hidden')) {
          toggleChatbot(true);
        }
      });

      // Close chat with Escape key
      document.addEventListener('keydown', function(event) {
        if (event.key === "Escape" && !chatWindow.classList.contains('hidden')) {
          toggleChatbot(true);
        }
      });

      // Close chat when a link inside is clicked
      chatMessages.addEventListener('click', (e) => {
        if (e.target.tagName === 'A' && e.target.getAttribute('href').startsWith('#')) {
          toggleChatbot(true);
        }
      });

      // Initial state
      const initChat = () => {
        chatMessages.innerHTML = '';
        const suggestionsWithReset = [...initialSuggestions, "That's not my name."];
        qaPairs["That's not my name."] = "My apologies! Let's try that again.";
        if (chatState === 'greeting') {
          askForName();
        } else { // This handles loading/re-opening the chat when name is already known
          addMessage(`Hello again, ${userName}! What else can I help you with?`, 'bot');
          showSuggestions(suggestionsWithReset);
        }
      };

      initChat();
    });

    document.addEventListener("DOMContentLoaded", () => {
      // Page load animation
      document.body.classList.remove('opacity-0');

      // AOS Init
      if (typeof AOS !== 'undefined') {
        AOS.init({
          duration: 800,
          once: true,
          offset: 100,
        });
      }

      // Navbar blur effect
      const header = document.getElementById("site-header");
      window.addEventListener("scroll", () => {
        if (window.scrollY > 10) {
          header.classList.add("backdrop-blur-md", "bg-rich-black/40");
          header.classList.remove("bg-transparent");
        } else {
          header.classList.remove("backdrop-blur-md", "bg-rich-black/40");
          header.classList.add("bg-transparent");
        }
      });

      // Stats count-up
      const statValues = document.querySelectorAll(".stat-value");
      statValues.forEach(el => {
        const target = parseInt(el.getAttribute("data-target"));
        const suffix = el.getAttribute("data-suffix") || "";
        const prefix = el.getAttribute("data-prefix") || "";
        let current = 0;
        const increment = Math.ceil(target / 80);

        const update = () => {
          current += increment;
          if (current > target) current = target;
          el.textContent = `${prefix}${current}${suffix}`;
          if (current < target) requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
      });

      // Smooth scroll for navlinks
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          if (targetId.startsWith('#')) {
            const targetElement = document.getElementById(targetId.substring(1));
            if (targetElement) {
              const headerHeight = document.getElementById('site-header').offsetHeight;
              const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
              window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
              });
            }
          } else {
            window.location.href = targetId;
          }
        });
      });

      // Active Navlink Highlighting
      const sections = document.querySelectorAll("section[id]");
      const navLinkElements = document.querySelectorAll("nav ul li a");

      function activateNavLink() {
        let scrollY = window.pageYOffset;
        let currentSectionId = "";

        sections.forEach((section) => {
          const sectionHeight = section.offsetHeight;
          const sectionTop = section.offsetTop - 120;
          if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            currentSectionId = section.getAttribute("id");
          }
        });

        navLinkElements.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === "#" + currentSectionId) {
            link.classList.add("active");
          }
        });
      }
      window.addEventListener("scroll", activateNavLink);
      activateNavLink();

      // FAQ Accordion
      const faqToggles = document.querySelectorAll(".faq-toggle");
      faqToggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
          const isActive = toggle.classList.contains("active");
          // Close all accordions
          faqToggles.forEach(t => {
            t.classList.remove("active");
            t.nextElementSibling.classList.remove('open');
          });
          // If the clicked one wasn't active, open it
          if (!isActive) {
            toggle.classList.add("active");
            toggle.nextElementSibling.classList.add('open');
          }
        });
      });

      // Booking Form
      const bookingForm = document.getElementById('booking-form-element');
      const bookingFormContainer = document.getElementById('booking-form-container');
      const confirmationContainer = document.getElementById('confirmation-container');
      const newBookingBtn = document.getElementById('new-booking-btn');
      if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
          // Allow form submission to backend normally
          // Remove custom confirmation display to let backend handle feedback
        });
      }
      if (newBookingBtn) {
        newBookingBtn.addEventListener('click', function() {
          confirmationContainer.classList.add('hidden');
          bookingFormContainer.classList.remove('hidden');
          if (bookingForm) bookingForm.reset();
          document.getElementById('name').value = '';
          AOS.refresh();
          window.scrollTo({
            top: bookingFormContainer.offsetTop - 100,
            behavior: 'smooth'
          });
        });
      }

      // Services Filter
      const filterButtons = document.querySelectorAll('.service-filter-btn');
      const serviceCards = document.querySelectorAll('.service-card');

      filterButtons.forEach(button => {
        button.addEventListener('click', () => {
          // Update active button
          filterButtons.forEach(btn => btn.classList.remove('active'));
          button.classList.add('active');

          const filter = button.dataset.filter;

          serviceCards.forEach(card => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            card.classList.add('hidden');
            
            if (filter === 'all' || card.dataset.category === filter) {
              setTimeout(() => { card.classList.remove('hidden'); card.style.opacity = '1'; card.style.transform = 'scale(1)'; }, 50);
            }
          });
        });
      });

      // Pricing Plan Toggle
      const planToggle = document.getElementById('plan-toggle');
      if (planToggle) {
        const planTypeService = document.getElementById('plan-type-service');
        const planTypeAnnual = document.getElementById('plan-type-annual');

        planToggle.addEventListener('change', function() {
          const isAnnual = this.checked;
          document.querySelectorAll('.pricing-card').forEach(card => {
            const priceEl = card.querySelector('[data-price-service]');
            const periodEl = card.querySelector('[data-period-service]');
            
            const price = isAnnual ? priceEl.dataset.priceAnnual : priceEl.dataset.priceService;
            const period = isAnnual ? periodEl.dataset.periodAnnual : periodEl.dataset.periodService;

            priceEl.textContent = price.includes('Custom') ? 'Custom' : `₱${price}`;
            periodEl.textContent = period;
          });

          planTypeService.classList.toggle('text-text-primary', !isAnnual);
          planTypeAnnual.classList.toggle('text-text-primary', isAnnual);
        });
      }

      // Back to Top Button
      const backToTop = document.getElementById("backToTop");
      window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
          backToTop.classList.remove("opacity-0", "pointer-events-none");
          backToTop.classList.add("opacity-100");
        } else {
          backToTop.classList.add("opacity-0", "pointer-events-none");
          backToTop.classList.remove("opacity-100");
        }
      });
      backToTop.addEventListener("click", () => {
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      });

      // Simplified Calendar
      const monthYear = document.getElementById('month-year');
      const calendarDays = document.getElementById('calendar-days');
      const prevMonthBtn = document.getElementById('prev-month');
      const nextMonthBtn = document.getElementById('next-month');
      const timeSelect = document.getElementById('time');
      const selectedDateInput = document.getElementById('selected-date-input');

      let fullyBookedDates = []; // NEW: Store fully booked dates
      let availabilityData = {}; // Store all availability data for the month
      let currentDate = new Date();

      function renderCalendar() {
        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();

        monthYear.textContent = `${currentDate.toLocaleString('default', { month: 'long' })} ${year}`;

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        calendarDays.innerHTML = '';

        for (let i = 0; i < firstDayOfMonth; i++) {
          const emptyDiv = document.createElement('div');
          calendarDays.appendChild(emptyDiv);
        }

        for (let day = 1; day <= daysInMonth; day++) {
          const dateStr = formatDate(year, month + 1, day);
          const dayDate = new Date(year, month, day);
          const today = new Date();
          today.setHours(0, 0, 0, 0); // Normalize today's date

          const dayElement = document.createElement('div');
          dayElement.textContent = day;
          dayElement.classList.add('cursor-pointer', 'w-10', 'h-10', 'flex', 'items-center', 'justify-center', 'rounded-full', 'transition-all', 'duration-300', 'ease-in-out', 'transform', 'hover:scale-110', 'hover:bg-caribbean-green', 'hover:text-rich-black');
          
          const isPastDate = dayDate < today;
          const isFullyBooked = availabilityData[dateStr] ? availabilityData[dateStr].is_fully_booked : false;

          if (isPastDate || isFullyBooked) {
              dayElement.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-800', 'text-gray-500', 'pointer-events-none');
              if (isFullyBooked) {
                  dayElement.title = "Fully Booked";
              } else {
                  dayElement.title = "Past Date";
              }
          } else {
            dayElement.addEventListener('click', () => {
              // Remove previously selected
              calendarDays.querySelectorAll('.day-selected').forEach(el => {
                el.classList.remove('day-selected', 'bg-caribbean-green', 'text-rich-black');
              });
              dayElement.classList.add('day-selected', 'bg-caribbean-green', 'text-rich-black');
              selectedDateInput.value = dateStr;
              // Update time slots based on selected date
              populateTimeOptions(dateStr);
            });
          }

          // Auto-select today's date if it's not in the past and not fully booked
          if (day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear() && !isPastDate && !isFullyBooked) {
              dayElement.classList.add('day-selected', 'bg-caribbean-green', 'text-rich-black');
              selectedDateInput.value = dateStr;
              populateTimeOptions(dateStr);
          }

          calendarDays.appendChild(dayElement);
        }
      }

      function formatDate(year, month, day) {
        // Format YYYY-MM-DD
        const mm = month < 10 ? '0' + month : month;
        const dd = day < 10 ? '0' + day : day;
        return `${year}-${mm}-${dd}`;
      }

      function populateTimeOptions(selectedDateStr = null) {
        timeSelect.innerHTML = '<option disabled selected value="">Select Time</option>';
        const availableSlots = selectedDateStr && availabilityData[selectedDateStr] ? availabilityData[selectedDateStr].available_slots : [];

        if (availableSlots.length > 0) {
            availableSlots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot; // e.g., "08:00:00"
                option.textContent = slot.substring(0, 5); // e.g., "08:00"
                timeSelect.appendChild(option);
            });
        } else if (selectedDateStr) {
            timeSelect.innerHTML = '<option disabled selected value="">No available times</option>';
        }
      }

      async function fetchAvailability(technicianId, month, year) {
          const calendarDays = document.getElementById('calendar-days');
          calendarDays.innerHTML = '<div class="col-span-7 text-center p-4 text-pistachio">Loading availability...</div>'; // Loading state
          calendarDays.classList.add('pointer-events-none', 'opacity-50');

          if (!technicianId) {
              availabilityData = {};
              renderCalendar();
              populateTimeOptions();
              calendarDays.classList.remove('pointer-events-none', 'opacity-50');
              return;
          }
          try {
              const response = await fetch(`Backend/get-availability.php?technician_id=${technicianId}&month=${month}&year=${year}`);
              if (!response.ok) throw new Error('Network response was not ok');
              availabilityData = await response.json();
          } catch (error) {
              console.error('Failed to fetch availability:', error);
              availabilityData = {};
              calendarDays.innerHTML = '<div class="col-span-7 text-center p-4 text-red-400">Could not load availability.</div>';
          } finally {
              calendarDays.classList.remove('pointer-events-none', 'opacity-50');
              renderCalendar(); // Render calendar even if fetch fails
              populateTimeOptions(selectedDateInput.value); // Re-populate times for the selected date
          }
      }

      prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        fetchAvailability(form.elements['technician_id'].value, currentDate.getMonth() + 1, currentDate.getFullYear());
      });

      nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        fetchAvailability(form.elements['technician_id'].value, currentDate.getMonth() + 1, currentDate.getFullYear());
      });

      // Form submission validation
      bookingForm.addEventListener('submit', function(e) {
        if (!selectedDateInput.value) {
          alert('Please select a date for your booking.');
          e.preventDefault();
        }
        if (!timeSelect.value) {
          alert('Please select a time for your booking.');
          e.preventDefault();
        }
      });

      // --- NEW: Modal Logic ---
      const bookingBtn = document.getElementById('booking-submit-btn');
      const paymentModal = document.getElementById('payment-modal');
      const closeModalBtn = document.getElementById('close-payment-modal');
      const confirmAndPayBtn = document.getElementById('confirm-and-pay-btn');
      const packageSelectorModal = document.getElementById('package-selector-modal');
      const form = document.getElementById('booking-form-element');
      const amountHiddenInput = document.getElementById('amount-hidden');
      
      // Hidden inputs for payment details
      const packageNameHiddenInput = document.getElementById('package-name-hidden');
      const paymentReferenceHiddenInput = document.getElementById('payment-reference-hidden');
      const gcashNameHiddenInput = document.getElementById('gcash-name-hidden');

      form.elements['technician_id'].addEventListener('change', (e) => {
          currentDate = new Date(); // Reset to current month when technician changes
          selectedDateInput.value = ''; // Clear selected date
          fetchAvailability(e.target.value, currentDate.getMonth() + 1, currentDate.getFullYear());
      });

      // Open Modal
      bookingBtn.addEventListener('click', function() {
        // Client-side validation before showing payment modal
        const name = form.elements['name'].value;
        const email = form.elements['email'].value;
        const technician = form.elements['technician_id'].value;
        const date = form.elements['date'].value;
        const vehicle = form.elements['vehicle_name'].value;
        const time = form.elements['time'].value;
        
        let isValid = true;
        const requiredFields = [
          { element: form.elements['name'], message: 'Full Name' },
          { element: form.elements['email'], message: 'Email Address' },
          { element: form.elements['technician_id'], message: 'Technician' },
          { element: form.elements['vehicle_name'], message: 'Vehicle' },
          { element: form.elements['date'], message: 'Date' },
          { element: form.elements['time'], message: 'Time' }
        ];

        requiredFields.forEach(field => {
          if (!field.element.value.trim()) {
            field.element.classList.add('border-red-500', 'focus:ring-red-500'); // Add visual error
            isValid = false;
          } else {
            field.element.classList.remove('border-red-500', 'focus:ring-red-500'); // Remove visual error
          }
        });

        if (!isValid) {
          Swal.fire({
            icon: 'warning',
            title: 'Please complete the booking form',
            text: 'All required fields must be filled out before proceeding to payment.',
            background: 'var(--background)',
            color: 'var(--text-primary)',
            confirmButtonColor: 'var(--caribbean-green)'
          });
          return;
        }
        
        // Set initial package and amount for the modal
        const defaultPackageLabel = packageSelectorModal.querySelector('label.border-caribbean-green');
        if (defaultPackageLabel) {
          amountHiddenInput.value = defaultPackageLabel.dataset.price;
          packageNameHiddenInput.value = defaultPackageLabel.dataset.packageName;
        }

        // Show modal
        paymentModal.classList.remove('hidden');
        paymentModal.classList.add('flex');
      });
      // Close Modal
      closeModalBtn.addEventListener('click', () => {
        paymentModal.classList.add('hidden');
        paymentModal.classList.remove('flex');
      });

      // Package Selection in Modal
      packageSelectorModal.addEventListener('click', (e) => {
        const label = e.target.closest('label');
        if (label) {
          // Ensure the radio button inside the label is checked
          const radioButton = label.querySelector('input[type="radio"]');
          if (radioButton) {
            radioButton.checked = true;
          }
          // Update UI
          packageSelectorModal.querySelectorAll('label').forEach(l => l.classList.remove('border-caribbean-green', 'bg-caribbean-green/10'));
          label.classList.add('border-caribbean-green', 'bg-caribbean-green/10');
          // Update hidden amount input
          amountHiddenInput.value = label.dataset.price;
          packageNameHiddenInput.value = label.dataset.packageName;
        }
      });

      // Final Submit from Modal
      confirmAndPayBtn.addEventListener('click', () => {
        // Get values from modal
        const refInput = document.getElementById('modal-payment-ref');
        const gcashNameInput = document.getElementById('modal-gcash-name');        

        // Validate modal fields
        if (!refInput.value.trim() || !gcashNameInput.value.trim()) {
          Swal.fire({
            icon: 'error',
            title: 'Missing Payment Details',
            text: 'Please enter both the GCash Reference Number and Account Name.',
            background: 'var(--background)',
            color: 'var(--text-primary)',
            confirmButtonColor: 'var(--caribbean-green)'
          });
          return;
        }
        
        paymentReferenceHiddenInput.value = refInput.value;
        gcashNameHiddenInput.value = gcashNameInput.value;
        // Submit the main form
        form.submit();
      });

      // Initial load
      fetchAvailability(form.elements['technician_id'].value, currentDate.getMonth() + 1, currentDate.getFullYear());
    });

    // NEW: Technician Rating Tooltip Logic
    document.querySelectorAll('article[data-trainer-id]').forEach(card => {
      const tooltipContent = card.querySelector('.trainer-tooltip-content');
      let ratingsLoaded = false;

      card.addEventListener('mouseenter', async () => {
        tooltipContent.classList.remove('hidden');
        if (ratingsLoaded) return;

        const technicianId = card.dataset.trainerId;
        const avgRatingEl = tooltipContent.querySelector('.average-rating');
        const ratingsListEl = tooltipContent.querySelector('.ratings-list');

        avgRatingEl.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';

        try {
          const response = await fetch(`api.php?action=get_technician_ratings&technician_id=${technicianId}`);
          const data = await response.json();

          if (data.ratings && data.ratings.length > 0) {
            const totalRating = data.ratings.reduce((sum, r) => sum + r.rating, 0);
            const avgRating = totalRating / data.ratings.length;

            avgRatingEl.innerHTML = '';
            for (let i = 1; i <= 5; i++) {
              avgRatingEl.innerHTML += `<span class="material-symbols-outlined text-base ${i <= avgRating ? 'text-yellow-400' : 'text-stone'}">star</span>`;
            }
            avgRatingEl.innerHTML += `<span class="ml-1 text-xs secondary-text">(${avgRating.toFixed(1)})</span>`;

            ratingsListEl.innerHTML = data.ratings.map(r => `
                        <div class="border-t border-glass-border pt-2">
                            <p class="secondary-text italic">"${r.feedback || 'No feedback provided.'}"</p>
                            <p class="text-right text-xs text-stone mt-1">- ${r.user_name}</p>
                        </div>
                    `).join('');
          } else {
            avgRatingEl.innerHTML = '<span class="text-xs text-gray-500">No ratings yet</span>';
            ratingsListEl.innerHTML = '';
          }
          ratingsLoaded = true;
        } catch (error) {
          console.error('Failed to fetch ratings:', error);
          avgRatingEl.innerHTML = '<span class="text-xs text-red-500">Error</span>';
        }
      });

      card.addEventListener('mouseleave', () => {
        tooltipContent.classList.add('hidden');
      });
    });

  </script>


</body>

</html>