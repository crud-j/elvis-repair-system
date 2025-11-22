<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MyNissan Customer Dashboard</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  
  <!-- SweetAlert2 for better alerts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link rel="stylesheet" href="../css/scroll.css" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <style type="text/tailwindcss">
    @layer base {
      :root, html {
        --background: #f8fafc;
        --text-primary: #1f2937;
        --text-muted: #6b7280;
        --card-bg: rgba(255, 255, 255, 0.6);
        --card-border: rgba(0, 0, 0, 0.1);
        --sidebar-bg: rgba(255, 255, 255, 0.4);
      }
      html.dark {
        --background: #0D0F11;
        --text-primary: #F7F7F6;
        --text-muted: #A6CEC4;
        --card-bg: rgba(166, 206, 196, 0.05);
        --card-border: rgba(47, 166, 140, 0.2);
        --sidebar-bg: rgba(13, 15, 17, 0.5);
      }
    }
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
    }
    .glass-card {
        background-color: var(--card-bg);
        border-color: var(--card-border);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
  </style>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
           colors: {
            "background": "var(--background)",
            "text-primary": "var(--text-primary)",
            "text-muted": "var(--text-muted)",
            "primary": "#00DF81",
            "primary-dark": "#22CC95",
          },
          animation: {
            'fade-in': 'fadeIn 0.5s ease-out',
            'slide-up': 'slideUp 0.5s ease-out'
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' }
            },
            slideUp: {
              '0%': { transform: 'translateY(10px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' }
            }
          },
          fontFamily: {
            "display": ["Roboto", "sans-serif"]
          },
        },
      },
    }
  </script>
  <script>
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
</head>

<body class="bg-background font-display text-text-primary flex min-h-screen transition-colors duration-300 antialiased">
  <!-- Background Glows -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-primary/20 dark:bg-primary/10 rounded-full blur-[160px] animate-pulse"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/20 dark:bg-primary/10 rounded-full blur-[160px] animate-pulse [animation-delay:-3s]"></div>

  <!-- Sidebar -->
<aside id="sidebar" class="group flex-shrink-0 w-64 bg-sidebar-bg backdrop-blur-lg border-r border-card-border p-6 flex flex-col transition-all duration-300">
   <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 ">
    <div class="flex items-center gap-3 p-2 mb-6">
      <div class="size-8 text-primary" aria-hidden="true">
        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8">
          <path d="M24 4C25.7818 14.2173 33.7827 22.2182 44 24C33.7827 25.7818 25.7818 33.7827 24 44C22.2182 33.7827 14.2173 25.7818 4 24C14.2173 22.2182 22.2182 14.2173 24 4Z" fill="currentColor"></path>
        </svg>
      </div>
      <h2 class="text-text-primary text-lg font-bold leading-tight tracking-[-0.015em] group-[.is-collapsed]:hidden">MyNissan</h2>
    </div>

    <nav class="flex flex-col gap-2" role="navigation" aria-label="Main sidebar navigation">
      <div class="border-t border-card-border my-2"></div>
       <button id="sidebar-collapse-btn" class="mt-4 w-full h-10 flex items-center justify-center text-text-muted bg-white/5 dark:bg-black/20 hover:bg-white/10 dark:hover:bg-white/5 rounded-lg border border-card-border transition-colors" aria-label="Collapse sidebar">
      <span class="material-symbols-outlined transform transition-transform duration-300">chevron_left</span>
    </button>
       <!-- Other Buttons -->
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="dashboard" aria-label="Dashboard">
        <span class="material-symbols-outlined">dashboard</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">Dashboard</p>
      </button>
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="vehicles" aria-label="My Vehicles">
        <span class="material-symbols-outlined">directions_car</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">My Vehicles</p>
      </button>
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="services" aria-label="Services">
        <span class="material-symbols-outlined">build</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">Services</p>
      </button>
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="store" aria-label="Parts Store">
        <span class="material-symbols-outlined">storefront</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">Parts Store</p>
      </button>
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="appointments" aria-label="My Appointments">
        <span class="material-symbols-outlined">calendar_month</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">My Appointments</p>
      </button>
      
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="invoices" aria-label="My Invoices">
        <span class="material-symbols-outlined">receipt_long</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">My Invoices</p>
      </button>
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center relative" data-section="notifications" aria-label="Notifications">
        <span class="material-symbols-outlined">notifications</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">Notifications</p>
        <span id="sidebar-notification-badge" class="absolute top-1 right-1 size-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full hidden group-[.is-collapsed]:top-0 group-[.is-collapsed]:right-0"></span>
      </button>
      <button id="cart-sidebar-btn" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center relative" aria-label="Shopping Cart">
        <span class="material-symbols-outlined">shopping_cart</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">Cart</p>
        <span id="cart-count-badge" class="absolute top-1 right-1 size-5 flex items-center justify-center bg-primary text-background text-xs font-bold rounded-full hidden group-[.is-collapsed]:top-0 group-[.is-collapsed]:right-0">0</span>
      </button>
      <button onclick="smoothScrollTo(this)" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-all duration-200 group-[.is-collapsed]:justify-center" data-section="profile" aria-label="Profile Setup">
        <span class="material-symbols-outlined">manage_accounts</span>
        <p class="text-sm font-medium leading-normal group-[.is-collapsed]:hidden">Profile Setup</p>
      </button> 
        <a href="../../index.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-muted hover:bg-primary/10 hover:text-primary transition-colors duration-200" aria-label="Back to Home">
        <span class="material-symbols-outlined">home</span>
        <p class="text-sm font-medium leading-normal">Back to Home</p>
      </a>
    </nav>
  </div>

  <!-- Bottom Section (Sticky) -->
  <div class="flex-shrink-0 pt-4 flex flex-col gap-4">
    <div class="flex items-center gap-2">
      <!-- Notification Bell -->
      <button id="theme-toggle-btn" class="relative size-10 flex items-center justify-center text-text-muted bg-white/5 dark:bg-black/20 hover:bg-white/10 dark:hover:bg-white/5 rounded-lg border border-card-border transition-colors" aria-label="Toggle theme">
        <span class="material-symbols-outlined absolute block dark:hidden">light_mode</span>
        <span class="material-symbols-outlined absolute hidden dark:block">dark_mode</span>
      </button>
      <a href="../../Backend/logout.php" class="text-text-muted hover:text-primary transition-colors group-[.is-collapsed]:hidden" aria-label="Logout">
        <span class="material-symbols-outlined">logout</span>
      </a>
    </div>
    <div class="flex items-center gap-2 text-xs text-text-muted p-2 group-[.is-collapsed]:justify-center">
      <span class="material-symbols-outlined text-sm" aria-hidden="true">lock</span>
      <p class="group-[.is-collapsed]:hidden">Secure &amp; Encrypted</p>
    </div>
    <div class="border-t border-card-border pt-4 flex items-center gap-3">
      <img id="sidebar-profile-pic" src="../../assets/img/placeholders/placeholder.png" alt="User profile picture" class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" />
      <div class="flex flex-col flex-1 group-[.is-collapsed]:hidden">
        <h1 id="user-name" class="text-text-primary text-sm font-medium leading-normal">Loading...</h1>
        <p id="user-email" class="text-text-muted text-xs font-normal leading-normal">Loading...</p>
      </div>
    </div>
  </div>
</aside>

  <!-- Main content -->
  <main class="flex-1 flex flex-col overflow-y-auto p-6 lg:p-10 custom-scrollbar">
    <section id="dashboard" class="section-content mb-16 hidden animate-fade-in">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h1 class="text-text-primary text-4xl font-black leading-tight tracking-[-0.033em]">Dashboard</h1>
        <div class="flex items-center gap-4">
            <!-- Notification Bell -->
             <div class="relative">
                <button id="header-notification-bell" class="relative size-10 flex items-center justify-center text-text-muted bg-white/5 dark:bg-black/20 hover:bg-white/10 dark:hover:bg-white/5 rounded-full border border-card-border transition-transform hover:scale-105">
                  <span class="material-symbols-outlined">notifications</span>
                  <span id="header-notification-badge" class="absolute top-0 right-0 size-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full hidden"></span>
                </button>
                <div id="header-notification-panel" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-background border border-card-border rounded-xl shadow-2xl z-50 glass-card overflow-hidden animate-slide-up">
                  <div class="p-4 border-b border-card-border flex justify-between items-center">
                    <h3 class="font-bold text-text-primary">Notifications</h3>
                    <button id="header-mark-all-read-btn" class="text-sm text-primary hover:underline">Mark all as read</button>
                  </div>
                  <div id="header-notification-list" class="p-2 max-h-96 overflow-y-auto"></div>
                </div>
              </div>
        </div>
      </div>


      <p id="welcome-message" class="text-text-muted text-base font-normal leading-normal mb-8">Welcome back! Here is an overview of your account.</p>
      <!-- Dashboard quick summary -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card rounded-xl border p-6 flex items-center gap-4">
          <span class="material-symbols-outlined text-4xl text-primary p-3 bg-primary/10 rounded-full">event_upcoming</span>
          <div><h2 class="font-semibold text-text-muted">Upcoming Appointments</h2><p id="dashboard-upcoming-appointments" class="text-2xl font-bold">0</p></div>
        </div>
        <div class="glass-card rounded-xl border p-6 flex items-center gap-4">
          <span class="material-symbols-outlined text-4xl text-primary p-3 bg-primary/10 rounded-full">payments</span>
          <div><h2 class="font-semibold text-text-muted">Pending Payments</h2><p id="dashboard-pending-payments" class="text-2xl font-bold">₱0.00</p></div>
        </div>
        <div class="glass-card rounded-xl border p-6 flex items-center gap-4">
          <span class="material-symbols-outlined text-4xl text-primary p-3 bg-primary/10 rounded-full">directions_car</span>
          <div><h2 class="font-semibold text-text-muted">Vehicles Registered</h2><p id="dashboard-vehicles-registered" class="text-2xl font-bold">0</p></div>
        </div>
      </div>

      <!-- What's New Section -->
      <div class="mt-12">
        <h2 class="text-text-primary text-2xl font-bold leading-tight tracking-[-0.033em] mb-6">What's New in the Store</h2>
        <div id="new-parts-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Skeleton loaders for new parts -->
            <div class="glass-card rounded-xl border p-4 animate-pulse"><div class="h-40 bg-slate-700/20 rounded"></div><div class="h-6 bg-slate-700/20 rounded w-3/4 mt-4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-2"></div></div>
            <div class="glass-card rounded-xl border p-4 animate-pulse"><div class="h-40 bg-slate-700/20 rounded"></div><div class="h-6 bg-slate-700/20 rounded w-3/4 mt-4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-2"></div></div>
        </div>
      </div>

      <!-- Recommended Services Section -->
      <div class="mt-12">
        <h2 class="text-text-primary text-2xl font-bold leading-tight tracking-[-0.033em] mb-6">Recommended For You</h2>
        <div id="recommended-services-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Skeleton loaders for recommendations -->
            <div class="glass-card rounded-xl border p-4 animate-pulse"><div class="h-40 bg-slate-700/20 rounded"></div><div class="h-6 bg-slate-700/20 rounded w-3/4 mt-4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-2"></div></div>
            <div class="glass-card rounded-xl border p-4 animate-pulse"><div class="h-40 bg-slate-700/20 rounded"></div><div class="h-6 bg-slate-700/20 rounded w-3/4 mt-4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-2"></div></div>
            <div class="glass-card rounded-xl border p-4 animate-pulse"><div class="h-40 bg-slate-700/20 rounded"></div><div class="h-6 bg-slate-700/20 rounded w-3/4 mt-4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-2"></div></div>
            <div class="glass-card rounded-xl border p-4 animate-pulse"><div class="h-40 bg-slate-700/20 rounded"></div><div class="h-6 bg-slate-700/20 rounded w-3/4 mt-4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-2"></div></div>
        </div>
      </div>

    </section>


    <!-- Vehicle Section -->
    <section id="vehicles" class="section-content mb-16 hidden animate-fade-in">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-text-primary text-4xl font-black leading-tight tracking-[-0.033em] mb-2">Manage My Vehicles</h1>
                <p class="text-text-muted text-base font-normal leading-normal">Add, edit, and schedule service for your vehicles.</p>
            </div>
            <button id="add-vehicle-btn" class="flex items-center justify-center gap-2 h-11 px-6 bg-primary text-background text-base font-medium rounded-lg hover:bg-primary-dark transition-transform hover:scale-105">
                <span class="material-symbols-outlined">add</span>
                <span>Add New Vehicle</span>
            </button>
        </div>
        <div id="vehicle-list" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- SKELETON LOADER FOR VEHICLES -->
          <div class="glass-card rounded-xl border p-6 animate-pulse"><div class="h-8 bg-slate-700/20 rounded w-3/4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-4"></div><div class="h-10 bg-slate-700/20 rounded w-full mt-6"></div></div>
          <div class="glass-card rounded-xl border p-6 animate-pulse"><div class="h-8 bg-slate-700/20 rounded w-3/4"></div><div class="h-4 bg-slate-700/20 rounded w-1/2 mt-4"></div><div class="h-10 bg-slate-700/20 rounded w-full mt-6"></div></div>
        </div>
      </div>
    </section>

    <!-- Add/Edit Vehicle Modal -->
    <div id="vehicle-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden animate-fade-in">
        <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-w-3xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 id="vehicle-modal-title" class="text-2xl font-bold text-text-primary">Add New Vehicle</h2>
                <button id="close-vehicle-modal" class="text-text-muted hover:text-primary"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form id="vehicle-form" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                <input type="hidden" id="vehicle-id" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="vehicle-nickname" class="block text-sm font-medium text-text-muted">Nickname</label>
                        <input type="text" id="vehicle-nickname" name="nickname" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="vehicle-plate" class="block text-sm font-medium text-text-muted">Plate Number</label>
                        <input type="text" id="vehicle-plate" name="plate_number" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="vehicle-make" class="block text-sm font-medium text-text-muted">Make</label>
                        <input type="text" id="vehicle-make" name="make" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="vehicle-model" class="block text-sm font-medium text-text-muted">Model</label>
                        <input type="text" id="vehicle-model" name="model" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="vehicle-year" class="block text-sm font-medium text-text-muted">Year</label>
                        <input type="text" id="vehicle-year" name="year" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div>
                    <label for="vehicle-vin" class="block text-sm font-medium text-text-muted">VIN</label>
                    <input type="text" id="vehicle-vin" name="vin" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="vehicle-issues" class="block text-sm font-medium text-text-muted">Known Issues</label>
                    <textarea id="vehicle-issues" name="issues" rows="3" class="w-full mt-1 rounded-lg p-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary" placeholder="Describe any known issues, e.g., 'Rattling noise from engine', 'Check engine light is on'"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-muted">Vehicle Photo</label>
                    <div class="mt-2 flex items-center gap-4">
                        <img id="vehicle-photo-preview" src="../../assets/img/placeholders/placeholder.png" class="size-24 h-24 object-cover rounded-lg bg-black/20 border border-card-border">
                        <input type="file" id="vehicle-photo" name="car_photo" class="hidden" accept="image/*">
                        <input type="hidden" name="existing_photo_url" id="existing-photo-url" value="">
                        <button type="button" id="upload-vehicle-photo-btn" class="rounded-lg h-11 px-4 bg-white/10 border border-card-border text-text-primary text-sm font-medium hover:bg-white/20 transition-colors">Change Photo</button>
                    </div>
                </div>
                <div class="border-t border-card-border pt-4 flex justify-end">
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-11 px-6 bg-primary text-background text-base font-medium hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined">save</span>
                        Save Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vehicle Service History Modal -->
    <div id="history-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden animate-fade-in">
        <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-w-3xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 id="history-modal-title" class="text-2xl font-bold text-text-primary">Service History</h2>
                <button id="close-history-modal" class="text-text-muted hover:text-primary"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div id="history-modal-content" class="flex-1 overflow-y-auto custom-scrollbar pr-2">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-text-primary uppercase bg-white/5 border-b border-card-border">
                        <tr>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Service</th>
                            <th class="px-4 py-3 font-medium">Technician</th>
                            <th class="px-4 py-3 font-medium text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div id="appointment-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden animate-fade-in">
        <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-text-primary">Book a Service</h2>
                <button id="close-appointment-modal" class="text-text-muted hover:text-primary"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form id="appointment-form" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                <input type="hidden" id="appointment-vehicle-id" name="vehicle_id">
                <div>
                    <label for="appointment-service" class="block text-sm font-medium text-text-muted">Service</label>
                    <select id="appointment-service" name="service_id" required class="w-full mt-1 rounded-lg h-11 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary"></select>
                </div>
                <div>
                    <label for="appointment-technician" class="block text-sm font-medium text-text-muted">Preferred Technician</label>
                    <select id="appointment-technician" name="technician_id" required class="w-full mt-1 rounded-lg h-11 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary"></select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="appointment-date" class="block text-sm font-medium text-text-muted">Date</label>
                        <input type="date" id="appointment-date" name="appointment_date" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="appointment-time" class="block text-sm font-medium text-text-muted">Time</label>
                        <input type="time" id="appointment-time" name="appointment_time" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div>
                    <label for="appointment-notes" class="block text-sm font-medium text-text-muted">Notes (Issues)</label>
                    <textarea id="appointment-notes" name="notes" rows="4" class="w-full mt-1 rounded-lg p-3 bg-white/5 border-card-border text-text-primary focus:ring-primary focus:border-primary" placeholder="Please describe the problem in detail."></textarea>
                </div>
                <div class="border-t border-card-border pt-4 flex justify-end">
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-11 px-6 bg-primary text-background text-base font-medium hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined">arrow_forward</span>
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Payment Modal -->
    <div id="service-payment-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden animate-fade-in">
        <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-text-primary">Complete Your Booking</h2>
                <button id="close-service-payment-modal" class="text-text-muted hover:text-primary"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form id="service-payment-form" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                <div class="p-4 border border-card-border rounded-lg bg-black/10">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">Booking Summary</h3>
                    <p class="text-text-muted"><strong>Service:</strong> <span id="payment-service-name"></span></p>
                    <p class="text-text-muted"><strong>Date:</strong> <span id="payment-service-date"></span></p>
                    <p class="text-2xl font-bold text-primary mt-2">Total: <span id="payment-service-amount"></span></p>
                </div>
                <div class="p-4 border border-card-border rounded-lg">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">GCash Payment</h3>
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="text-center">
                            <img src="../../assets/img/placeholders/placeholder.png" alt="GCash QR Code" class="size-36 rounded-lg bg-white p-1">
                            <p class="text-xs text-text-muted mt-2">Scan to pay</p>
                        </div>
                        <div class="flex-1 space-y-4">
                            <div><label for="service-gcash-number" class="block text-sm font-medium text-text-muted">Your GCash Number</label><input type="text" id="service-gcash-number" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" placeholder="09xxxxxxxxx"></div>
                            <div><label for="service-payment-reference" class="block text-sm font-medium text-text-muted">GCash Reference No.</label><input type="text" id="service-payment-reference" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" placeholder="13-digit reference number"></div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-card-border pt-4 flex justify-end">
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-12 px-6 bg-primary text-background text-base font-medium hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined">payment</span>
                        Confirm and Pay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <section id="services" class="section-content mb-16 hidden animate-fade-in">
      <h1 class="text-4xl font-black tracking-tighter text-text-primary mb-6">Service History</h1>
      <p class="text-text-muted mb-8">View a complete record of all services for your vehicles.</p>
      <div class="glass-card rounded-xl border p-6">
        <div class="flex justify-end mb-4">
            <input type="text" id="service-history-search" placeholder="Search services..." class="w-full md:w-1/3 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-text-muted" id="service-history-table">
              <thead class="border-b border-card-border text-text-primary uppercase">
                <tr><th class="px-4 py-3">Service & Date</th><th class="px-4 py-3">Vehicle</th><th class="px-4 py-3 text-right">Cost</th></tr>
              </thead>
              <tbody></tbody>
            </table>
        </div>
      </div>
    </section>
    <section id="appointments" class="section-content mb-16 hidden">
      <h1 class="text-4xl font-black tracking-tighter text-text-primary mb-6">My Upcoming Appointments</h1>
      <div id="appointments-list" class="space-y-6">
        <!-- Appointment cards will be inserted here by JavaScript -->
      </div>
    </section>



<section id="profile" class="section-content mb-16 hidden animate-fade-in">
    <h1 class="text-4xl font-black tracking-tighter text-text-primary mb-2">Profile Setup</h1>
    <p class="text-text-muted mb-8">Update your personal information, profile picture, and password.</p>

    <div class="max-w-5xl">
        <form id="profile-update-form">
            <!-- Profile Picture and Username -->
            <div class="glass-card rounded-2xl border p-8 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="md:col-span-1">
                        <h2 class="text-lg font-semibold text-text-primary">Display Information</h2>
                        <p class="text-sm text-text-muted mt-1">Your public profile.</p>
                    </div>
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                        <div class="flex items-center gap-4">
                            <img id="profile-pic-preview" src="../../assets/img/placeholders/placeholder.png" alt="Profile preview" class="size-20 rounded-full object-cover bg-white/5 border border-card-border">
                            <input type="file" id="profile-picture-upload" name="profile_picture" class="hidden" accept="image/*">
                            <button type="button" id="upload-pic-btn" class="rounded-lg h-10 px-4 bg-white/10 border border-card-border text-text-primary text-sm font-medium hover:bg-white/20 transition-colors">Change</button>
                        </div>
                        <div>
                            <label for="profile-username" class="block text-sm font-medium text-text-muted">Username</label>
                            <input id="profile-username" name="username" type="text" autocomplete="username" class="w-full mt-2 rounded-lg h-11 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="glass-card rounded-2xl border p-8">
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <h2 class="text-lg font-semibold text-text-primary">Change Password</h2>
                        <p class="text-sm text-text-muted mt-1">Leave fields blank to keep your current password.</p>
                    </div>
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label for="current-password" class="block text-sm font-medium text-text-muted">Current Password</label>
                            <input id="current-password" name="current_password" type="password" placeholder="••••••••" autocomplete="current-password" class="w-full mt-2 rounded-lg h-11 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="new-password" class="block text-sm font-medium text-text-muted">New Password</label>
                                <input id="new-password" name="new_password" type="password" placeholder="••••••••" autocomplete="new-password" class="w-full mt-2 rounded-lg h-11 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
                            </div>
                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-text-muted">Confirm New Password</label>
                                <input id="confirm-password" name="confirm_password" type="password" placeholder="••••••••" autocomplete="new-password" class="w-full mt-2 rounded-lg h-11 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 flex justify-end">
                <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-11 px-6 bg-primary text-background text-base font-medium leading-normal hover:bg-primary-dark transition-colors">
                    <span class="material-symbols-outlined">save</span>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</section>

    <section id="invoices" class="section-content mb-16 hidden animate-fade-in">
  <h1 class="text-4xl font-black tracking-tighter text-text-primary mb-6">My Invoices</h1>
  <p class="text-text-muted mb-8">View and download your invoices for services and part purchases.</p>
  <div class="glass-card rounded-xl border p-6">
    <div class="flex justify-end mb-4">
        <input type="text" id="invoices-search" placeholder="Search by ID or detail..." class="w-full md:w-1/3 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm" id="customer-invoices-table">
            <thead class="text-xs text-text-primary uppercase bg-white/5 border-b border-card-border">
                <tr>
                    <th class="px-4 py-3 font-medium" scope="col">Invoice ID</th>
                    <th class="px-4 py-3 font-medium" scope="col">Date Issued</th>
                    <th class="px-4 py-3 font-medium" scope="col">Details</th>
                    <th class="px-4 py-3 font-medium text-right" scope="col">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
  </div>
</section>

<section id="store" class="section-content mb-16 hidden animate-fade-in">
  <h1 class="text-4xl font-black tracking-tighter text-text-primary mb-6">Parts Store</h1>
  <p class="text-text-muted mb-8">Purchase genuine parts directly from our inventory.</p>
  <div class="flex justify-end mb-4">
      <input type="text" id="parts-store-search" placeholder="Search parts..." class="w-full md:w-1/3 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" />
  </div>
  <div id="parts-store-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      </div>
    <div class="flex items-center justify-between pt-8 text-sm text-text-muted" id="parts-pagination-controls">
        <p id="parts-pagination-info">Loading...</p>
        <div class="flex items-center gap-2">
            <button id="parts-prev-page" class="px-3 py-1 rounded-md border border-card-border hover:bg-white/10 disabled:opacity-50" disabled>Previous</button>
            <button id="parts-next-page" class="px-3 py-1 rounded-md border border-card-border hover:bg-white/10 disabled:opacity-50" disabled>Next</button>
        </div>
    </div>
</section>

<section id="notifications" class="section-content mb-16 hidden animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-black tracking-tighter text-text-primary">Notifications</h1>
        <button id="mark-all-read-btn" class="text-sm text-primary hover:underline">Mark all as read</button>
    </div>
    <p class="text-text-muted mb-8">All your recent account activity and updates in one place.</p>
    <div class="glass-card rounded-xl border">
        <div id="notification-list" class="divide-y divide-card-border">
            <!-- Notifications will be inserted here -->
        </div>
    </div>
</section>


  </main>

  <script>
    // Theme toggle
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    themeToggleBtn.addEventListener('click', () => {
      if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      }
    });

    // Set initial theme based on localStorage or system preference
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }


    // Smooth scroll to section when button is clicked
  function smoothScrollTo(button) {
    const section = button.getAttribute('data-section');
    const target = document.querySelector(`[data-section="${section}"]`);
    if (!target) return;

    // Remove active state from all buttons
    document.querySelectorAll('nav button[data-section]').forEach(btn => {
      btn.classList.remove('bg-primary', 'text-background', 'font-bold');
      btn.classList.add('text-text-muted', 'hover:bg-primary/10', 'hover:text-primary');
      btn.querySelector('p').classList.remove('font-bold');
      btn.querySelector('p').classList.add('font-medium');
      btn.querySelector('.material-symbols-outlined').style.fontVariationSettings = "'FILL' 0";
    });

    // Add active state to clicked button
    button.classList.add('bg-primary', 'text-background', 'font-bold');
    button.querySelector('p').classList.add('font-bold');
    button.querySelector('.material-symbols-outlined').style.fontVariationSettings = "'FILL' 1";

    // Smooth scroll parent container to bring button into view
    const container = button.closest('.custom-scrollbar');
    const buttonTop = button.offsetTop - container.offsetTop;
    const containerHeight = container.clientHeight;
    const buttonHeight = button.offsetHeight;

    // Scroll to center the active button
    container.scrollTo({
      top: buttonTop - containerHeight / 2 + buttonHeight / 2,
      behavior: 'smooth'
    });
  }

  // Optional: Auto-scroll active item into view on page load
  document.addEventListener('DOMContentLoaded', () => {
    const activeBtn = document.querySelector('button[aria-current="page"]');
    if (activeBtn) {
      setTimeout(() => smoothScrollTo(activeBtn), 100); // Small delay for layout
    }
  });


    // Sidebar navigation controls showing/hiding sections
    const sidebarButtons = document.querySelectorAll('aside nav button[data-section]');
    const sections = document.querySelectorAll('main section.section-content');

    function showSection(sectionId) {
      sections.forEach(sec => sec.classList.add('hidden')); // Hide all sections
      const activeSection = document.getElementById(sectionId);
      if (activeSection) {
        activeSection.classList.remove('hidden'); // Show the active section
      }

      sidebarButtons.forEach(btn => {
        if (btn.dataset.section === sectionId) {
          btn.classList.add('bg-primary', 'text-background', 'font-bold');
          btn.querySelector('.material-symbols-outlined').style.fontVariationSettings = "'FILL' 1";
          btn.querySelector('p').classList.add('font-bold');
          btn.setAttribute('aria-current', 'page');
        } else {
          btn.classList.remove('bg-primary', 'text-background', 'font-bold');
          btn.removeAttribute('aria-current');
          btn.querySelector('.material-symbols-outlined').style.fontVariationSettings = "'FILL' 0";
          btn.querySelector('p').classList.remove('font-bold');
        }
      });
    }

    sidebarButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const sectionId = btn.dataset.section;
        if (sectionId) {
          showSection(sectionId);
        }
      });
    });

    // Initialize to dashboard view
    showSection('dashboard');

    // --- SIDEBAR COLLAPSE LOGIC ---
    const sidebar = document.getElementById('sidebar');
    const collapseBtn = document.getElementById('sidebar-collapse-btn');
    const collapseIcon = collapseBtn.querySelector('span');

    function setSidebarState(collapsed) {
        if (collapsed) {
            sidebar.classList.add('is-collapsed', 'w-20');
            sidebar.classList.remove('w-64');
            collapseIcon.style.transform = 'rotate(180deg)';
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            sidebar.classList.remove('is-collapsed', 'w-20');
            sidebar.classList.add('w-64');
            collapseIcon.style.transform = 'rotate(0deg)';
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    }

    collapseBtn.addEventListener('click', () => {
        setSidebarState(!sidebar.classList.contains('is-collapsed'));
    });

    // Check localStorage on page load
    setSidebarState(localStorage.getItem('sidebarCollapsed') === 'true');

    // --- DYNAMIC DATA FETCHING ---
    // --- What's New Section ---
    async function loadNewParts() {
        const newParts = await fetchData('getNewParts');
        const newListContainer = document.getElementById('new-parts-list');
        newListContainer.innerHTML = ''; // Clear skeletons

        if (newParts && newParts.length > 0) {
            newParts.forEach(part => {
                const price = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(part.price);
                const partCard = `
                    <div class="glass-card rounded-xl border flex flex-col overflow-hidden group/part">
                        <div class="overflow-hidden"><img src="${part.image_url || '../../assets/img/placeholders/placeholder.png'}" alt="${part.part_name}" class="h-48 w-full object-cover bg-black/20 group-hover/part:scale-105 transition-transform duration-300"></div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-text-primary">${part.part_name}</h3>
                            <p class="text-2xl font-bold text-primary mt-2">${price}</p>
                            <button class="add-to-cart-btn w-full mt-4 flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-primary/10 text-primary hover:bg-primary/20 transition-colors" data-id="${part.id}" data-name="${part.part_name}">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                `;
                newListContainer.insertAdjacentHTML('beforeend', partCard);
            });
        } else {
            newListContainer.innerHTML = '<p class="text-text-muted col-span-full text-center">No new items to show right now.</p>';
        }
    }

    async function loadServiceRecommendations() {
        const recommendations = await fetchData('getServiceRecommendations');
        const container = document.getElementById('recommended-services-list');
        container.innerHTML = ''; // Clear skeletons

        if (recommendations && recommendations.length > 0) {
            recommendations.forEach(service => {
                const price = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(service.price);
                let imageUrl = '../../assets/img/placeholders/placeholder.png';
                if (service.image_url) {
                    // Check if it's an absolute URL or a relative one
                    imageUrl = service.image_url.startsWith('http') ? service.image_url : `../../${service.image_url}`;
                }
                const serviceCard = `
                    <div class="glass-card rounded-xl border flex flex-col overflow-hidden group/service">
                        <div class="overflow-hidden"><img src="${imageUrl}" alt="${service.name}" class="h-48 w-full object-cover bg-black/20 group-hover/service:scale-105 transition-transform duration-300"></div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-text-primary">${service.name}</h3>
                            <p class="text-sm text-text-muted mt-1 flex-grow">${service.description}</p>
                            <p class="text-2xl font-bold text-primary mt-4">${price}</p>
                            <button onclick="document.querySelector('[data-section=services]').click()" class="w-full mt-4 flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                                <span class="material-symbols-outlined">build</span>
                                View Service
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', serviceCard);
            });
        } else {
            container.innerHTML = '<p class="text-text-muted col-span-full text-center">No specific recommendations for your vehicles at this time. Check out our general services!</p>';
        }
    }




    const API_URL = '/Elvis_Repair/Backend/customerdash-backend/customer-backend.php';


    // --- PARTS STORE ---
    let storeCurrentPage = 1;
    const storeLimit = 8;
    let storeSearchQuery = '';

    async function loadPartsStore(page = 1, query = '') {
        storeCurrentPage = page;
        storeSearchQuery = query;

        const data = await fetchData(`getPartsForStore&page=${page}&limit=${storeLimit}&search=${encodeURIComponent(query)}`);
        const storeList = document.getElementById('parts-store-list');
        storeList.innerHTML = ''; // Clear

        if (data && data.parts) {
            data.parts.forEach(part => {
                const price = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(part.price);
                const partCard = `
                    <div class="glass-card rounded-xl border flex flex-col overflow-hidden">
                        <img src="${part.image_url || '../../assets/img/placeholders/placeholder.png'}" alt="${part.part_name}" class="h-48 w-full object-cover bg-black/20">
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-text-primary">${part.part_name}</h3>
                            <p class="text-text-muted text-sm mt-1">Stock: ${part.stock_level}</p>
                            <p class="text-2xl font-bold text-primary mt-4">${price}</p>
                            <div class="mt-auto pt-4 flex items-center gap-2">
                                <button class="purchase-now-btn w-full flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-primary text-background text-base font-medium leading-normal hover:bg-primary-dark transition-colors" data-id="${part.id}" data-name="${part.part_name}" data-price="${part.price}" data-stock="${part.stock_level}" data-image="${part.image_url || '../../assets/img/placeholders/placeholder.png'}">
                                    Purchase Now
                                </button>
                                <button class="add-to-cart-btn flex items-center justify-center rounded-lg h-11 w-12 bg-primary/20 text-primary hover:bg-primary/30 transition-colors" data-id="${part.id}" data-name="${part.part_name}">
                                    <span class="material-symbols-outlined">add_shopping_cart</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                storeList.insertAdjacentHTML('beforeend', partCard);
            });

            if (data.parts.length === 0) {
                storeList.innerHTML = '<p class="text-text-muted col-span-full text-center">No parts found matching your search.</p>';
            }
        }
        renderPartsStorePagination(data.total, page, storeLimit);
    }

    function renderPartsStorePagination(total, page, limit) {
        const paginationInfo = document.getElementById('parts-pagination-info');
        const prevBtn = document.getElementById('parts-prev-page');
        const nextBtn = document.getElementById('parts-next-page');
        
        const totalPages = Math.ceil(total / limit);
        page = Number(page);

        if (total === 0) {
            paginationInfo.textContent = 'No parts available';
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            return;
        }

        const from = (page - 1) * limit + 1;
        const to = Math.min(page * limit, total);
        paginationInfo.textContent = `Showing ${from} to ${to} of ${total} parts`;

        prevBtn.disabled = page <= 1;
        nextBtn.disabled = page >= totalPages;
    }

    function setupPartsSearch() {
        const searchInput = document.getElementById('parts-store-search');
        let debounceTimer;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                loadPartsStore(1, e.target.value);
            }, 300);
        });
    }

    // Pagination button listeners
    document.getElementById('parts-prev-page').addEventListener('click', () => {
        if (storeCurrentPage > 1) {
            loadPartsStore(storeCurrentPage - 1, storeSearchQuery);
        }
    });

    document.getElementById('parts-next-page').addEventListener('click', () => {
        loadPartsStore(storeCurrentPage + 1, storeSearchQuery);
    });

    async function handleAddToCart(event) {
        const button = event.target.closest('.add-to-cart-btn');
        if (!button) return;

        const productId = button.dataset.id;
        const productName = button.dataset.name;
        
        Swal.fire({
            title: `Add ${productName} to cart?`,
            text: "You can adjust quantity in the cart.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, add to cart!',
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: '#6b7280',
            background: 'var(--background)',
            color: 'var(--text-primary)'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    Swal.showLoading();
                    const response = await fetch(`${API_URL}?action=addToCart`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ product_id: productId, quantity: 1, product_name: productName })
                    });
                    const res = await response.json();
                    if (res.success) {
                        Swal.fire({ title: 'Added to Cart!', text: res.message, icon: 'success', confirmButtonColor: 'var(--primary)' });
                        loadCartCount(); // Refresh cart count
                    } else {
                        throw new Error(res.error || 'Failed to add to cart.');
                    }
                } catch (error) {
                    Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
                }
            }
        });
    }


    // --- INVOICES ---
async function loadMyInvoices() {
    const invoices = await fetchData('getInvoices');
    const tableBody = document.querySelector('#customer-invoices-table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    if (!invoices) {
        // Show skeleton loader
        tableBody.innerHTML = Array(3).fill('').map(() => `
            <tr class="border-b border-card-border animate-pulse">
                <td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-24"></div></td><td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-32"></div></td><td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-48"></div></td><td class="px-4 py-4 text-right"><div class="h-4 bg-slate-700/20 rounded w-16 ml-auto"></div></td>
            </tr>`).join('');
        return;
    }

    if (invoices && invoices.length > 0) {
        invoices.forEach(invoice => {
            const issuedTime = new Date(invoice.created_at + ' UTC');
            const formattedTime = issuedTime.toLocaleString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric'
            });
            
            let detailText = '';
            // For invoices generated after the order_items change
            if (invoice.purchased_items) {
                detailText = invoice.purchased_items; // This comes from GROUP_CONCAT in backend
            } else if (invoice.invoice_data) {
                // Fallback for older invoices or if invoice_data still contains HTML/simple text
                try {
                    const invoiceData = JSON.parse(invoice.invoice_data);
                    if (invoiceData.type === 'Appointment' && invoiceData.details && invoiceData.details.service_name) {
                        detailText = `Service: ${invoiceData.details.service_name}`;
                    } else if (invoiceData.type === 'Order' && invoiceData.details && invoiceData.details.items) {
                        detailText = invoiceData.details.items.map(item => `${item.quantity}x ${item.part_name}`).join(', ');
                    } else {
                        // If JSON structure is unexpected
                        detailText = "Details unavailable (parsed from old format)";
                    }
                } catch (e) {
                    // if invoice_data is not JSON, try to extract from old HTML format
                    const detailsMatch = invoice.invoice_data.match(/<p>Service: (.*?)<\/p>|<p>Part: (.*?)<\/p>/);
                    detailText = detailsMatch ? (detailsMatch[1] || detailsMatch[2]) : 'Invoice Details';
                    console.warn("Attempted to parse old invoice_data format for invoice ID", invoice.order_id, e);
                }
            } else {
                detailText = "Details unavailable";
            }

            const row = `
                <tr class="border-b border-card-border hover:bg-white/5 transition-colors">
                    <td class="px-4 py-3 font-medium text-text-primary">ORD-${invoice.order_id}</td>
                    <td class="px-4 py-3 text-text-muted">${formattedTime}</td>
                    <td class="px-4 py-3 text-text-muted">${detailText}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            ${invoice.order_status === 'completed' ? 'bg-primary/20 text-primary' : ''}
                            ${invoice.order_status === 'pending' ? 'bg-yellow-500/20 text-yellow-500' : ''}
                            ${invoice.order_status === 'processing' ? 'bg-blue-500/20 text-blue-500' : ''}
                            ${invoice.order_status === 'cancelled' ? 'bg-red-500/20 text-red-500' : ''}
                            ${!invoice.order_status ? 'bg-gray-500/20 text-gray-500' : ''}
                        ">${invoice.order_status ? invoice.order_status.toUpperCase() : 'N/A'}</span>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    } else {
        tableBody.innerHTML = '<tr class="border-b border-card-border"><td colspan="4" class="px-4 py-4 text-center text-text-muted">No invoices found.</td></tr>';
    }
}

function setupInvoicesSearch() {
    const searchInput = document.getElementById('invoices-search');
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#customer-invoices-table tbody tr');
        rows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const details = row.cells[2].textContent.toLowerCase();
            if (id.includes(searchTerm) || details.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}
    async function fetchData(action, options = {}) {
        try {
            const response = await fetch(`${API_URL}?action=${action}`);
            const text = await response.text(); // Read response as text first
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
            }
            return JSON.parse(text); // Then parse as JSON
        } catch (error) {
            console.error(`Error fetching ${action}:`, error.message);
            return null; // Return null on error to handle it gracefully in calling functions
        }
    }

    // --- USER DETAILS ---
    async function loadUserDetails() {
        const user = await fetchData('getUserDetails');
        if (user) {
            document.getElementById('user-name').textContent = user.username;
            document.getElementById('user-email').textContent = user.email;
            document.getElementById('profile-username').value = user.username;
            document.getElementById('welcome-message').textContent = `Welcome back, ${user.username}! Here is an overview of your account.`;
            
            // Update profile pictures
            const defaultPic = '../../assets/img/placeholders/placeholder.png';
            const sidebarPic = document.getElementById('sidebar-profile-pic');
            const previewPic = document.getElementById('profile-pic-preview');
            
            const profilePicUrl = user.profile_picture_url ? user.profile_picture_url : defaultPic;
            sidebarPic.src = profilePicUrl;
            previewPic.src = profilePicUrl;
        }
    }

    // --- VEHICLES ---
    async function loadVehicles() {
        const vehicles = await fetchData('getVehicles');
        const vehicleList = document.getElementById('vehicle-list');
        vehicleList.innerHTML = ''; // Clear skeleton

        if (vehicles && vehicles.length > 0) {
            vehicles.forEach(vehicle => {
                const vehicleCard = `
                    <article class="glass-card rounded-xl shadow-sm border p-6 flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <img src="${vehicle.car_photo_url}" alt="${vehicle.nickname}" class="size-20 rounded-lg object-cover bg-black/20 border border-card-border">
                                <div class="flex flex-col">
                                    <h3 class="text-text-primary text-xl font-bold">${vehicle.nickname}</h3>
                                    <p class="text-text-muted text-sm font-normal">${vehicle.year} ${vehicle.make} ${vehicle.model}</p>
                                    <p class="text-text-primary font-mono mt-1 text-sm bg-white/10 border border-card-border rounded px-2 py-1 self-start">${vehicle.plate_number}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <button class="edit-vehicle-btn p-2 rounded-full hover:bg-white/10" data-id="${vehicle.id}"><span class="material-symbols-outlined">edit</span></button>
                                <button class="delete-vehicle-btn p-2 rounded-full hover:bg-white/10" data-id="${vehicle.id}"><span class="material-symbols-outlined">delete</span></button>
                            </div>
                        </div>
                        <div class="border-t border-card-border pt-4 flex-grow">
                            <p class="text-text-muted text-sm font-medium">Known Issues</p>
                            <p class="text-text-primary text-sm mt-1 whitespace-pre-wrap">${vehicle.issues || 'No issues listed.'}</p>
                        </div>
                        <div class="border-t border-card-border pt-4 mt-auto flex items-center gap-2">
                            <button class="view-history-btn w-full flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-white/10 text-text-primary hover:bg-white/20 transition-colors" data-id="${vehicle.id}" data-name="${vehicle.nickname}">
                                <span class="material-symbols-outlined">history</span>
                                Service History
                            </button>
                            <button class="schedule-service-btn w-full flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-primary/10 text-primary hover:bg-primary/20 transition-colors" data-id="${vehicle.id}" data-issues="${vehicle.issues || ''}">
                                <span class="material-symbols-outlined">calendar_today</span>
                                Schedule Service
                            </button>
                        </div>
                    </article>
                `;
                vehicleList.insertAdjacentHTML('beforeend', vehicleCard);
            });
        } else {
            vehicleList.innerHTML = '<p class="text-text-muted col-span-full text-center py-8">You have not added any vehicles yet.</p>';
        }
    }

    function setupVehicleModal() {
        const modal = document.getElementById('vehicle-modal');
        const form = document.getElementById('vehicle-form');
        const title = document.getElementById('vehicle-modal-title');
        const preview = document.getElementById('vehicle-photo-preview');
        const fileInput = document.getElementById('vehicle-photo');

        const openModal = async (vehicleId = null) => {
            form.reset();
            document.getElementById('vehicle-id').value = '';
            preview.src = '../../assets/img/placeholders/placeholder.png';

            if (vehicleId) {
                title.textContent = 'Edit Vehicle';
                const vehicle = await fetchData(`getVehicleDetails&id=${vehicleId}`);
                if (vehicle) {
                    document.getElementById('vehicle-id').value = vehicle.id;
                    document.getElementById('vehicle-nickname').value = vehicle.nickname;
                    document.getElementById('vehicle-plate').value = vehicle.plate_number;
                    document.getElementById('vehicle-make').value = vehicle.make;
                    document.getElementById('vehicle-model').value = vehicle.model;
                    document.getElementById('vehicle-year').value = vehicle.year;
                    document.getElementById('vehicle-vin').value = vehicle.vin;
                    document.getElementById('vehicle-issues').value = vehicle.issues;
                    preview.src = vehicle.car_photo_url;
                    document.getElementById('existing-photo-url').value = currentVehicle.car_photo_url || '';
                    // When opening edit modal
                }
            } else {
                title.textContent = 'Add New Vehicle';
            }
            modal.classList.remove('hidden');
        };

        const closeModal = () => modal.classList.add('hidden');

        document.getElementById('add-vehicle-btn').addEventListener('click', () => openModal());
        document.getElementById('close-vehicle-modal').addEventListener('click', closeModal);
        document.getElementById('upload-vehicle-photo-btn').addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });

        form.addEventListener('submit', handleVehicleSave);
    }

    async function handleVehicleSave(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        Swal.fire({ title: 'Saving...', text: 'Please wait.', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

        try {
            const response = await fetch(`${API_URL}?action=saveVehicle`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                Swal.fire('Success!', result.message, 'success');
                document.getElementById('vehicle-modal').classList.add('hidden');
                loadVehicles();
                loadDashboardStats();
            } else {
                throw new Error(result.message || 'Failed to save vehicle.');
            }
        } catch (error) {
            Swal.fire('Error!', error.message, 'error');
        }
    }

    async function handleVehicleDelete(vehicleId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This vehicle will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${API_URL}?action=deleteVehicle`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: vehicleId })
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Deleted!', 'The vehicle has been removed.', 'success');
                    loadVehicles();
                    loadDashboardStats();
                } else {
                    throw new Error(res.error || 'Failed to delete vehicle.');
                }
            } catch (error) {
                Swal.fire('Error!', error.message, 'error');
            }
        }
    }

    function setupAppointmentModal() {
        const modal = document.getElementById('appointment-modal');
        const form = document.getElementById('appointment-form');
        const vehicleIdInput = document.getElementById('appointment-vehicle-id');
        const notesInput = document.getElementById('appointment-notes');


        const openModal = async (vehicleId, issues) => {
            form.reset();
            vehicleIdInput.value = vehicleId;
            notesInput.value = issues;

            // Fetch services and technicians
            const [services, technicians] = await Promise.all([
                fetchData('getServices'), // Assumes this action exists
                fetchData('getTechnicians') // Assumes this action exists
            ]);

            const serviceSelect = document.getElementById('appointment-service');
            serviceSelect.innerHTML = '<option value="">-- Select a Service --</option>';
            if(services) {
                services.forEach(s => serviceSelect.innerHTML += `<option value="${s.id}">${s.name} - ₱${s.price}</option>`);
            }

            const techSelect = document.getElementById('appointment-technician');
            techSelect.innerHTML = '<option value="">-- Select a Technician --</option>';
            if(technicians) {
                technicians.forEach(t => {
                    techSelect.innerHTML += `<option value="${t.id}">${t.name}${t.specialty ? ` - ${t.specialty}` : ''}</option>`;
                });
            }

            modal.classList.remove('hidden');
        };

        const closeModal = () => modal.classList.add('hidden');

        document.getElementById('close-appointment-modal').addEventListener('click', closeModal);
        form.addEventListener('submit', handleProceedToPayment);

        // Event delegation for dynamically created buttons
        document.getElementById('vehicle-list').addEventListener('click', (e) => {
            const scheduleBtn = e.target.closest('.schedule-service-btn');
            const editBtn = e.target.closest('.edit-vehicle-btn');
            const deleteBtn = e.target.closest('.delete-vehicle-btn');
            const historyBtn = e.target.closest('.view-history-btn');


            if (scheduleBtn) {
                openModal(scheduleBtn.dataset.id, scheduleBtn.dataset.issues);
            }
            if (editBtn) {
                setupVehicleModal(); // Ensure modal is ready
                openVehicleModal(editBtn.dataset.id);
            }
            if (deleteBtn) {
                handleVehicleDelete(deleteBtn.dataset.id);
            }
            if (historyBtn) {
                openHistoryModal(historyBtn.dataset.id, historyBtn.dataset.name);
            }
        });
    }
    
    async function openVehicleModal(vehicleId = null) {
        const modal = document.getElementById('vehicle-modal');
        const form = document.getElementById('vehicle-form');
        const title = document.getElementById('vehicle-modal-title');
        const preview = document.getElementById('vehicle-photo-preview');

        form.reset();
        document.getElementById('vehicle-id').value = '';
        preview.src = '../../assets/img/placeholders/placeholder.png';

        if (vehicleId) {
            title.textContent = 'Edit Vehicle';
            const vehicle = await fetchData(`getVehicleDetails&id=${vehicleId}`);
            if (vehicle) {
                document.getElementById('vehicle-id').value = vehicle.id;
                document.getElementById('vehicle-nickname').value = vehicle.nickname;
                document.getElementById('vehicle-plate').value = vehicle.plate_number;
                document.getElementById('vehicle-make').value = vehicle.make;
                document.getElementById('vehicle-model').value = vehicle.model;
                document.getElementById('vehicle-year').value = vehicle.year;
                document.getElementById('vehicle-vin').value = vehicle.vin;
                document.getElementById('vehicle-issues').value = vehicle.issues;
                preview.src = vehicle.car_photo_url;
                document.getElementById('existing-photo-url').value = vehicle.car_photo_url.replace('../../Backend/', '');
            }
        } else {
            title.textContent = 'Add New Vehicle';
        }
        modal.classList.remove('hidden');
    }

    // Store appointment data temporarily
    let tempAppointmentData = {};

    function handleProceedToPayment(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        tempAppointmentData = Object.fromEntries(formData.entries());

        // Get service name and price for the payment modal
        const serviceSelect = document.getElementById('appointment-service');
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const serviceText = selectedOption.text; // "Service Name - ₱Price"
        const [serviceName, servicePrice] = serviceText.split(' - ');

        document.getElementById('payment-service-name').textContent = serviceName;
        document.getElementById('payment-service-amount').textContent = servicePrice;
        document.getElementById('payment-service-date').textContent = new Date(tempAppointmentData.appointment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

        // Hide appointment modal and show payment modal
        document.getElementById('appointment-modal').classList.add('hidden');
        document.getElementById('service-payment-modal').classList.remove('hidden');
    }

    async function handleAppointmentCreate(event) {
        event.preventDefault();
        const paymentForm = event.target;
        const paymentReference = paymentForm.querySelector('#service-payment-reference').value;
        const gcashNumber = paymentForm.querySelector('#service-gcash-number').value;

        if (!paymentReference || !gcashNumber) {
            Swal.fire({ title: 'Missing Payment Info', text: 'Please provide GCash reference and number.', icon: 'warning', confirmButtonColor: 'var(--primary)' });
            return;
        }

        // Combine appointment data with payment data
        const finalData = {
            ...tempAppointmentData,
            payment_reference: paymentReference,
            gcash_number: gcashNumber
        };

        Swal.fire({ title: 'Booking...', text: 'Please wait.', didOpen: () => Swal.showLoading(), allowOutsideClick: false, background: 'var(--background)', color: 'var(--text-primary)' });

        try {
            const response = await fetch(`${API_URL}?action=createAppointment`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(finalData)
            });
            const result = await response.json();
            if (result.success) {
                Swal.fire({ title: 'Success!', text: result.message, icon: 'success', confirmButtonColor: 'var(--primary)', background: 'var(--background)', color: 'var(--text-primary)' });
                document.getElementById('service-payment-modal').classList.add('hidden');
                loadUpcomingAppointments();
                loadDashboardStats();
                tempAppointmentData = {}; // Clear temp data
            } else {
                throw new Error(result.error || 'Failed to create appointment.');
            }
        } catch (error) {
            Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)', background: 'var(--background)', color: 'var(--text-primary)' });
        }
    }

    async function openHistoryModal(vehicleId, vehicleName) {
        const modal = document.getElementById('history-modal');
        const title = document.getElementById('history-modal-title');
        const tableBody = document.getElementById('history-table-body');

        title.textContent = `Service History for ${vehicleName}`;
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4">Loading...</td></tr>';
        modal.classList.remove('hidden');

        const history = await fetchData(`getVehicleServiceHistory&vehicle_id=${vehicleId}`);
        tableBody.innerHTML = '';

        if (history && history.length > 0) {
            history.forEach(item => {
                tableBody.innerHTML += `<tr class="border-b border-card-border"><td class="px-4 py-2">${new Date(item.appointment_date).toLocaleDateString()}</td><td class="px-4 py-2">${item.service_name}</td><td class="px-4 py-2">${item.technician_name}</td><td class="px-4 py-2 text-right">₱${parseFloat(item.amount).toFixed(2)}</td></tr>`;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-text-muted">No completed service history for this vehicle.</td></tr>';
        }
    }

    // --- NOTIFICATIONS ---
    function setupNotifications() {
        const headerBell = document.getElementById('header-notification-bell');
        const headerPanel = document.getElementById('header-notification-panel');
        const headerList = document.getElementById('header-notification-list');
        const headerBadge = document.getElementById('header-notification-badge');
        const sidebarBadge = document.getElementById('sidebar-notification-badge');
        const markAllReadBtn = document.getElementById('header-mark-all-read-btn');
        let unreadIds = [];

        async function loadNotifications() {
            const notifications = await fetchData('getNotifications');
            const list = document.getElementById('notification-list');
            list.innerHTML = ''; // Clear existing notifications to prevent duplication
            unreadIds = [];
            let unreadCount = 0;
            
            headerList.innerHTML = ''; // Clear header dropdown list
            if (notifications && notifications.length > 0) {
                notifications.forEach(n => {
                    if (n.is_read == 0) {
                        unreadCount++;
                        unreadIds.push(n.id);
                    }
                    const timeAgo = formatTimeAgo(n.created_at);
                    const item = document.createElement('a');
                    item.href = '#'; // Links are handled by section navigation now
                    item.className = `block p-4 hover:bg-white/5 ${n.is_read == 0 ? 'bg-primary/5' : ''}`;
                    item.innerHTML = `
                        <div class="flex justify-between items-start">
                            <p class="font-semibold text-text-primary text-sm">${n.title}</p>
                            ${n.is_read == 0 ? '<div class="size-2 bg-primary rounded-full flex-shrink-0 mt-1.5 ml-2"></div>' : ''}
                        </div>
                        <p class="text-text-muted text-sm mt-1 pr-4">${n.message}</p>
                        <p class="text-xs text-text-muted/70 mt-2">${timeAgo}</p>
                    `;
                    // Append to both the main section list and the header dropdown
                    list.appendChild(item);
                    headerList.appendChild(item.cloneNode(true));
                });
            } else {
                list.innerHTML = '<p class="text-center text-text-muted p-4">No notifications yet.</p>';
                headerList.innerHTML = '<p class="text-center text-text-muted p-4">No notifications yet.</p>';
            }

            headerBadge.textContent = unreadCount;
            sidebarBadge.textContent = unreadCount;
            headerBadge.classList.toggle('hidden', unreadCount === 0);
            sidebarBadge.classList.toggle('hidden', unreadCount === 0);
        }

        async function markAsRead(ids) {
            if (ids.length === 0) return;
            await fetch(`${API_URL}?action=markNotificationsRead`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: ids })
            });
            loadNotifications();
        }

        markAllReadBtn.addEventListener('click', () => markAsRead(unreadIds));

        // New logic for header dropdown
        headerBell.addEventListener('click', (e) => {
            e.stopPropagation();
            headerPanel.classList.toggle('hidden');
            if (!headerPanel.classList.contains('hidden')) {
                // Mark as read after a short delay when opening
                setTimeout(() => markAsRead(unreadIds), 2000);
            }
        });

        document.addEventListener('click', (e) => {
            if (!headerPanel.contains(e.target) && !headerBell.contains(e.target)) {
                headerPanel.classList.add('hidden');
            }
        });


        loadNotifications();
        setInterval(loadNotifications, 60000);
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString + ' UTC');
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = seconds / 31536000; if (interval > 1) return Math.floor(interval) + " years ago";
        interval = seconds / 2592000; if (interval > 1) return Math.floor(interval) + " months ago";
        interval = seconds / 86400; if (interval > 1) return Math.floor(interval) + " days ago";
        interval = seconds / 3600; if (interval > 1) return Math.floor(interval) + " hours ago";
        interval = seconds / 60; if (interval > 1) return Math.floor(interval) + " minutes ago";
        return "Just now";
    }

    // --- APPOINTMENTS ---
    async function loadUpcomingAppointments() {
        const appointments = await fetchData('getUpcomingAppointments');
        const appointmentsList = document.getElementById('appointments-list');
        appointmentsList.innerHTML = ''; // Clear placeholder

        if (appointments && appointments.length > 0) {
            appointments.forEach(appt => {
                const appointmentDate = new Date(`${appt.appointment_date}T${appt.appointment_time}`);
                const formattedDate = appointmentDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
                const formattedTime = appointmentDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

                let statusBadgeClass = '';
                let statusText = '';
                switch (appt.status) {
                    case 'confirmed':
                        statusBadgeClass = 'bg-primary/20 text-primary';
                        statusText = 'CONFIRMED';
                        break;
                    case 'pending':
                        statusBadgeClass = 'bg-yellow-500/20 text-yellow-400';
                        statusText = 'PENDING';
                        break;
                    case 'in_progress':
                        statusBadgeClass = 'bg-cyan-500/20 text-cyan-400';
                        statusText = 'IN PROGRESS';
                        break;
                    default:
                        statusBadgeClass = 'bg-gray-500/20 text-gray-400';
                        statusText = appt.status.toUpperCase();
                }

                const appointmentCard = `
                    <article class="flex flex-col md:flex-row items-stretch justify-between gap-6 rounded-xl glass-card border p-6 shadow-sm">
                        <div class="flex flex-1 flex-col gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center size-12 rounded-lg bg-primary/10 text-primary" aria-hidden="true">
                                    <span class="material-symbols-outlined text-3xl">calendar_month</span>
                                </div>
                                <div>
                                    <p class="text-text-primary text-lg font-bold leading-tight">${formattedDate}</p>
                                    <p class="text-text-muted text-sm font-normal leading-normal">at ${formattedTime}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 pt-2 border-t border-card-border">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-text-muted" aria-hidden="true">build</span>
                                    <div>
                                        <p class="text-text-muted text-xs font-normal">Service</p>
                                        <p class="text-text-primary text-sm font-medium truncate">${appt.service_name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-text-muted" aria-hidden="true">engineering</span>
                                    <div>
                                        <p class="text-text-muted text-xs font-normal">Technician</p>
                                        <p class="text-text-primary text-sm font-medium truncate">${appt.technician_name}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 flex flex-wrap items-center gap-3">
                                ${appt.status !== 'cancelled' ? `
                                <button class="cancel-appointment-btn flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-transparent text-text-muted text-sm font-medium leading-normal hover:bg-red-500/10 hover:text-red-400" aria-label="Cancel Appointment" data-id="${appt.id}">
                                    Cancel
                                </button>
                                ` : ''}
                            </div>
                        </div>
                        <div class="relative w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-black/20 rounded-lg">
                             <div class="absolute top-3 right-3 flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ${statusBadgeClass}">
                                <span class="material-symbols-outlined text-sm" aria-hidden="true">${appt.status === 'confirmed' ? 'check_circle' : (appt.status === 'in_progress' ? 'sync' : 'hourglass_top')}</span>
                                <span>${statusText}</span>
                            </div>
                            <p class="text-text-muted text-sm">${appt.vehicle_name || 'Vehicle not specified'}</p>
                        </div>
                    </article>
                `;
                appointmentsList.insertAdjacentHTML('beforeend', appointmentCard);
            });
        } else {
            appointmentsList.innerHTML = '<div class="glass-card rounded-xl border p-6 text-center text-text-muted"><p>You have no upcoming appointments.</p></div>';
        }
    }

    function setupAppointmentCancellation() {
        const button = event.target.closest('.cancel-appointment-btn');
        if (!button) return;

        const appointmentId = button.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this. Your payment will be refunded within 3-5 business days.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            background: 'var(--background)',
            color: 'var(--text-primary)'
        }).then((result) => {
            if (result.isConfirmed) {
                cancelAppointmentRequest(appointmentId);
            }
        });
    }

    async function cancelAppointmentRequest(appointmentId) {
        try {
            const response = await fetch(`${API_URL}?action=cancelAppointment`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: appointmentId })
            });
            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Your appointment has been cancelled.',
                    icon: 'success',
                    background: 'var(--background)',
                    color: 'var(--text-primary)'
                });
                loadUpcomingAppointments(); // Refresh the list
                loadDashboardStats(); // Refresh stats
            } else {
                throw new Error(result.error || 'Failed to cancel appointment.');
            }
        } catch (error) {
            console.error('Error cancelling appointment:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message,
                icon: 'error',
                background: 'var(--background)',
                color: 'var(--text-primary)'
            });
        }
    }

    // --- SERVICE HISTORY ---
    async function loadServiceHistory() {
        const history = await fetchData('getServiceHistory');
        const tableBody = document.querySelector('#service-history-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        if (!history) {
            // Show skeleton loader
            tableBody.innerHTML = Array(4).fill('').map(() => `
                <tr class="border-b border-card-border animate-pulse">
                    <td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-3/4"></div></td><td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-1/2"></div></td><td class="px-4 py-4 text-right"><div class="h-4 bg-slate-700/20 rounded w-1/4 ml-auto"></div></td>
                </tr>`).join('');
            return;
        }
        if (history && history.length > 0) {
            history.forEach(item => {
                const serviceDate = new Date(item.appointment_date);
                const formattedDate = serviceDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                const cost = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(item.amount);

                const row = `
                    <tr class="border-b border-card-border hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium text-text-primary">${item.service_name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</div>
                            <div class="text-text-muted text-xs">${formattedDate}</div>
                        </td>
                        <td class="px-4 py-3 text-text-muted">${item.vehicle_name || item.vehicle_model}</td>
                        <td class="px-4 py-3 text-right font-mono text-text-primary">${cost}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-text-muted">No completed services found.</td></tr>';
        }
    }

    function setupServiceHistorySearch() {
        const searchInput = document.getElementById('service-history-search');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#service-history-table tbody tr');
            rows.forEach(row => {
                const serviceName = row.cells[0].textContent.toLowerCase();
                const vehicleName = row.cells[1].textContent.toLowerCase();
                if (serviceName.includes(searchTerm) || vehicleName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // --- DASHBOARD STATS ---
    async function loadDashboardStats() {
        const stats = await fetchData('getCustomerDashboardStats');
        if (stats) {
            // Upcoming Appointments
            const upcomingCount = stats.upcoming_appointments || 0;
            document.getElementById('dashboard-upcoming-appointments').textContent = upcomingCount;

            // Pending Payments
            const pendingAmount = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(stats.pending_payments || 0);
            document.getElementById('dashboard-pending-payments').textContent = pendingAmount;

            // Vehicles Registered
            const vehicleCount = stats.vehicles_registered || 0;
            document.getElementById('dashboard-vehicles-registered').textContent = vehicleCount;
        }
    }
    // --- PROFILE MANAGEMENT ---
    async function handleProfileUpdate(event) {
        event.preventDefault(); // Prevent default form submission
        const formData = new FormData(event.target);

        Swal.fire({
            title: 'Updating Profile...',
            text: 'Please wait.',
            didOpen: () => { Swal.showLoading() },
            allowOutsideClick: false
        });

        // Convert FormData to a plain JavaScript object
        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });

        try {
            const response = await fetch(`${API_URL}?action=updateProfile`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Set content type to JSON
                },
                body: JSON.stringify(formObject) // Send data as a JSON string
            });

            // Check if the response is valid JSON before parsing
            const result = await response.json();

            if (result.success) {
                Swal.fire({ title: 'Success!', text: result.message, icon: 'success', confirmButtonColor: 'var(--primary)' });
                loadUserDetails(); // Refresh user details in the sidebar
                // Clear password fields
                document.getElementById('current-password').value = '';
                document.getElementById('new-password').value = '';
                document.getElementById('confirm-password').value = '';
            } else {
                throw new Error(result.error || 'An unknown error occurred.');
            }
        } catch (error) {
            Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
        }
    }

    function setupProfilePictureUpload() {
        const uploadBtn = document.getElementById('upload-pic-btn');
        const fileInput = document.getElementById('profile-picture-upload');
        const previewImg = document.getElementById('profile-pic-preview');

        uploadBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', async () => {
            const file = fileInput.files[0];
            if (!file) return;

            // Show preview
            const reader = new FileReader();
            reader.onload = e => { previewImg.src = e.target.result; };
            reader.readAsDataURL(file);

            // Upload the file
            const formData = new FormData();
            formData.append('profile_picture', file);

            try {
                const response = await fetch(`${API_URL}?action=uploadProfilePicture`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    Swal.fire({ title: 'Success!', text: 'Profile picture updated.', icon: 'success', confirmButtonColor: 'var(--primary)' });
                    // Refresh user details to show new picture everywhere
                    loadUserDetails();
                } else {
                    throw new Error(result.error || 'Failed to upload picture.');
                }
            } catch (error) {
                Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
            }
        });
    }

    // --- PURCHASE NOW MODAL ---
    function openPurchaseNowModal(part) {
        const modal = document.getElementById('purchase-now-modal');
        document.getElementById('purchase-part-image').src = part.image;
        document.getElementById('purchase-part-name').textContent = part.name;
        document.getElementById('purchase-part-price').textContent = `₱${parseFloat(part.price).toFixed(2)}`;
        document.getElementById('purchase-part-id').value = part.id;
        document.getElementById('purchase-part-stock').textContent = part.stock; // New line for stock
        
        const quantityInput = document.getElementById('purchase-quantity');
        quantityInput.value = 1;
        quantityInput.max = part.stock;
        
        updatePurchaseTotal(part.price, 1);

        modal.classList.remove('hidden');
    }

    function updatePurchaseTotal(price, quantity) {
        const total = parseFloat(price) * parseInt(quantity);
        document.getElementById('purchase-total-amount').textContent = `₱${total.toFixed(2)}`;
    }

    async function handlePurchaseNow(event) {
        event.preventDefault();
        const form = event.target;
        const partId = form.querySelector('#purchase-part-id').value;
        const quantity = parseInt(form.querySelector('#purchase-quantity').value);
        const availableStock = parseInt(document.getElementById('purchase-part-stock').textContent);
        const paymentMethod = 'GCash';
        const paymentReference = form.querySelector('#purchase-payment-reference').value;
        const gcashNumber = form.querySelector('#purchase-gcash-number').value;

        if (quantity <= 0) {
            Swal.fire({ title: 'Invalid Quantity', text: 'Please enter a quantity greater than 0.', icon: 'warning', confirmButtonColor: 'var(--primary)' });
            return;
        }

        if (quantity > availableStock) {
            Swal.fire({ title: 'Insufficient Stock', text: `The requested quantity (${quantity}) exceeds available stock (${availableStock}).`, icon: 'warning', confirmButtonColor: 'var(--primary)' });
            return;
        }

        Swal.fire({
            title: 'Processing Purchase...',
            text: 'Please wait.',
            didOpen: () => { Swal.showLoading() },
            allowOutsideClick: false
        });

        try {
            const response = await fetch(`${API_URL}?action=checkout`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    payment_method: paymentMethod,
                    items: [{ product_id: partId, quantity: quantity }], // Send as an array of items
                    payment_reference: paymentReference,
                    gcash_number: gcashNumber
                })
            });
            const res = await response.json();
            if (res.success) {
                Swal.fire({ title: 'Order Placed!', text: res.message, icon: 'success', confirmButtonColor: 'var(--primary)' });
                document.getElementById('purchase-now-modal').classList.add('hidden');
                loadPartsStore(storeCurrentPage, storeSearchQuery);
                loadNewParts();
                loadMyInvoices();
            } else { throw new Error(res.error || 'Purchase failed.'); }
        } catch (error) { Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' }); }
    }

    async function handleCheckout() {
        // This function will now open the checkout modal instead of direct processing
        const cartItems = await fetchData('getCartItems');
        if (!cartItems || cartItems.length === 0) {
            Swal.fire({ title: 'Empty Cart', text: 'Your cart is empty.', icon: 'info', confirmButtonColor: 'var(--primary)' });
            return;
        }

        // For simplicity, we can reuse the "Purchase Now" modal and adapt it for the whole cart.
        // Or create a dedicated checkout page/modal. Let's adapt the existing one for now.
        // This example will proceed with the original simple checkout logic.
        // A more advanced implementation would involve a dedicated checkout form similar to the new Purchase Now modal.
        
        // For now, let's keep the simple checkout for the main cart, 
        // but the "Purchase Now" has the advanced form.
        // The backend is ready for both.
        
        // To make the main cart checkout also use the new form, you would:
        // 1. Open a modal pre-filled with all cart items.
        // 2. Collect GCash details in that modal.
        // 3. Submit all items and GCash details to the 'checkout' endpoint.
    }

    // --- CART FUNCTIONS ---
    async function loadCartCount() {
        const response = await fetchData('getCartCount');
        const cartCountBadge = document.getElementById('cart-count-badge');
        if (response && response.total_items !== undefined) {
            cartCountBadge.textContent = response.total_items;
            cartCountBadge.classList.toggle('hidden', response.total_items === 0);
        } else {
            cartCountBadge.classList.add('hidden');
        }
    }

    async function loadCartItems() {
        const cartItems = await fetchData('getCartItems');
        const cartItemsList = document.getElementById('cart-items-list');
        const cartTotalAmount = document.getElementById('cart-total-amount');
        cartItemsList.innerHTML = '';
        let total = 0;

        if (cartItems && cartItems.length > 0) {
            cartItems.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                const cartItemHtml = `
                    <div class="flex items-center gap-4 p-3 border border-card-border rounded-lg">
                        <img src="${item.image_url}" alt="${item.part_name}" class="size-16 object-cover rounded-md bg-black/20">
                        <div class="flex-1">
                            <h3 class="font-semibold text-text-primary">${item.part_name}</h3>
                            <p class="text-text-muted text-sm">₱${parseFloat(item.price).toFixed(2)} x ${item.quantity}</p>
                            <p class="text-text-muted text-xs">Available: ${item.stock_level}</p>
                            ${item.quantity > item.stock_level ? `<p class="text-red-500 text-xs font-semibold mt-1">Quantity exceeds available stock!</p>` : ''}
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="update-quantity-btn text-text-muted hover:text-primary" data-id="${item.cart_item_id}" data-product-id="${item.product_id}" data-quantity="${item.quantity - 1}" ${item.quantity <= 1 ? 'disabled' : ''}>
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <span class="font-medium text-text-primary">${item.quantity}</span>
                            <button class="update-quantity-btn text-text-muted hover:text-primary" data-id="${item.cart_item_id}" data-product-id="${item.product_id}" data-quantity="${item.quantity + 1}" ${item.quantity >= item.stock_level ? 'disabled' : ''}>
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                        <button class="remove-from-cart-btn text-red-500 hover:text-red-700" data-id="${item.cart_item_id}">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                `;
                cartItemsList.insertAdjacentHTML('beforeend', cartItemHtml);
            });
        } else {
            cartItemsList.innerHTML = '<p class="text-center text-text-muted p-4">Your cart is empty.</p>';
        }
        cartTotalAmount.textContent = `₱${total.toFixed(2)}`;
    }

    async function updateCartItemQuantity(cartItemId, productId, newQuantity) {
        try {
            const response = await fetch(`${API_URL}?action=updateCartItemQuantity`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_item_id: cartItemId, product_id: productId, quantity: newQuantity })
            });
            const res = await response.json();
            if (res.success) {
                loadCartItems();
                loadCartCount();
            } else {
                throw new Error(res.error || 'Failed to update quantity.');
            }
        } catch (error) {
            Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
        }
    }

    async function removeCartItem(cartItemId) {
        try {
            const response = await fetch(`${API_URL}?action=removeCartItem`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_item_id: cartItemId })
            });
            const res = await response.json();
            if (res.success) {
                loadCartItems();
                loadCartCount();
            } else {
                throw new Error(res.error || 'Failed to remove item.');
            }
        } catch (error) {
            Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
        }
    }

    async function handleFinalCheckout(event) {
        event.preventDefault();
        const gcashNumber = document.getElementById('checkout-gcash-number').value;
        const paymentReference = document.getElementById('checkout-payment-reference').value;

        if (!gcashNumber || !paymentReference) {
            Swal.fire({ title: 'Missing Information', text: 'Please provide both your GCash number and the payment reference number.', icon: 'warning', confirmButtonColor: 'var(--primary)' });
            return;
        }

        Swal.fire({
            title: 'Processing Order...',
            text: 'Please wait while we finalize your order.',
            didOpen: () => { Swal.showLoading() },
            allowOutsideClick: false
        });

        try {
            const response = await fetch(`${API_URL}?action=checkout`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    payment_method: 'GCash',
                    gcash_number: gcashNumber,
                    payment_reference: paymentReference
                    // Note: We are not sending items. The backend will process the user's whole cart.
                })
            });
            const res = await response.json();
            if (res.success) {
                Swal.fire({ title: 'Order Placed!', text: res.message, icon: 'success', confirmButtonColor: 'var(--primary)' });
                document.getElementById('checkout-modal').classList.add('hidden'); // Close checkout modal
                loadCartCount();
                loadCartItems(); // This will now show an empty cart
                loadMyInvoices(); // Refresh invoices to show the new one
                loadPartsStore(storeCurrentPage, storeSearchQuery); // Refresh stock levels
                loadNewParts(); // Refresh stock levels
            } else {
                throw new Error(res.error || 'Checkout failed.');
            }
        } catch (error) {
            Swal.fire({ title: 'Error!', text: error.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
        }
    }

    async function handleCheckout() {
        // This function is now primarily for opening the checkout modal.
        // The actual submission is handled by handleFinalCheckout.
        const totalText = document.getElementById('cart-total-amount').textContent;
        if (parseFloat(totalText.replace('₱', '')) <= 0) {
            Swal.fire({ title: 'Empty Cart', text: 'Please add items to your cart before checking out.', icon: 'info', confirmButtonColor: 'var(--primary)' });
            return;
        }
        document.getElementById('checkout-total-amount').textContent = totalText;
        document.getElementById('cart-modal').classList.add('hidden');
        document.getElementById('checkout-modal').classList.remove('hidden');
    }

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        // Initial data loads
        const initialLoad = () => {
            loadUserDetails();
            loadVehicles();
            loadUpcomingAppointments();
            loadDashboardStats();
            loadServiceHistory();
            loadNewParts();
            loadServiceRecommendations();
            loadMyInvoices();
            loadPartsStore();
            loadCartCount(); // Load cart count on initial load
        };

        initialLoad();

        // Setup interactive elements
        setupVehicleModal();
        setupAppointmentModal();
        setupNotifications();
        setupProfilePictureUpload();
        setupServiceHistorySearch();
        setupInvoicesSearch();
        setupPartsSearch();
        
        // New Modal Listeners
        document.getElementById('close-history-modal').addEventListener('click', () => document.getElementById('history-modal').classList.add('hidden'));
        document.getElementById('close-service-payment-modal').addEventListener('click', () => document.getElementById('service-payment-modal').classList.add('hidden'));
        document.getElementById('service-payment-form').addEventListener('submit', handleAppointmentCreate);



        // Event Listeners (Delegation)
        document.getElementById('appointments-list').addEventListener('click', setupAppointmentCancellation);
        document.getElementById('profile-update-form').addEventListener('submit', handleProfileUpdate);
        document.getElementById('new-parts-list').addEventListener('click', handleAddToCart);
        document.getElementById('parts-store-list').addEventListener('click', handleAddToCart);
        document.getElementById('parts-store-list').addEventListener('click', (e) => {
            const purchaseBtn = e.target.closest('.purchase-now-btn');
            if (purchaseBtn) {
                openPurchaseNowModal({
                    id: purchaseBtn.dataset.id,
                    name: purchaseBtn.dataset.name,
                    price: purchaseBtn.dataset.price,
                    stock: purchaseBtn.dataset.stock,
                    image: purchaseBtn.dataset.image,
                });
            }
        });



        // Cart Modal Event Listeners
        const cartModal = document.getElementById('cart-modal');
        const cartSidebarBtn = document.getElementById('cart-sidebar-btn');
        const closeCartModalBtn = document.getElementById('close-cart-modal');
        const checkoutBtn = document.getElementById('checkout-btn');
        const cartItemsList = document.getElementById('cart-items-list');

        // New Checkout Modal Elements
        const checkoutModal = document.getElementById('checkout-modal');
        const closeCheckoutModalBtn = document.getElementById('close-checkout-modal');
        const checkoutForm = document.getElementById('checkout-form');
        const checkoutTotalAmount = document.getElementById('checkout-total-amount');


        // Purchase Now Modal Listeners
        const purchaseModal = document.getElementById('purchase-now-modal');
        const closePurchaseModalBtn = document.getElementById('close-purchase-now-modal');
        const purchaseForm = document.getElementById('purchase-now-form');
        const purchaseQuantityInput = document.getElementById('purchase-quantity');

        closePurchaseModalBtn.addEventListener('click', () => purchaseModal.classList.add('hidden'));
        purchaseForm.addEventListener('submit', handlePurchaseNow);
        purchaseQuantityInput.addEventListener('input', (e) => {
            const price = parseFloat(document.getElementById('purchase-part-price').textContent.replace('₱', ''));
            const currentQuantity = parseInt(e.target.value);
            const availableStock = parseInt(document.getElementById('purchase-part-stock').textContent);
            const warningMessage = document.getElementById('purchase-quantity-warning');
            const confirmPayBtn = purchaseForm.querySelector('button[type="submit"]');

            if (currentQuantity > availableStock) {
                warningMessage.classList.remove('hidden');
                confirmPayBtn.disabled = true;
                confirmPayBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                warningMessage.classList.add('hidden');
                confirmPayBtn.disabled = false;
                confirmPayBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            updatePurchaseTotal(price, currentQuantity);
        });



        cartSidebarBtn.addEventListener('click', () => {
            cartModal.classList.remove('hidden');
            loadCartItems(); // Load items when modal opens
        });

        closeCartModalBtn.addEventListener('click', () => {
            cartModal.classList.add('hidden');
        });

        cartModal.addEventListener('click', (e) => {
            if (e.target === cartModal) { // Close if clicked outside the modal content
                cartModal.classList.add('hidden');
            }
        });

        // MODIFIED: This now opens the new checkout modal
        checkoutBtn.addEventListener('click', () => {
            const totalText = document.getElementById('cart-total-amount').textContent;
            if (parseFloat(totalText.replace('₱', '')) <= 0) {
                Swal.fire({ title: 'Empty Cart', text: 'Please add items to your cart before checking out.', icon: 'info', confirmButtonColor: 'var(--primary)' });
                return;
            }
            checkoutTotalAmount.textContent = totalText;
            cartModal.classList.add('hidden');
            checkoutModal.classList.remove('hidden');
        });

        // New listeners for the checkout modal
        closeCheckoutModalBtn.addEventListener('click', () => checkoutModal.classList.add('hidden'));
        checkoutForm.addEventListener('submit', handleFinalCheckout);


        cartItemsList.addEventListener('click', (e) => {
            const updateBtn = e.target.closest('.update-quantity-btn');
            if (updateBtn) {
                const cartItemId = updateBtn.dataset.id;
                const productId = updateBtn.dataset.productId;
                const newQuantity = parseInt(updateBtn.dataset.quantity);
                updateCartItemQuantity(cartItemId, productId, newQuantity);
            }

            const removeBtn = e.target.closest('.remove-from-cart-btn');
            if (removeBtn) {
                const cartItemId = removeBtn.dataset.id;
                Swal.fire({
                    title: 'Remove item?',
                    text: "Are you sure you want to remove this item from your cart?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it!',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    background: 'var(--background)',
                    color: 'var(--text-primary)'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeCartItem(cartItemId);
                    }
                });
            }
        });
    });


  </script>

  <!-- Cart Modal Structure -->
  <div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden animate-fade-in">
    <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] flex flex-col">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-text-primary">Your Cart</h2>
        <button id="close-cart-modal" class="text-text-muted hover:text-primary">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <div id="cart-items-list" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
        <!-- Cart items will be loaded here -->
      </div>
      <div id="cart-summary" class="border-t border-card-border pt-4 mt-4">
        <div class="flex justify-between items-center text-lg font-bold text-text-primary mb-4">
          <span>Total:</span>
          <span id="cart-total-amount">₱0.00</span>
        </div>
        <button id="checkout-btn" class="w-full flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-primary text-background text-base font-medium leading-normal hover:bg-primary-dark transition-colors">
          <span class="material-symbols-outlined">payment</span>
          Proceed to Checkout
        </button>
      </div>
    </div>
  </div>

  <!-- Checkout Modal -->
<div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden animate-fade-in">
    <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-text-primary">Complete Your Order</h2>
            <button id="close-checkout-modal" class="text-text-muted hover:text-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="checkout-form" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
            <div class="p-4 border border-card-border rounded-lg">
                <h3 class="text-lg font-semibold text-text-primary mb-4">GCash Payment</h3>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="text-center">
                        <img src="../../assets/img/placeholders/placeholder.png" alt="GCash QR Code" class="size-36 rounded-lg bg-white p-1">
                        <p class="text-xs text-text-muted mt-2">Scan to pay</p>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <label for="checkout-gcash-number" class="block text-sm font-medium text-text-muted">Your GCash Number</label>
                            <input type="text" id="checkout-gcash-number" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" placeholder="09xxxxxxxxx">
                        </div>
                        <div>
                            <label for="checkout-payment-reference" class="block text-sm font-medium text-text-muted">GCash Reference No.</label>
                            <input type="text" id="checkout-payment-reference" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" placeholder="13-digit reference number">
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-card-border pt-4 mt-4">
                <div class="flex justify-between items-center text-xl font-bold text-text-primary mb-4">
                    <span>Total:</span>
                    <span id="checkout-total-amount">₱0.00</span>
                </div>
                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg h-12 px-5 bg-primary text-background text-base font-medium leading-normal hover:bg-primary-dark transition-colors">
                    <span class="material-symbols-outlined">payment</span>
                    Confirm and Pay
                </button>
            </div>
        </form>
    </div>
</div>

  <!-- Purchase Now Modal -->
    <div id="purchase-now-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden animate-fade-in">
        <div class="glass-card rounded-xl border p-6 w-11/12 md:w-2/3 lg:w-1/2 max-w-3xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-text-primary">Complete Your Purchase</h2>
                <button id="close-purchase-now-modal" class="text-text-muted hover:text-primary">
                    <span class="material-symbols-outlined">close</span> 
                </button>
            </div>
            <form id="purchase-now-form" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                <input type="hidden" id="purchase-part-id">
                <div class="flex items-start gap-6 p-4 border border-card-border rounded-lg">
                    <img id="purchase-part-image" src="" alt="Part Image" class="size-24 object-cover rounded-md bg-black/20">
                    <div class="flex-1">
                        <h3 id="purchase-part-name" class="text-xl font-semibold text-text-primary">Part Name</h3>
                        <p id="purchase-part-price" class="text-lg font-bold text-primary">₱0.00</p>
                        <div class="mt-4">
                            <label for="purchase-quantity" class="block text-sm font-medium text-text-muted">Quantity</label>
                            <input type="number" id="purchase-quantity" min="1" value="1" class="w-24 mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary">
                            <span id="purchase-part-stock-display" class="text-text-muted text-xs ml-2">Available: <span id="purchase-part-stock"></span></span>
                            <p id="purchase-quantity-warning" class="text-red-500 text-xs font-semibold mt-1 hidden">Quantity exceeds available stock!</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 border border-card-border rounded-lg">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">GCash Payment</h3>
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="text-center">
                            <img src="../../assets/img/placeholders/placeholder.png" alt="GCash QR Code" class="size-36 rounded-lg bg-white p-1">
                            <p class="text-xs text-text-muted mt-2">Scan to pay</p>
                        </div>
                        <div class="flex-1 space-y-4">
                            <div>
                                <label for="purchase-gcash-number" class="block text-sm font-medium text-text-muted">Your GCash Number</label>
                                <input type="text" id="purchase-gcash-number" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" placeholder="09xxxxxxxxx">
                            </div>
                            <div><label for="purchase-payment-reference" class="block text-sm font-medium text-text-muted">GCash Reference No.</label>
                                <input type="text" id="purchase-payment-reference" required class="w-full mt-1 rounded-lg h-10 px-3 bg-white/5 border-card-border text-text-primary text-sm font-normal focus:ring-primary focus:border-primary" placeholder="13-digit reference number"></div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-card-border pt-4 mt-4">
                    <div class="flex justify-between items-center text-xl font-bold text-text-primary mb-4">
                        <span>Total:</span>
                        <span id="purchase-total-amount">₱0.00</span>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg h-12 px-5 bg-primary text-background text-base font-medium leading-normal hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined">payment</span>
                        Confirm and Pay
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html></div>
    </div>
</body>

</html>