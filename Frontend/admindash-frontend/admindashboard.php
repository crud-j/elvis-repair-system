<?php
session_start();
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Admin Dashboard - Unified Interface</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    *,
    *::before,
    *::after {
      transition-property: background-color, border-color, color, fill, stroke, transform;
      transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
      transition-duration: 300ms;
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
            "primary": {
              DEFAULT: "#00DF81",
              dark: "#22CC95"
            },
            "background": "var(--background)",
            "text-primary": "var(--text-primary)",
            "text-muted": "var(--text-muted)",
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.5rem",
            "lg": "0.75rem",
            "xl": "1rem",
            "full": "9999px"
          },
          transitionTimingFunction: {
            'ease-in-out': 'cubic-bezier(0.4, 0, 0.2, 1)',
          },
          boxShadow: {
            'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'modal': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
          },
          animation: {
            'fade-in': 'fadeIn 0.3s ease-out',
            'slide-up': 'slideUp 0.3s ease-out'
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' }
            },
            slideUp: {
              '0%': { transform: 'translateY(20px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' }
            }
          }
        },
      },
    }
  </script>
  <script>
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
      if (!('theme' in localStorage)) {
        localStorage.setItem('theme', 'dark');
      }
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
</head>

<body class="bg-background font-display text-text-primary flex min-h-screen overflow-hidden transition-colors duration-300 antialiased">
  <!-- Background Glows -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-primary/20 dark:bg-primary/10 rounded-full blur-[160px] animate-pulse"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/20 dark:bg-primary/10 rounded-full blur-[160px] animate-pulse [animation-delay:-3s]"></div>

  <!-- Sidebar -->
  <aside class="w-64 flex-shrink-0 bg-sidebar-bg backdrop-blur-lg flex flex-col justify-between p-4 border-r border-card-border lg:translate-x-0 transition-transform duration-300 overflow-y-auto custom-scrollbar" id="sidebar">
    <div class="flex flex-col gap-8">
      <div class="flex items-center gap-2 px-2">
        <span class="material-symbols-outlined text-primary text-3xl" aria-hidden="true">
          directions_car
        </span>
        <h2 class="text-xl font-bold text-text-primary">Admin Dashboard</h2>
      </div>
      <nav class="flex flex-col gap-2" aria-label="Sidebar navigation">
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 text-primary font-medium" data-section="dashboard" aria-current="page" aria-label="Dashboard">
          <span class="material-symbols-outlined">dashboard</span>
          <p class="text-sm">Dashboard</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="bookings" aria-label="Bookings">
          <span class="material-symbols-outlined">calendar_month</span>
          <p class="text-sm font-medium">Bookings</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="services" aria-label="Services">
          <span class="material-symbols-outlined">construction</span>
          <p class="text-sm font-medium">Services</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="inventory" aria-label="Inventory">
          <span class="material-symbols-outlined">inventory_2</span>
          <p class="text-sm font-medium">Inventory</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="orders" aria-label="Orders">
          <span class="material-symbols-outlined">shopping_cart</span>
          <p class="text-sm font-medium">Part Orders</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="reports" aria-label="Reports">
          <span class="material-symbols-outlined">bar_chart</span>
          <p class="text-sm font-medium">Reports</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="schedule" aria-label="Schedule">
          <span class="material-symbols-outlined">calendar_today</span>
          <p class="text-sm font-medium">Schedule</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="login-history" aria-label="Login History">
          <span class="material-symbols-outlined">history</span>
          <p class="text-sm font-medium">Login History</p>
        </button>
        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" data-section="contacts" aria-label="Contacts">
          <span class="material-symbols-outlined">contact_mail</span>
          <p class="text-sm font-medium">Contacts</p>
        </button>
      </nav>
    </div>
    <div class="flex flex-col gap-2">
      <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-text-muted" aria-label="Settings" data-section="settings">
        <span class="material-symbols-outlined">settings</span>
        <p class="text-sm font-medium">Settings</p>
      </button>
      <div class="flex items-center gap-3 p-2 mt-2 border-t border-card-border">
        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" aria-hidden="true" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBgm1nDsksSVcVp5PiS1q3Pus_JCyHbhKjL31g-5uwxQ07cpio89oYQs83bSLXGJPfUSgAq-t1m78n3wzvoStU4Er9R_4SKmshlw7614qt_lTEMyBZMmHh5hkN2L3cq0gCPlLUmSydGPGldJqDcQj6L7mWE5vNUNRZpIJv4EcnZwb9hjjyELQUhwfn02gjqcskSemIAKCfcO6-9IaveJXdmY253At6RRCIEj-UQYWUKfnnYSZoRW_3IGMBNqQdLcXdfQdhPxNd13gU");'></div>
        <div class="flex flex-col flex-1">
          <h1 id="admin-name" class="text-text-primary text-sm font-medium leading-normal">Loading...</h1>
          <p id="admin-email" class="text-text-muted text-xs font-normal leading-normal">Loading...</p>
        </div>
        <a href="../../Backend/logout.php" class="text-text-muted hover:text-text-primary" aria-label="Logout">
          <span class="material-symbols-outlined">logout</span>
        </a>
      </div>
    </div>
  </aside>

  <!-- Sidebar Toggle for Mobile -->
  <button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-primary text-background rounded-lg">
    <span class="material-symbols-outlined">menu</span>
  </button>

  <!-- Main content area -->
  <main class="flex-1 p-4 lg:p-8 overflow-y-auto h-screen bg-background text-text-primary ml-0 lg:ml-0 transition-all duration-300">
    <!-- Sections container -->
    <section id="dashboard" class="section-content max-w-7xl mx-auto grid grid-cols-1 gap-6 animate-fade-in" aria-label="Dashboard section">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h1 class="text-text-primary text-3xl font-bold leading-tight">Repair Dashboard</h1>
        <div class="flex items-center gap-4">
          <button id="new-booking-btn-dashboard" class="flex items-center justify-center gap-2 h-10 px-4 text-sm font-medium text-background bg-primary hover:bg-primary-dark rounded-lg transition-transform hover:scale-105" aria-label="New Booking">
            <span class="material-symbols-outlined text-base">add</span>
            <span>New Booking</span>
          </button>

          <!-- Notification Bell -->
          <div class="relative">
            <button id="notification-bell" class="relative size-10 flex items-center justify-center text-text-muted bg-white/5 dark:bg-black/20 hover:bg-white/10 dark:hover:bg-white/5 rounded-full border border-card-border transition-transform hover:scale-105">
              <span class="material-symbols-outlined">notifications</span>
              <span id="notification-badge" class="absolute top-0 right-0 size-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full hidden"></span>
            </button>
            <div id="notification-panel" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-background border border-card-border rounded-xl shadow-2xl z-50 glass-card overflow-hidden animate-slide-up">
              <div class="p-4 border-b border-card-border flex justify-between items-center">
                <h3 class="font-bold text-text-primary">Notifications</h3>
                <button id="mark-all-read-btn" class="text-sm text-primary hover:underline">Mark all as read</button>
              </div>
              <div id="notification-list" class="p-2 max-h-96 overflow-y-auto"></div>
            </div>
          </div>

          <button id="theme-toggle-btn" class="relative size-10 flex items-center justify-center overflow-hidden text-text-muted bg-white/5 dark:bg-black/20 hover:bg-white/10 dark:hover:bg-white/5 rounded-full border border-card-border focus:outline-none focus-visible:ring-2 focus-visible:ring-primary transition-transform hover:scale-105" aria-label="Toggle theme">
            <span class="sr-only">Toggle theme</span>
            <span class="material-symbols-outlined absolute block dark:hidden dark:-rotate-90 dark:opacity-0 transition-transform duration-300 ease-in-out">light_mode</span>
            <span class="material-symbols-outlined absolute hidden dark:block dark:rotate-0 dark:opacity-100 rotate-90 opacity-0 transition-transform duration-300 ease-in-out">dark_mode</span>
          </button>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="flex flex-col justify-between gap-4 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="Total Bookings">
          <div class="flex items-center justify-between">
            <p class="text-text-muted text-sm font-medium">Total Bookings</p>
            <span class="material-symbols-outlined text-text-muted">calendar_month</span>
          </div>
          <div class="flex flex-col" id="total-bookings-stat">
            <p class="text-text-primary text-3xl font-bold">...</p>
            <p class="text-text-muted text-sm font-medium mt-1">Total appointments</p>
          </div>
        </div>
        <div class="flex flex-col justify-between gap-4 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="Services In Progress">
          <div class="flex items-center justify-between">
            <p class="text-text-muted text-sm font-medium">Services In Progress</p>
            <span class="material-symbols-outlined text-text-muted">construction</span>
          </div>
          <div class="flex flex-col" id="services-in-progress-stat">
            <p class="text-text-primary text-3xl font-bold">...</p>
            <p class="text-text-muted text-sm font-medium mt-1">Currently being serviced</p>
          </div>
        </div>
        <div class="flex flex-col justify-between gap-4 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="This Week's Revenue">
          <div class="flex items-center justify-between">
            <p class="text-text-muted text-sm font-medium">This Week's Revenue</p>
            <span class="material-symbols-outlined text-text-muted">payments</span>
          </div>
          <div class="flex flex-col" id="weekly-revenue-stat">
            <p class="text-text-primary text-3xl font-bold">...</p>
            <p class="text-text-muted text-sm font-medium mt-1">Revenue this week</p>
          </div>
        </div>
        <div class="flex flex-col justify-between gap-4 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="This Month's Revenue">
          <div class="flex items-center justify-between">
            <p class="text-text-muted text-sm font-medium">This Month's Revenue</p>
            <span class="material-symbols-outlined text-text-muted">account_balance_wallet</span>
          </div>
          <div class="flex flex-col" id="monthly-revenue-stat">
            <p class="text-text-primary text-3xl font-bold">...</p>
            <p class="text-text-muted text-sm font-medium mt-1">Revenue this month</p>
          </div>
        </div>
      </div>
      <div class="flex flex-col gap-4 rounded-xl border p-6 glass-card shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="Low Stock Parts">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
          <div>
            <p class="text-text-primary text-lg font-semibold">Low Stock Parts</p>
            <p class="text-text-muted text-sm">Inventory items that are running low and need reordering.</p>
          </div>
          <button class="flex items-center justify-center gap-2 h-9 px-3 text-sm font-medium text-primary-dark dark:text-primary border border-primary/40 dark:border-primary/50 rounded-lg hover:bg-primary/10 self-start sm:self-center transition-transform hover:scale-105" aria-label="Order All Low Stock Parts">
            <span class="material-symbols-outlined text-base">shopping_cart</span>
            <span>Order All</span>
          </button>
        </div>
        <div class="overflow-x-auto -mx-6 px-6">
          <table class="w-full text-left text-sm" id="low-stock-table">
            <thead class="text-xs text-text-muted uppercase bg-white/5">
              <tr>
                <th class="px-4 py-3 font-medium" scope="col">Part Name</th>
                <th class="px-4 py-3 font-medium" scope="col">Part ID</th>
                <th class="px-4 py-3 font-medium text-center" scope="col">Stock Level</th>
                <th class="px-4 py-3 font-medium text-center" scope="col">Threshold</th>
                <th class="px-4 py-3 font-medium" scope="col"></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="flex flex-col gap-4 rounded-xl border p-6 glass-card shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="At-Risk Customers">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
          <div>
            <p class="text-text-primary text-lg font-semibold">At-Risk Customers</p>
            <p class="text-text-muted text-sm">Customers with no appointments in over 6 months.</p>
          </div>
        </div>
        <div class="overflow-x-auto -mx-6 px-6">
          <table class="w-full text-left text-sm" id="at-risk-customers-table">
            <thead class="text-xs text-text-muted uppercase bg-white/5">
              <tr>
                <th class="px-4 py-3 font-medium" scope="col">Customer Name</th>
                <th class="px-4 py-3 font-medium" scope="col">Last Visit</th>
                <th class="px-4 py-3 font-medium" scope="col">Last Vehicle</th>
                <th class="px-4 py-3 font-medium text-right" scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Skeleton Loader -->
              <tr class="border-b border-card-border animate-pulse">
                  <td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-3/4"></div></td>
                  <td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-1/2"></div></td>
                  <td class="px-4 py-4"><div class="h-4 bg-slate-700/20 rounded w-1/3"></div></td>
                  <td class="px-4 py-4 text-right"><div class="h-8 bg-slate-700/20 rounded w-24 ml-auto"></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Bookings Section -->
    <section id="bookings" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Bookings section">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h1 class="text-text-primary text-3xl font-bold leading-tight">Manage Bookings</h1>
            <input type="text" id="bookings-search" placeholder="Search bookings..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="flex flex-col gap-4 rounded-xl border p-6 glass-card shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="All Bookings">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="text-text-primary text-lg font-semibold">All Appointments</p>
                    <p class="text-text-muted text-sm">View, confirm, or manage all customer bookings.</p>
                </div>
            </div>
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="w-full text-left text-sm" id="all-bookings-table">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-4 py-3 font-medium" scope="col">Customer</th>
                            <th class="px-4 py-3 font-medium" scope="col">Vehicle</th>
                            <th class="px-4 py-3 font-medium" scope="col">Package</th>
                            <th class="px-4 py-3 font-medium" scope="col">Technician</th>
                            <th class="px-4 py-3 font-medium" scope="col">Date & Time</th>
                            <th class="px-4 py-3 font-medium text-center" scope="col">Status</th>
                            <th class="px-4 py-3 font-medium text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Booking rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Edit Booking Modal -->
    <section id="edit-booking-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-rich-black/70 backdrop-blur-sm p-4 hidden animate-fade-in" aria-labelledby="edit-booking-title" role="dialog" aria-modal="true">
        <div class="glass-card w-full max-w-2xl rounded-2xl p-8 border shadow-modal relative">
            <button id="close-edit-modal" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10 transition-transform hover:scale-105" aria-label="Close modal">
                <span class="material-symbols-outlined">close</span>
            </button>
            <h3 id="edit-booking-title" class="text-2xl font-bold text-white mb-6">Edit Booking</h3>
            <form id="edit-booking-form" class="space-y-6">
                <input type="hidden" id="edit-booking-id" name="id">
                <div>
                    <label for="edit-customer-name" class="block text-sm font-medium text-gray-400 mb-2">Customer</label>
                    <input id="edit-customer-name" type="text" readonly class="form-input w-full rounded-lg border-gray-700 bg-black/30 p-4 text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label for="edit-technician-id" class="block text-sm font-medium text-gray-400 mb-2">Technician</label>
                    <select id="edit-technician-id" name="technician_id" required class="form-select w-full rounded-lg border-gray-700 bg-black/50 p-4 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent"></select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="edit-appointment-date" class="block text-sm font-medium text-gray-400 mb-2">Date</label>
                        <input id="edit-appointment-date" name="appointment_date" type="date" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-4 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit-appointment-time" class="block text-sm font-medium text-gray-400 mb-2">Time</label>
                        <input id="edit-appointment-time" name="appointment_time" type="time" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-4 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label for="edit-status" class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                    <select id="edit-status" name="status" required class="form-select w-full rounded-lg border-gray-700 bg-black/50 p-4 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                 <div>
                    <label for="edit-notes" class="block text-sm font-medium text-gray-400 mb-2">Notes</label>
                    <textarea id="edit-notes" name="notes" rows="3" class="form-textarea w-full rounded-lg border-gray-700 bg-black/50 p-4 text-white focus:ring-2 focus:ring-caribbean-green focus:border-transparent"></textarea>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto rounded-lg bg-primary px-6 py-3 text-lg font-bold tracking-wide transition-all duration-300 hover:scale-105 hover:bg-primary-dark">Save Changes</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Services section">
        <header class="flex flex-wrap justify-between items-center gap-4 mb-8"> 
            <div class="flex flex-col gap-1">
                <h1 class="text-text-primary text-3xl font-bold leading-tight">Manage Services</h1>
                <p class="text-text-muted text-base font-normal leading-normal">Add, edit, or remove the services offered at your shop.</p>
            </div>
            <button id="add-new-service-btn" class="flex items-center justify-center gap-2 h-10 px-4 text-sm font-medium text-background bg-primary hover:bg-primary-dark rounded-lg transition-transform hover:scale-105">
                <span class="material-symbols-outlined text-base">add</span>
                <span>Add New Service</span>
            </button>
        </header>

        <!-- Services Table -->
        <section class="overflow-hidden rounded-xl border glass-card shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-6 py-3" scope="col">Image</th>
                            <th class="px-6 py-3" scope="col">Service Name</th>
                            <th class="px-6 py-3" scope="col">Description</th>
                            <th class="px-6 py-3 text-right" scope="col">Price (PHP)</th>
                            <th class="px-6 py-3 text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="services-table-body">
                        <!-- Service rows will be injected by JS -->
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <!-- Add/Edit Service Modal -->
    <section id="service-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-rich-black/70 backdrop-blur-sm p-4 hidden animate-fade-in" role="dialog" aria-modal="true">
        <div class="glass-card w-full max-w-lg rounded-2xl p-8 border shadow-modal relative">
            <button id="close-service-modal" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10 transition-transform hover:scale-105" aria-label="Close modal"><span class="material-symbols-outlined">close</span></button>
            <h3 id="service-modal-title" class="text-2xl font-bold text-white mb-6">Add New Service</h3>
            <form id="service-form" class="space-y-4">
                <input type="hidden" id="service-id-input" name="id">
                <div><label for="service-name" class="block text-sm font-medium text-gray-400 mb-2">Service Name</label><input id="service-name" name="name" type="text" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary">
                </div>
                <div><label for="service-description" class="block text-sm font-medium text-gray-400 mb-2">Description</label><textarea id="service-description" name="description" rows="3" class="form-textarea w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label for="service-price" class="block text-sm font-medium text-gray-400 mb-2">Price (PHP)</label><input id="service-price" name="price" type="number" step="0.01" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary"></div>
                    <div><label for="service-category" class="block text-sm font-medium text-gray-400 mb-2">Category</label><select id="service-category" name="category" required class="form-select w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary"><option value="maintenance">Maintenance</option><option value="repair">Repair</option><option value="diagnostics">Diagnostics</option></select></div>
                </div>
                <div>
                    <label for="service-image-url" class="block text-sm font-medium text-gray-400 mb-2">Image URL</label>
                    <input id="service-image-url" name="image_url" type="text" placeholder="e.g., assets/img/Services/services-1.png" class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary">
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="rounded-lg bg-primary px-6 py-3 text-base font-bold text-background transition-all hover:scale-105 hover:bg-primary-dark">Save Service</button></div>
            </form>
        </div>
    </section>

    

    <!-- Login History Section -->
    <section id="login-history" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Login History section">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h1 class="text-text-primary text-3xl font-bold leading-tight">Recent User Logins</h1>
            <input type="text" id="login-search" placeholder="Search logins..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="flex flex-col gap-4 rounded-xl border p-6 glass-card shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="Login History Table">
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="w-full text-left text-sm" id="login-history-table">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-4 py-3 font-medium" scope="col">Username</th>
                            <th class="px-4 py-3 font-medium" scope="col">Email</th>
                            <th class="px-4 py-3 font-medium" scope="col">Login Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- History rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Contacts Section -->
    <section id="contacts" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Contacts section">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h1 class="text-text-primary text-3xl font-bold leading-tight">Customer Messages</h1>
            <input type="text" id="contacts-search" placeholder="Search messages..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="flex flex-col gap-4 rounded-xl border p-6 glass-card shadow-card hover:shadow-lg transition-shadow" role="region" aria-label="Contact Messages">
            <div>
                <p class="text-text-primary text-lg font-semibold">Inbox</p>
                <p class="text-text-muted text-sm">Messages from the website contact form.</p>
            </div>
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="w-full text-left text-sm" id="contact-messages-table">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-4 py-3 font-medium" scope="col">From</th>
                            <th class="px-4 py-3 font-medium" scope="col">Message</th>
                            <th class="px-4 py-3 font-medium" scope="col">Received</th>
                            <th class="px-4 py-3 font-medium text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Inventory Section -->
    <section id="inventory" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Inventory section">
        <header class="flex flex-wrap justify-between items-center gap-4 mb-8"> 
            <div class="flex flex-col gap-1">
                <h1 class="text-text-primary text-3xl font-bold leading-tight">Parts Inventory</h1>
                <p class="text-text-muted text-base font-normal leading-normal">Manage your car parts inventory, track stock, and handle orders.</p>
            </div>
            <div class="flex items-center gap-4">
                <input type="text" id="inventory-search" placeholder="Search parts..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">
                <button id="add-new-part-btn" class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-background gap-2 text-sm font-medium leading-normal px-4 hover:bg-primary-dark transition-transform hover:scale-105">
                    <span class="material-symbols-outlined text-base">add</span>
                    <span class="truncate">Add New Part</span>
                </button>
            </div>
        </header>

        <!-- Inventory Stats -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="inventory-stats">
            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between"><p class="text-text-muted text-sm font-medium">Total Parts</p><span class="material-symbols-outlined text-primary">inventory_2</span></div>
                <p class="text-text-primary tracking-tight text-3xl font-bold" id="stat-total-parts">...</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between"><p class="text-text-muted text-sm font-medium">Total Value</p><span class="material-symbols-outlined text-primary">attach_money</span></div>
                <p class="text-text-primary tracking-tight text-3xl font-bold" id="stat-total-value">...</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between"><p class="text-text-muted text-sm font-medium">Low Stock</p><span class="material-symbols-outlined text-yellow-400">warning</span></div>
                <p class="text-text-primary tracking-tight text-3xl font-bold" id="stat-low-stock">...</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 glass-card border shadow-card hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between"><p class="text-text-muted text-sm font-medium">Out of Stock</p><span class="material-symbols-outlined text-red-400">error</span></div>
                <p class="text-text-primary tracking-tight text-3xl font-bold" id="stat-out-of-stock">...</p>
            </div>
        </section>

        <!-- Inventory Table -->
        <section class="overflow-hidden rounded-xl border glass-card shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-6 py-3" scope="col">Part Name</th>
                            <th class="px-6 py-3" scope="col">SKU</th>
                            <th class="px-6 py-3" scope="col">Supplier</th>
                            <th class="px-6 py-3 text-right" scope="col">Stock</th>
                            <th class="px-6 py-3 text-right" scope="col">Price</th>
                            <th class="px-6 py-3" scope="col">Status</th>
                            <th class="px-6 py-3 text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-table-body">
                        <!-- Rows will be injected by JS -->
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <!-- New Booking Modal (Admin Creates Booking for Customer) -->
<div id="new-booking-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
    <div class="glass-card rounded-xl border p-8 w-11/12 md:w-3/4 lg:w-1/2 max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-text-primary">Create New Booking (Admin)</h2>
            <button id="close-new-booking-modal" class="text-text-muted hover:text-red-500 text-3xl">×</button>
        </div>

        <form id="admin-booking-form" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-2">Customer <span class="text-red-500">*</span></label>
                    <select name="customer_id" required class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Select Customer</option>
                        <!-- Filled by JS -->
                    </select>
                </div>

                <!-- Vehicle Selection -->
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-2">Vehicle <span class="text-red-500">*</span></label>
                    <select name="vehicle_id" required class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">First select customer</option>
                    </select>
                </div>

                <!-- Service Package -->
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-2">Service Package <span class="text-red-500">*</span></label>
                    <select name="package_name" required class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Select Package</option>
                        <!-- Filled from DB -->
                    </select>
                </div>

                <!-- Technician -->
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-2">Technician <span class="text-red-500">*</span></label>
                    <select name="technician_id" required class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Select Technician</option>
                    </select>
                </div>

                <!-- Date & Time -->
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-2">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-2">Time <span class="text-red-500">*</span></label>
                    <input type="time" name="time" required class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary">
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-text-muted mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-card-border text-text-primary"></textarea>
            </div>

            <!-- Payment Status (Admin Override) -->
            <div class="mt-6 flex items-center gap-4">
                <label class="text-sm font-medium text-text-muted">Mark as Paid?</label>
                <input type="checkbox" name="mark_as_paid" class="size-6 text-primary">
                <span class="text-xs text-text-muted">(Admin can bypass GCash payment)</span>
            </div>

            <div class="mt-8 flex gap-4 justify-end">
                <button type="button" id="cancel-booking-btn" class="px-6 py-3 rounded-lg border border-card-border text-text-muted hover:bg-white/10">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 rounded-lg bg-primary text-background font-semibold hover:bg-primary-dark flex items-center gap-2">
                    <span class="material-symbols-outlined">event_available</span>
                    Create Booking
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Add/Edit Part Modal -->
    <div id="part-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-rich-black/70 backdrop-blur-sm p-4 hidden animate-fade-in" aria-labelledby="part-modal-title" role="dialog" aria-modal="true">
        <div class="glass-card w-full max-w-2xl rounded-2xl p-8 border shadow-modal relative">
            <button id="close-part-modal" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10 transition-transform hover:scale-105" aria-label="Close modal">
                <span class="material-symbols-outlined">close</span>
            </button>
            <h3 id="part-modal-title" class="text-2xl font-bold text-white mb-6">Add New Part</h3>
            <form id="part-form" class="space-y-4">
                <input type="hidden" id="part-id-input" name="id">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="part-name" class="block text-sm font-medium text-gray-400 mb-2">Part Name</label>
                        <input id="part-name" name="part_name" type="text" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label for="part-sku" class="block text-sm font-medium text-gray-400 mb-2">Part ID / SKU</label>
                        <input id="part-sku" name="part_id" type="text" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="part-supplier" class="block text-sm font-medium text-gray-400 mb-2">Supplier</label>
                        <input id="part-supplier" name="supplier" type="text" class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                     <div>
                        <label for="part-price" class="block text-sm font-medium text-gray-400 mb-2">Price (PHP)</label>
                        <input id="part-price" name="price" type="number" step="0.01" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="part-stock" class="block text-sm font-medium text-gray-400 mb-2">Stock Level</label>
                        <input id="part-stock" name="stock_level" type="number" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label for="part-threshold" class="block text-sm font-medium text-gray-400 mb-2">Low Stock Threshold</label>
                        <input id="part-threshold" name="threshold" type="number" required class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
                 <div>
                    <label for="part-image-url" class="block text-sm font-medium text-gray-400 mb-2">Image URL (Optional)</label>
                    <input id="part-image-url" name="image_url" type="url" class="form-input w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto rounded-lg bg-primary px-6 py-3 text-base font-bold text-background transition-all duration-300 hover:scale-105 hover:bg-primary-dark">Save Part</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Section -->
    <section id="orders" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Part Orders section">
        <header class="flex flex-wrap justify-between items-center gap-4 mb-8"> 
            <div class="flex flex-col gap-1">
                <h1 class="text-text-primary text-3xl font-bold leading-tight">Part Orders</h1>
                <p class="text-text-muted text-base font-normal leading-normal">View and manage all customer part purchases.</p>
            </div>
            <div class="flex items-center gap-4">
                <input type="text" id="orders-search" placeholder="Search orders..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
        </header>

        <!-- Orders Table -->
        <section class="overflow-hidden rounded-xl border glass-card shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-6 py-3" scope="col">Order ID</th>
                            <th class="px-6 py-3" scope="col">Customer</th>
                            <th class="px-6 py-3" scope="col">Items</th>
                            <th class="px-6 py-3 text-right" scope="col">Total</th>
                            <th class="px-6 py-3" scope="col">Payment Method</th>
                            <th class="px-6 py-3" scope="col">Date</th>
                            <th class="px-6 py-3 text-center" scope="col">Status</th>
                            <th class="px-6 py-3 text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        <!-- Order rows will be injected by JS -->
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <!-- Edit Order Status Modal -->
    <div id="order-status-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-rich-black/70 backdrop-blur-sm p-4 hidden animate-fade-in">
        <div class="glass-card w-full max-w-sm rounded-2xl p-8 border shadow-modal relative">
            <button id="close-order-status-modal" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10"><span class="material-symbols-outlined">close</span></button>
            <h3 class="text-2xl font-bold text-white mb-6">Update Order Status</h3>
            <form id="order-status-form" class="space-y-4"><input type="hidden" id="order-id-input"><label for="order-status-select" class="block text-sm font-medium text-gray-400 mb-2">Status</label><select id="order-status-select" class="form-select w-full rounded-lg border-gray-700 bg-black/50 p-3 text-white focus:ring-2 focus:ring-primary"><option value="Processing">Processing</option><option value="Shipped">Shipped</option><option value="Completed">Completed</option><option value="Cancelled">Cancelled</option></select><div class="pt-4 flex justify-end"><button type="submit" class="rounded-lg bg-primary px-6 py-3 text-base font-bold text-background transition-all hover:scale-105">Update Status</button></div></form>
        </div>
    </div>

      <!-- Invoices Section -->
      <section id="invoices" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Invoices section">
        <header class="flex flex-wrap justify-between items-center gap-4 mb-8">
            <div class="flex flex-col gap-1">
                <h1 class="text-text-primary text-3xl font-bold leading-tight">Invoices</h1>
                <p class="text-text-muted text-base font-normal leading-normal">Review all generated invoices for part orders and services.</p>
            </div>
            <input type="text" id="invoices-search" placeholder="Search by Order ID, Customer..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">
        </header>
        <section class="overflow-hidden rounded-xl border glass-card shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text-muted uppercase bg-white/5">
                        <tr>
                            <th class="px-6 py-3" scope="col">Order ID</th>
                            <th class="px-6 py-3" scope="col">Customer</th>
                            <th class="px-6 py-3" scope="col">Details</th>
                            <th class="px-6 py-3 text-right" scope="col">Total</th>
                            <th class="px-6 py-3" scope="col">Date</th>
                            <th class="px-6 py-3 text-center" scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody id="invoices-table-body">
                        <!-- Invoice rows will be injected by JS -->
                    </tbody>
                </table>
            </div>
        </section>
      </section>

      <!-- Reports Section -->
      <section id="reports" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Reports section">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
            <h1 class="text-text-primary text-4xl font-black leading-tight tracking-[-0.033em]">Reports &amp; Analytics</h1>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 rounded-lg bg-white/10 border border-card-border px-4 h-11 text-sm font-medium text-text-primary hover:bg-white/20">
                    <span class="material-symbols-outlined text-primary" style="font-size: 20px;">print</span>
                    Print
                </button>
                <button class="flex items-center gap-2 rounded-lg bg-primary hover:bg-primary-dark px-4 h-11 text-sm font-medium text-background">
                    <span class="material-symbols-outlined" style="font-size: 20px;">download</span>
                    Export
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="p-6 rounded-xl glass-card border">
                <p class="text-base font-medium text-text-muted mb-1">Total Completed Repairs</p>
                <p id="report-total-repairs" class="text-3xl font-bold text-text-primary">...</p>
            </div>
            <div class="p-6 rounded-xl glass-card border">
                <p class="text-base font-medium text-text-muted mb-1">Total Revenue</p>
                <p id="report-total-revenue" class="text-3xl font-bold text-text-primary">...</p>
            </div>
            <div class="p-6 rounded-xl glass-card border">
                <p class="text-base font-medium text-text-muted mb-1">Inventory Value</p>
                <p id="report-inventory-value" class="text-3xl font-bold text-text-primary">...</p>
            </div>
        </div>
        <div class="glass-card border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-card-border">
                <h3 class="text-lg font-bold text-text-primary">Revenue Report: Last 30 Days</h3>
                <p class="text-sm text-text-muted">Detailed breakdown of completed services.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-sm text-text-muted bg-white/5">
                        <tr>
                            <th class="p-4 font-medium">Date</th>
                            <th class="p-4 font-medium">Service Name</th>
                            <th class="p-4 font-medium">Customer</th>
                            <th class="p-4 font-medium">Technician</th>
                            <th class="p-4 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="revenue-report-table-body" class="text-sm text-text-primary divide-y divide-card-border">
                        <!-- Dynamic rows will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
      </section>

      <!-- Schedule Section -->
      <section id="schedule" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Schedule section">
        <div class="p-8">
<header class="flex flex-wrap justify-between items-start gap-4 mb-6">
<div class="flex flex-col">
<h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Schedules &amp; Appointments</h1>
<p class="text-gray-500 dark:text-[#9dabb9] text-base font-normal leading-normal mt-1">Manage appointments and technician schedules.</p>
</div>
<div class="flex items-center gap-4">
<input type="text" id="schedule-search" placeholder="Search schedules..." class="px-4 py-2 rounded-lg border border-card-border bg-transparent text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary">


<button class="flex items-center justify-center gap-2 rounded-lg h-10 px-4 bg-primary-light dark:bg-primary-dark text-white dark:text-background-dark text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary-darker-light dark:hover:bg-primary-darker-dark">
<span class="material-symbols-outlined text-lg">add</span>
<span class="truncate">New Appointment</span>
</button>
<button class="relative inline-flex items-center h-10 w-10 justify-center rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-white/10 dark:hover:bg-white/20 text-gray-600 dark:text-white/80" id="theme-toggle">
<span class="material-symbols-outlined dark:hidden">wb_sunny</span>
<span class="material-symbols-outlined hidden dark:inline">nightlight</span>
</button>
</div>
</header>
<div class="glass-card border rounded-xl overflow-hidden">
    <div class="p-6 border-b border-card-border">
        <h3 class="text-lg font-bold text-text-primary">All Appointments</h3>
        <p class="text-sm text-text-muted">Confirmed and pending bookings for technicians.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-text-muted uppercase bg-white/5">
                <tr>
                    <th class="px-6 py-3" scope="col">Date</th>
                    <th class="px-6 py-3" scope="col">Time</th>
                    <th class="px-6 py-3" scope="col">Technician</th>
                    <th class="px-6 py-3" scope="col">Customer</th>
                    <th class="px-6 py-3" scope="col">Service Package</th>
                    <th class="px-6 py-3 text-center" scope="col">Status</th>
                </tr>
            </thead>
            <tbody id="schedule-table-body">
                <!-- Schedule items will be injected here by JS -->
            </tbody>
        </table>
    </div>
</div>
</div>
      </section>

      <!-- Settings Section -->
      <section id="settings" class="hidden max-w-7xl mx-auto space-y-8 animate-fade-in" aria-label="Settings section">
        <h2 class="text-2xl font-semibold text-text-primary">Settings</h2>
        <p class="text-text-muted">Placeholder content for Settings section.</p>
      </section>
  </main>

  <script>
    const API_URL = '../../Backend/admindash-backend/admin-backend.php';

    // Theme toggle button
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    themeToggleBtn.addEventListener('click', () => {
      if (localStorage.getItem('theme')) {
        if (localStorage.getItem('theme') === 'light') {
          document.documentElement.classList.add('dark');
          localStorage.setItem('theme', 'dark');
        } else {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('theme', 'light');
        }
      } else {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('theme', 'light');
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('theme', 'dark');
        }
      }
    });

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
    });

    // Sidebar navigation controls showing/hiding sections
    const sidebarButtons = document.querySelectorAll('aside nav button[data-section]');
    const sections = document.querySelectorAll('main section.section-content, main section[aria-label$="section"]');

    function showSection(sectionId) {
      sections.forEach(sec => sec.classList.add('hidden'));
      const activeSection = document.getElementById(sectionId);
      if (activeSection) activeSection.classList.remove('hidden');

      // Load data specific to the section when it's shown
      switch (sectionId) {
        case 'dashboard':
          loadDashboardStats();
          loadLowStockParts();
          loadAtRiskCustomers();
          break;
        case 'bookings':
          loadAllBookings();
          break;
        case 'services':
          loadServices();
          break;
        case 'inventory':
          loadInventoryData();
          break;
        case 'orders':
          loadOrders();
          break;
        case 'invoices':
          loadInvoices();
          break;
        case 'reports':
          loadReportData(); // This was missing a function call
          break;
        case 'schedule':
          loadTrainerSchedule();
          break;
      }

      sidebarButtons.forEach(btn => {
        if (btn.dataset.section === sectionId) {
          btn.classList.add('bg-primary/10', 'text-primary', 'font-semibold');
          btn.setAttribute('aria-current', 'page');
        } else {
          btn.classList.remove('bg-primary/10', 'text-primary', 'font-semibold');
          btn.removeAttribute('aria-current');
        }
      });
      // Close sidebar on mobile after selection
      if (window.innerWidth < 1024) {
        sidebar.classList.add('-translate-x-full');
      }
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

    async function fetchAdminData(action) {
        try {
            const response = await fetch(`${API_URL}?action=${action}`);
            if (!response.ok) {
                if (response.status === 403) {
                    // If unauthorized, redirect to login
                    window.location.href = '../auth/login.php';
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error(`Error fetching ${action}:`, error);
            Swal.fire('Error', 'Failed to load data. Please try again.', 'error');
        }
    }

    // 1. Load Admin Details
    async function loadAdminDetails() {
        const admin = await fetchAdminData('getAdminDetails');
        if (admin) {
            document.getElementById('admin-name').textContent = admin.username;
            document.getElementById('admin-email').textContent = admin.email;
        }
    }

    // 2. Load Dashboard Statistics
    async function loadDashboardStats() {
        const stats = await fetchAdminData('getDashboardStats');
        if (stats) {
            document.querySelector('#total-bookings-stat p:first-child').textContent = stats.total_bookings;
            document.querySelector('#services-in-progress-stat p:first-child').textContent = stats.services_in_progress;
            
            const weeklyRevenue = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(stats.weekly_revenue);
            document.querySelector('#weekly-revenue-stat p:first-child').textContent = weeklyRevenue;

            const monthlyRevenue = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(stats.monthly_revenue);
            document.querySelector('#monthly-revenue-stat p:first-child').textContent = monthlyRevenue;
        }
    }

    // 3. Load Low Stock Parts
    async function loadLowStockParts() {
        const parts = await fetchAdminData('getLowStockParts');
        const tableBody = document.querySelector('#low-stock-table tbody');
        tableBody.innerHTML = ''; // Clear existing static rows

        if (parts && parts.length > 0) {
            parts.forEach(part => {
                const stockLevelClass = part.stock_level < part.threshold 
                    ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' 
                    : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400';

                const row = `
                    <tr class="border-b border-card-border hover:bg-white/5">
                        <td class="px-4 py-3 font-medium text-text-primary">${part.part_name}</td>
                        <td class="px-4 py-3 text-text-muted">${part.part_id}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full ${stockLevelClass}">
                                ${part.stock_level}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-text-muted">${part.threshold}</td>
                        <td class="px-4 py-3 text-right">
                            <button class="text-primary-dark dark:text-primary text-sm font-semibold hover:underline" aria-label="Order ${part.part_name}">
                                Order Now
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            const noStockRow = `
                <tr class="border-b border-card-border">
                    <td colspan="5" class="px-4 py-4 text-center text-text-muted">All parts are well-stocked.</td>
                </tr>
            `;
            tableBody.innerHTML = noStockRow;
        }
    }

    // 3.5. Load At-Risk Customers
    async function loadAtRiskCustomers() {
        const customers = await fetchAdminData('getAtRiskCustomers');
        const tableBody = document.querySelector('#at-risk-customers-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        if (customers && customers.length > 0) {
            customers.forEach(customer => {
                const row = `
                    <tr class="border-b border-card-border hover:bg-white/5">
                        <td class="px-4 py-3">
                            <p class="font-medium text-text-primary">${customer.username}</p>
                            <p class="text-xs text-text-muted">${customer.email}</p>
                        </td>
                        <td class="px-4 py-3 text-text-muted">${customer.overdue_period} ago</td>
                        <td class="px-4 py-3 text-text-muted">${customer.last_vehicle_name || 'N/A'}</td>
                        <td class="px-4 py-3 text-right">
                            <button class="send-reminder-btn text-primary-dark dark:text-primary text-sm font-semibold hover:underline" 
                                    data-id="${customer.id}" 
                                    data-name="${customer.username}" 
                                    data-vehicle="${customer.last_vehicle_name || 'your vehicle'}">
                                Send Reminder
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `
                <tr class="border-b border-card-border">
                    <td colspan="4" class="px-4 py-4 text-center text-text-muted">No at-risk customers found. Great job!</td>
                </tr>
            `;
        }
    }

    // Handle "Send Reminder" click
    document.getElementById('at-risk-customers-table').addEventListener('click', async (e) => {
        const reminderBtn = e.target.closest('.send-reminder-btn');
        if (!reminderBtn) return;

        reminderBtn.disabled = true;
        reminderBtn.textContent = 'Sending...';

        const customerId = reminderBtn.dataset.id;
        const customerName = reminderBtn.dataset.name;
        const vehicleName = reminderBtn.dataset.vehicle;

        try {
            const response = await fetch(`${API_URL}?action=sendReminderToCustomer`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ customer_id: customerId, customer_name: customerName, vehicle_name: vehicleName })
            });
            const data = await response.json();
            if (data.success) {
                Swal.fire('Success', 'Reminder sent successfully!', 'success');
                reminderBtn.textContent = 'Sent!';
            } else { throw new Error(data.error || 'Failed to send reminder.'); }
        } catch (error) { Swal.fire('Error', error.message, 'error'); reminderBtn.disabled = false; reminderBtn.textContent = 'Send Reminder'; }
    });

  // --- BOOKING MANAGEMENT LOGIC ---

// 1. Load All Bookings into the Table
async function loadAllBookings(query = '') {
    const bookings = await fetchAdminData(`getAllBookings${query ? `&search=${query}` : ''}`);
    const tableBody = document.querySelector('#all-bookings-table tbody');
    tableBody.innerHTML = ''; 

    if (bookings && bookings.length > 0) {
        bookings.forEach(booking => {
            // Format Date and Time
            const dateObj = new Date(booking.appointment_date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            
            // Format Time (handling 24hr to 12hr conversion)
            const [hours, minutes] = booking.appointment_time.split(':');
            const timeObj = new Date();
            timeObj.setHours(hours, minutes);
            const formattedTime = timeObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

            // Status Badge Logic
            let statusBadge = '';
            let statusClass = '';
            switch (booking.status) {
                case 'pending': statusClass = 'bg-yellow-100 text-yellow-800'; break;
                case 'confirmed': statusClass = 'bg-green-100 text-green-800'; break;
                case 'completed': statusClass = 'bg-blue-100 text-blue-800'; break;
                case 'cancelled': statusClass = 'bg-red-100 text-red-800'; break;
                default: statusClass = 'bg-gray-100 text-gray-800';
            }
            statusBadge = `<span class="px-2 py-1 rounded-full text-xs font-bold ${statusClass}">${booking.status.toUpperCase()}</span>`;

            // Render Row
            const row = `
                <tr class="border-b border-card-border hover:bg-white/5 transition-colors">
                    <td class="px-4 py-3 font-medium text-text-primary">${booking.customer_name || 'Unknown'}</td>
                    <td class="px-4 py-3 text-text-muted">
                        <button class="vehicle-details-btn text-primary hover:underline" data-name="${booking.vehicle_name}" data-plate="${booking.plate_number}" data-photo="${booking.car_photo_url}" data-issues="${booking.issues}">
                            ${booking.vehicle_name || 'N/A'}
                        </button>
                    </td>
                    <td class="px-4 py-3 text-text-muted">${booking.package_name || 'N/A'}</td>
                    <td class="px-4 py-3 text-text-muted">${booking.technician_name || 'Unassigned'}</td>
                    <td class="px-4 py-3 text-text-muted">${formattedDate} <br> <span class="text-xs">${formattedTime}</span></td>
                    <td class="px-4 py-3 text-center">${statusBadge}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="edit-booking-btn p-1 text-blue-400 hover:text-blue-300 hover:bg-blue-400/10 rounded transition" title="Edit" data-id="${booking.id}">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            ${booking.status === 'pending' ? `
                                <button class="update-status-btn p-1 text-green-400 hover:text-green-300 hover:bg-green-400/10 rounded transition" title="Confirm" data-id="${booking.id}" data-status="confirmed">
                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                </button>
                            ` : ''}
                            ${booking.status === 'confirmed' ? `
                                <button class="update-status-btn p-1 text-cyan-400 hover:text-cyan-300 hover:bg-cyan-400/10 rounded transition" title="Mark In Progress" data-id="${booking.id}" data-status="in_progress">
                                    <span class="material-symbols-outlined text-lg">sync</span>
                                </button>
                                <button class="update-status-btn p-1 text-purple-400 hover:text-purple-300 hover:bg-purple-400/10 rounded transition" title="Mark Completed" data-id="${booking.id}" data-status="completed">
                                    <span class="material-symbols-outlined text-lg">task_alt</span>
                                </button>
                            ` : ''}
                            ${booking.status !== 'cancelled' && booking.status !== 'completed' ? `
                                <button class="update-status-btn p-1 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded transition" title="Cancel" data-id="${booking.id}" data-status="cancelled">
                                    <span class="material-symbols-outlined text-lg">cancel</span>
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    } else {
        tableBody.innerHTML = `<tr><td colspan="7" class="px-4 py-8 text-center text-text-muted">No bookings found.</td></tr>`;
    }
}

// Search for bookings
const bookingsSearch = document.getElementById('bookings-search');
bookingsSearch.addEventListener('input', (e) => loadAllBookings(e.target.value));

// 2. Handle Status Update (Confirm/Cancel) via Event Delegation
document.getElementById('all-bookings-table').addEventListener('click', async (e) => {
    const statusBtn = e.target.closest('.update-status-btn');
    const vehicleBtn = e.target.closest('.vehicle-details-btn');

    if (vehicleBtn) {
        const name = vehicleBtn.dataset.name;
        const plate = vehicleBtn.dataset.plate;
        const photo = vehicleBtn.dataset.photo;
        const issues = vehicleBtn.dataset.issues;

        Swal.fire({
            title: name,
            html: `
                <div class="text-left">
                    <img src="${photo}" alt="${name}" class="w-full h-48 object-cover rounded-lg mb-4 border border-card-border">
                    <p class="font-bold">Plate Number: <span class="font-normal">${plate || 'N/A'}</span></p>
                    <p class="font-bold mt-2">Reported Issues:</p>
                    <p class="whitespace-pre-wrap font-normal">${issues || 'None reported.'}</p>
                </div>
            `,
            background: 'var(--background)',
            color: 'var(--text-primary)'
        });
    }

    if (!statusBtn) return;

    const id = statusBtn.dataset.id;
    const newStatus = statusBtn.dataset.status;
    const actionText = newStatus === 'confirmed' ? 'Confirm' : 'Cancel';
    const btnColor = newStatus === 'confirmed' ? '#22CC95' : '#d33';

    const result = await Swal.fire({
        title: `${actionText} Booking?`,
        text: `Are you sure you want to mark this as ${newStatus}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: btnColor,
        confirmButtonText: `Yes, ${actionText} it!`,
        background: '#1f2937', // Dark mode match
        color: '#fff'
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`${API_URL}?action=updateAppointmentStatus`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ appointment_id: id, status: newStatus })
            });
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({title: 'Updated!', text: `Booking has been ${newStatus}.`, icon: 'success', background: '#1f2937', color: '#fff'});
                loadAllBookings();
            } else {
                throw new Error(data.error || 'Update failed');
            }
        } catch (error) {
            Swal.fire({title: 'Error', text: error.message, icon: 'error', background: '#1f2937', color: '#fff'});
        }
    }
});

// 3. Handle Edit Button Click (Open Modal)
document.getElementById('all-bookings-table').addEventListener('click', async (e) => {
    const btn = e.target.closest('.edit-booking-btn');
    if (!btn) return;
    
    const bookingId = btn.dataset.id;
    const modal = document.getElementById('edit-booking-modal');
    
    // Show loading state
    modal.classList.remove('hidden');

    try {
        // Fetch Booking Details
        const booking = await fetchAdminData(`getBookingDetails&id=${bookingId}`);
        // Fetch Technicians List
        const technicians = await fetchAdminData('getTechnicians');

        // Populate Technician Dropdown
        const techSelect = document.getElementById('edit-technician-id');
        techSelect.innerHTML = '<option value="">Select Technician</option>';
        technicians.forEach(tech => {
            const selected = booking.technician_id == tech.id ? 'selected' : '';
            techSelect.innerHTML += `<option value="${tech.id}" ${selected}>${tech.name}</option>`;
        });

        // Populate Form Fields
        document.getElementById('edit-booking-id').value = booking.id;
        document.getElementById('edit-customer-name').value = booking.username;
        document.getElementById('edit-appointment-date').value = booking.appointment_date;
        document.getElementById('edit-appointment-time').value = booking.appointment_time;
        document.getElementById('edit-status').value = booking.status;
        document.getElementById('edit-notes').value = booking.notes || '';

    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Could not load booking details', 'error');
        modal.classList.add('hidden'); // Hide if error
    }
});

// 4. Handle Edit Form Submission (Save Changes)
document.getElementById('edit-booking-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch(`${API_URL}?action=updateBookingDetails`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (result.success) {
            // Close Modal
            document.getElementById('edit-booking-modal').classList.add('hidden');
            
            Swal.fire({title: 'Saved!', text: 'Booking updated successfully.', icon: 'success', background: '#1f2937', color: '#fff'});
            loadAllBookings(); // Refresh Table
        } else {
            throw new Error(result.error || 'Failed to save');
        }
    } catch (error) {
        Swal.fire({title: 'Error', text: error.message, icon: 'error', background: '#1f2937', color: '#fff'});
    }
});

// 5. Close Modal Button Logic
document.getElementById('close-edit-modal').addEventListener('click', () => {
    document.getElementById('edit-booking-modal').classList.add('hidden');
});

// Initialize
loadAllBookings();

    // 6. Load Login History
    async function loadLoginHistory(query = '') {
        const history = await fetchAdminData(`getLoginHistory${query ? `&search=${query}` : ''}`);
        const tableBody = document.querySelector('#login-history-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        if (history && history.length > 0) {
            history.forEach(entry => {
                const loginTime = new Date(entry.login_time + ' UTC'); // Assuming DB time is UTC
                const formattedTime = loginTime.toLocaleString('en-US', { 
                    year: 'numeric', month: 'short', day: 'numeric', 
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true 
                });

                const row = `
                    <tr class="border-b border-card-border hover:bg-white/5">
                        <td class="px-4 py-3 font-medium text-text-primary">${entry.username}</td>
                        <td class="px-4 py-3 text-text-muted">${entry.email}</td>
                        <td class="px-4 py-3 text-text-muted">${formattedTime}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            const noHistoryRow = `
                <tr class="border-b border-card-border"><td colspan="3" class="px-4 py-4 text-center text-text-muted">No login history found.</td></tr>
            `;
            tableBody.innerHTML = noHistoryRow;
        }
    }

    // Search for login history
    const loginSearch = document.getElementById('login-search');
    loginSearch.addEventListener('input', (e) => loadLoginHistory(e.target.value));

    // 7. Load Contact Messages
    async function loadContactMessages(query = '') {
        const messages = await fetchAdminData(`getContactMessages${query ? `&search=${query}` : ''}`);
        const tableBody = document.querySelector('#contact-messages-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        if (messages && messages.length > 0) {
            messages.forEach(msg => {
                const receivedDate = new Date(msg.created_at);
                const formattedDate = receivedDate.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                const isUnread = msg.is_read == 0;

                const row = `
                    <tr class="border-b border-card-border hover:bg-white/5 ${isUnread ? 'font-bold' : 'font-normal'}">
                        <td class="px-4 py-4">
                            <p class="${isUnread ? 'text-text-primary' : 'text-text-muted'}">${msg.name}</p>
                            <p class="text-xs ${isUnread ? 'text-text-muted' : 'text-gray-500'}">${msg.email}</p>
                        </td>
                        <td class="px-4 py-4 ${isUnread ? 'text-text-primary' : 'text-text-muted'} whitespace-pre-wrap max-w-md">${msg.message}</td>
                        <td class="px-4 py-4 text-text-muted whitespace-nowrap">${formattedDate}</td>
                        <td class="px-4 py-4 text-right space-x-2 whitespace-nowrap">
                            ${isUnread ? `
                                <button class="contact-action-btn text-green-500 hover:text-green-400 text-sm font-semibold" data-id="${msg.id}" data-action="mark_read">
                                    Mark Read
                                </button>
                            ` : `
                                <button class="contact-action-btn text-yellow-500 hover:text-yellow-400 text-sm font-semibold" data-id="${msg.id}" data-action="mark_unread">
                                    Mark Unread
                                </button>
                            `}
                            <button class="contact-action-btn text-red-500 hover:text-red-400 text-sm font-semibold" data-id="${msg.id}" data-action="delete">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr class="border-b border-card-border"><td colspan="4" class="px-4 py-4 text-center text-text-muted">No contact messages found.</td></tr>`;
        }
    }

    // Search for contacts
    const contactsSearch = document.getElementById('contacts-search');
    contactsSearch.addEventListener('input', (e) => loadContactMessages(e.target.value));

    // 8. Handle Contact Message Actions
    async function handleContactAction(event) {
        const button = event.target.closest('.contact-action-btn');
        if (!button) return;

        const messageId = button.dataset.id;
        const action = button.dataset.action;

        let requestBody, apiAction, successMessage;

        if (action === 'delete') {
            apiAction = 'deleteContactMessage';
            requestBody = { message_id: messageId };
            successMessage = 'Message deleted successfully.';
        } else {
            apiAction = 'updateContactStatus';
            requestBody = { message_id: messageId, is_read: (action === 'mark_read' ? 1 : 0) };
            successMessage = 'Message status updated.';
        }

        try {
            const response = await fetch(`${API_URL}?action=${apiAction}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestBody)
            });
            const result = await response.json();

            if (result.success) {
                loadContactMessages(); // Refresh the table
            } else {
                throw new Error(result.error || 'Failed to perform action.');
            }
        } catch (error) {
            console.error(`Error performing action ${action}:`, error);
            Swal.fire('Error!', error.message, 'error');
        }
    }

    // 10. Inventory Management
    let allParts = []; // Cache for inventory data

    async function loadInventoryData(query = '') {
        // Load both stats and the full inventory list
        await Promise.all([loadInventoryStats(), loadFullInventory(query)]);
    }

    async function loadInventoryStats() {
        const stats = await fetchAdminData('getInventoryStats');
        if (stats) {
            const formatCurrency = (value) => new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value);
            document.getElementById('stat-total-parts').textContent = stats.total_parts || 0;
            document.getElementById('stat-total-value').textContent = formatCurrency(stats.total_value || 0);
            document.getElementById('stat-low-stock').textContent = stats.low_stock || 0;
            document.getElementById('stat-out-of-stock').textContent = stats.out_of_stock || 0;
        }
    }

    async function loadFullInventory(query = '') {
        allParts = await fetchAdminData(`getInventory${query ? `&search=${query}` : ''}`);
        const tableBody = document.getElementById('inventory-table-body');
        tableBody.innerHTML = '';

        if (allParts && allParts.length > 0) {
            allParts.forEach(part => {
                let statusBadge;
                if (part.stock_level <= 0) {
                    statusBadge = '<span class="bg-red-500/20 text-red-400 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Out of Stock</span>';
                } else if (part.stock_level <= part.threshold) {
                    statusBadge = '<span class="bg-yellow-500/20 text-yellow-400 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Low Stock</span>';
                } else {
                    statusBadge = '<span class="bg-green-500/20 text-green-400 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">In Stock</span>';
                }

                const row = `
                    <tr class="border-b border-card-border last:border-b-0 hover:bg-white/5">
                        <th class="px-6 py-4 font-medium whitespace-nowrap text-text-primary flex items-center gap-3" scope="row">
                            <img alt="${part.part_name}" class="w-10 h-10 object-cover rounded bg-gray-700" src="${part.image_url || '../../assets/img/placeholders/placeholder.png'}" />
                            ${part.part_name}
                        </th>
                        <td class="px-6 py-4 text-text-muted">${part.part_id}</td>
                        <td class="px-6 py-4 text-text-muted">${part.supplier || 'N/A'}</td>
                        <td class="px-6 py-4 text-right font-medium text-text-primary">${part.stock_level}</td>
                        <td class="px-6 py-4 text-right text-text-muted">₱${parseFloat(part.price).toFixed(2)}</td>
                        <td class="px-6 py-4">${statusBadge}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="edit-part-btn text-blue-400 hover:text-blue-300 text-sm font-semibold" data-id="${part.id}">Edit</button>
                            <button class="delete-part-btn text-red-400 hover:text-red-300 text-sm font-semibold" data-id="${part.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr class="border-b border-card-border"><td colspan="7" class="px-6 py-4 text-center text-text-muted">No parts found in inventory.</td></tr>`;
        }
    }

    // Search for inventory
    const inventorySearch = document.getElementById('inventory-search');
    inventorySearch.addEventListener('input', (e) => loadInventoryData(e.target.value));

    // Inventory Modal Logic
    const partModal = document.getElementById('part-modal');
    const closePartModalBtn = document.getElementById('close-part-modal');
    const addNewPartBtn = document.getElementById('add-new-part-btn');
    const partForm = document.getElementById('part-form');
    const partModalTitle = document.getElementById('part-modal-title');

    function openPartModal(partId = null) {
        partForm.reset();
        document.getElementById('part-id-input').value = '';
        if (partId) {
            partModalTitle.textContent = 'Edit Part';
            const part = allParts.find(p => p.id == partId);
            if (part) {
                document.getElementById('part-id-input').value = part.id;
                document.getElementById('part-name').value = part.part_name;
                document.getElementById('part-sku').value = part.part_id;
                document.getElementById('part-supplier').value = part.supplier;
                document.getElementById('part-price').value = part.price;
                document.getElementById('part-stock').value = part.stock_level;
                document.getElementById('part-threshold').value = part.threshold;
                document.getElementById('part-image-url').value = part.image_url;
            }
        } else {
            partModalTitle.textContent = 'Add New Part';
        }
        partModal.classList.remove('hidden');
    }

    function closePartModal() {
        partModal.classList.add('hidden');
    }

    async function handlePartFormSubmit(e) {
        e.preventDefault();
        const formData = new FormData(partForm);
        const data = Object.fromEntries(formData.entries());
        const action = data.id ? 'updatePart' : 'addPart';

        const response = await fetch(`${API_URL}?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            Swal.fire('Success!', result.message, 'success');
            closePartModal();
            loadInventoryData(); // Refresh stats and table
        } else {
            Swal.fire('Error!', result.error || 'An unknown error occurred.', 'error');
        }
    }

    // --- SERVICES MANAGEMENT ---
    let allServices = [];

    async function loadServices() {
        allServices = await fetchAdminData('getServices');
        const tableBody = document.getElementById('services-table-body');
        tableBody.innerHTML = '';

        if (allServices && allServices.length > 0) {
            allServices.forEach(service => {
                const row = `
                    <tr class="border-b border-card-border last:border-b-0 hover:bg-white/5">
                        <td class="px-6 py-4">
                            <img src="../../${service.image_url || 'assets/img/placeholders/placeholder.png'}" alt="${service.name}" class="w-16 h-10 object-cover rounded-md bg-gray-700">
                        </td>
                        <td class="px-6 py-4 font-medium text-text-primary">${service.name}</td>
                        <td class="px-6 py-4 text-text-muted max-w-sm truncate" title="${service.description}">${service.description}</td>
                        <td class="px-6 py-4 text-right font-mono text-text-primary">₱${parseFloat(service.price).toFixed(2)}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="edit-service-btn text-blue-400 hover:text-blue-300 text-sm font-semibold" data-id="${service.id}">Edit</button>
                            <button class="delete-service-btn text-red-400 hover:text-red-300 text-sm font-semibold" data-id="${service.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr class="border-b border-card-border"><td colspan="4" class="px-6 py-4 text-center text-text-muted">No services found. Click 'Add New Service' to begin.</td></tr>`;
        }
    }

    function setupServiceModal() {
        const modal = document.getElementById('service-modal');
        const form = document.getElementById('service-form');
        const title = document.getElementById('service-modal-title');
        const idInput = document.getElementById('service-id-input');

        const openModal = (serviceId = null) => {
            form.reset();
            idInput.value = '';
            if (serviceId) {
                title.textContent = 'Edit Service';
                const service = allServices.find(s => s.id == serviceId);
                if (service) {
                    idInput.value = service.id;
                    document.getElementById('service-name').value = service.name;
                    document.getElementById('service-description').value = service.description;
                    document.getElementById('service-price').value = service.price;
                    document.getElementById('service-category').value = service.category;
                    document.getElementById('service-image-url').value = service.image_url;
                }
            } else {
                title.textContent = 'Add New Service';
            }
            modal.classList.remove('hidden');
        };

        const closeModal = () => modal.classList.add('hidden');

        document.getElementById('add-new-service-btn').addEventListener('click', () => openModal());
        document.getElementById('close-service-modal').addEventListener('click', closeModal);

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const action = data.id ? 'updateService' : 'addService';

            const response = await fetch(`${API_URL}?action=${action}`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
            const result = await response.json();

            if (result.success) {
                Swal.fire('Success!', result.message, 'success');
                closeModal();
                loadServices();
            } else { Swal.fire('Error!', result.error || 'An error occurred.', 'error'); }
        });

        document.getElementById('services-table-body').addEventListener('click', (e) => {
            if (e.target.classList.contains('edit-service-btn')) {
                openModal(e.target.dataset.id);
            }
            if (e.target.classList.contains('delete-service-btn')) {
                const serviceId = e.target.dataset.id;
                Swal.fire({
                    title: 'Are you sure?', text: "This service will be permanently deleted!", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await fetch(`${API_URL}?action=deleteService`, { method: 'POST', body: JSON.stringify({ id: serviceId }) });
                        loadServices();
                    }
                });
            }
        });
    }

    // New Booking Modal Functionality
const newBookingBtn = document.getElementById('new-booking-btn-dashboard');
const newBookingModal = document.getElementById('new-booking-modal');
const closeModalBtn = document.getElementById('close-new-booking-modal');
const cancelBtn = document.getElementById('cancel-booking-btn');
const adminBookingForm = document.getElementById('admin-booking-form');

function openNewBookingModal() {
    newBookingModal.classList.remove('hidden');
    loadCustomersForBooking();
    loadServicesForBooking();
    loadTechniciansForBooking();
}

function closeNewBookingModal() {
    newBookingModal.classList.add('hidden');
    adminBookingForm.reset();
}

newBookingBtn?.addEventListener('click', openNewBookingModal);
closeModalBtn?.addEventListener('click', closeNewBookingModal);
cancelBtn?.addEventListener('click', closeNewBookingModal);

// Load Customers
async function loadCustomersForBooking() {
    const select = adminBookingForm.querySelector('[name="customer_id"]');
    select.innerHTML = '<option>Loading...</option>';
    const res = await fetch('../../Backend/admindash-backend/admin-backend.php?action=getAllCustomers');
    const customers = await res.json();
    select.innerHTML = '<option value="">Select Customer</option>';
    customers.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = `${c.username} (${c.email})`;
        select.appendChild(opt);
    });
}

// Load Customer's Vehicles
adminBookingForm.querySelector('[name="customer_id"]').addEventListener('change', async function() {
    const vehicleSelect = adminBookingForm.querySelector('[name="vehicle_id"]');
    vehicleSelect.innerHTML = '<option>Loading vehicles...</option>';
    if (!this.value) {
        vehicleSelect.innerHTML = '<option>First select customer</option>';
        return;
    }
    const res = await fetch(`../../Backend/customerdash-backend/customer-backend.php?action=getVehicles&user_id=${this.value}`);
    const vehicles = await res.json();
    vehicleSelect.innerHTML = '<option value="">Select Vehicle</option>';
    vehicles.forEach(v => {
        const opt = document.createElement('option');
        opt.value = v.id;
        opt.textContent = `${v.nickname || 'Unnamed'} (${v.make} ${v.model} ${v.year})`;
        vehicleSelect.appendChild(opt);
    });
});

// Load Services & Technicians
async function loadServicesForBooking() {
    const select = adminBookingForm.querySelector('[name="package_name"]');
    const res = await fetch('../../Backend/admindash-backend/admin-backend.php?action=getServices');
    const services = await res.json();
    services.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.name;
        opt.textContent = `${s.name} - ₱${parseFloat(s.price).toLocaleString()}`;
        select.appendChild(opt);
    });
}

async function loadTechniciansForBooking() {
    const select = adminBookingForm.querySelector('[name="technician_id"]');
    const res = await fetch('../../Backend/admindash-backend/admin-backend.php?action=getTechnicians');
    const techs = await res.json();
    techs.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = `${t.name}${t.specialty ? ` - ${t.specialty}` : ''}`;
        select.appendChild(opt);
    });
}

// Submit Booking
adminBookingForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('admin_created', '1'); // Flag for backend

    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = 'Creating...';

    const res = await fetch('../../Backend/booking-handler.php', {
        method: 'POST',
        body: formData
    });

    const result = await res.text();

    if (res.ok && result.includes('booking=success')) {
        Swal.fire('Success!', 'Booking created successfully!', 'success');
        closeNewBookingModal();
        loadAllBookings(); // Refresh bookings table
    } else {
        Swal.fire('Error', 'Failed to create booking. Check console.', 'error');
        console.log(result);
    }

    btn.disabled = false;
    btn.innerHTML = 'Create Booking';
});

    // --- ORDERS MANAGEMENT ---
    async function loadOrders(query = '') {
        const orders = await fetchAdminData(`getOrders${query ? `&search=${query}` : ''}`);
        const tableBody = document.getElementById('orders-table-body');
        tableBody.innerHTML = '';

        if (orders && orders.length > 0) {
            orders.forEach(order => {
                const orderDate = new Date(order.order_date);
                const formattedDate = orderDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                let statusBadge;
                switch (order.status) {
                    case 'Processing': statusBadge = '<span class="bg-blue-500/20 text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Processing</span>'; break;
                    case 'Shipped': statusBadge = '<span class="bg-purple-500/20 text-purple-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Shipped</span>'; break;
                    case 'Completed': statusBadge = '<span class="bg-green-500/20 text-green-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Completed</span>'; break;
                    case 'Cancelled': statusBadge = '<span class="bg-red-500/20 text-red-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Cancelled</span>'; break;
                    default: statusBadge = `<span class="bg-gray-500/20 text-gray-400 text-xs font-medium px-2.5 py-0.5 rounded-full">${order.status}</span>`;
                }

                const row = `
                    <tr class="border-b border-card-border last:border-b-0 hover:bg-white/5">
                        <td class="px-6 py-4 font-mono text-text-muted">#${order.id}</td>
                        <td class="px-6 py-4 font-medium text-text-primary">${order.customer_name}</td>
                        <td class="px-6 py-4 text-text-muted text-xs">${order.items.replace(/; /g, '<br>')}</td>
                        <td class="px-6 py-4 text-right font-mono text-primary">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                        <td class="px-6 py-4 text-text-muted">${order.payment_method}</td>
                        <td class="px-6 py-4 text-text-muted">${formattedDate}</td>
                        <td class="px-6 py-4 text-center">${statusBadge}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="edit-order-status-btn text-blue-400 hover:text-blue-300 text-sm font-semibold" data-id="${order.id}" data-status="${order.order_status}">
                                Change Status
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr class="border-b border-card-border"><td colspan="8" class="px-6 py-4 text-center text-text-muted">No orders found.</td></tr>`;
        }
    }

    // Search for orders
    document.getElementById('orders-search').addEventListener('input', (e) => loadOrders(e.target.value));

    // Order Status Modal Logic
    function setupOrderStatusModal() {
        const modal = document.getElementById('order-status-modal');
        const form = document.getElementById('order-status-form');
        const idInput = document.getElementById('order-id-input');
        const statusSelect = document.getElementById('order-status-select');

        const openModal = (orderId, currentStatus) => {
            idInput.value = orderId;
            statusSelect.value = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            modal.classList.remove('hidden');
        };

        const closeModal = () => modal.classList.add('hidden');

        document.getElementById('close-order-status-modal').addEventListener('click', closeModal);

        document.getElementById('orders-table-body').addEventListener('click', (e) => {
            if (e.target.classList.contains('edit-order-status-btn')) {
                openModal(e.target.dataset.id, e.target.dataset.status);
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const orderId = idInput.value;
            const newStatus = statusSelect.value.toLowerCase(); // Convert to lowercase

            const response = await fetch(`${API_URL}?action=updateOrderStatus`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            });
            const result = await response.json();

            if (result.success) {
                Swal.fire('Success!', 'Order status updated.', 'success');
                closeModal();
                loadOrders(); // Refresh the table
            } else {
                Swal.fire('Error!', result.error || 'An error occurred.', 'error');
            }
        });
    }

    // --- INVOICES MANAGEMENT ---
    async function loadInvoices(query = '') {
        // We can reuse the getOrders endpoint for invoices, perhaps with a filter for 'completed'
        const invoices = await fetchAdminData(`getOrders${query ? `&search=${query}` : ''}`);
        const tableBody = document.getElementById('invoices-table-body');
        tableBody.innerHTML = '';

        if (invoices && invoices.length > 0) {
            invoices.forEach(invoice => {
                const orderDate = new Date(invoice.order_date);
                const formattedDate = orderDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                let statusBadge;
                switch (invoice.order_status) {
                    case 'processing': statusBadge = '<span class="bg-blue-500/20 text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Processing</span>'; break;
                    case 'shipped': statusBadge = '<span class="bg-purple-500/20 text-purple-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Shipped</span>'; break;
                    case 'completed': statusBadge = '<span class="bg-green-500/20 text-green-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Completed</span>'; break;
                    case 'cancelled': statusBadge = '<span class="bg-red-500/20 text-red-400 text-xs font-medium px-2.5 py-0.5 rounded-full">Cancelled</span>'; break;
                    default: statusBadge = `<span class="bg-gray-500/20 text-gray-400 text-xs font-medium px-2.5 py-0.5 rounded-full">${invoice.order_status}</span>`;
                }

                const row = `
                    <tr class="border-b border-card-border last:border-b-0 hover:bg-white/5">
                        <td class="px-6 py-4 font-mono text-text-muted">#${invoice.id}</td>
                        <td class="px-6 py-4 font-medium text-text-primary">${invoice.customer_name}</td>
                        <td class="px-6 py-4 text-text-muted text-xs">${invoice.items.replace(/; /g, '<br>')}</td>
                        <td class="px-6 py-4 text-right font-mono text-primary">₱${parseFloat(invoice.total_amount).toFixed(2)}</td>
                        <td class="px-6 py-4 text-text-muted">${formattedDate}</td>
                        <td class="px-6 py-4 text-center">${statusBadge}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr class="border-b border-card-border"><td colspan="6" class="px-6 py-4 text-center text-text-muted">No invoices found.</td></tr>`;
        }
    }

    // Search for invoices
    document.getElementById('invoices-search').addEventListener('input', (e) => {
        const query = e.target.value;
        // We can filter client-side or server-side. Server-side is better for large datasets.
        loadInvoices(query);
    });




    // --- REPORTS SECTION ---
    async function loadReportData() {
        const reportData = await fetchAdminData('getReportData');
        if (!reportData) {
            console.error("Failed to load report data.");
            return;
        }

        const formatCurrency = (value) => new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value);

        // Populate stat cards
        document.getElementById('report-total-repairs').textContent = reportData.total_repairs || 0;
        document.getElementById('report-total-revenue').textContent = formatCurrency(reportData.total_revenue || 0);
        document.getElementById('report-inventory-value').textContent = formatCurrency(reportData.inventory_value || 0);

        // Populate revenue report table
        const reportTableBody = document.getElementById('revenue-report-table-body');
        reportTableBody.innerHTML = ''; // Clear existing

        if (reportData.recent_revenue_report && reportData.recent_revenue_report.length > 0) {
            reportData.recent_revenue_report.forEach(item => {
                const dateObj = new Date(item.appointment_date);
                const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                const row = `
                    <tr class="hover:bg-white/5">
                        <td class="p-4">${formattedDate}</td>
                        <td class="p-4">${item.service_name}</td>
                        <td class="p-4">${item.customer_name}</td>
                        <td class="p-4">${item.technician_name}</td>
                        <td class="p-4 text-right font-mono">${formatCurrency(item.amount)}</td>
                    </tr>
                `;
                reportTableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            reportTableBody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-text-muted">No revenue data for the last 30 days.</td></tr>`;
        }
    }

    // --- TRAINER SCHEDULE ---
    async function loadTrainerSchedule(query = '') {
        const bookings = await fetchAdminData(`getAllBookings${query ? `&search=${query}` : ''}`);
        const tableBody = document.getElementById('schedule-table-body');
        tableBody.innerHTML = ''; // Clear existing rows

        if (bookings && bookings.length > 0) {
            bookings.forEach(booking => {
                const dateObj = new Date(booking.appointment_date);
                const formattedDate = dateObj.toLocaleDate-String('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                
                const [hours, minutes] = booking.appointment_time.split(':');
                const timeObj = new Date();
                timeObj.setHours(hours, minutes);
                const formattedTime = timeObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

                let statusBadge = '';
                let statusClass = '';
                switch (booking.status) {
                    case 'pending': statusClass = 'bg-yellow-100 text-yellow-800'; break;
                    case 'confirmed': statusClass = 'bg-green-100 text-green-800'; break;
                    case 'completed': statusClass = 'bg-blue-100 text-blue-800'; break;
                    case 'cancelled': statusClass = 'bg-red-100 text-red-800'; break;
                    default: statusClass = 'bg-gray-100 text-gray-800';
                }
                statusBadge = `<span class="px-2 py-1 rounded-full text-xs font-bold ${statusClass}">${booking.status.toUpperCase()}</span>`;

                const row = `
                    <tr class="border-b border-card-border hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 text-text-muted">${formattedDate}</td>
                        <td class="px-6 py-4 text-text-muted">${formattedTime}</td>
                        <td class="px-6 py-4 font-medium text-text-primary">${booking.technician_name}</td>
                        <td class="px-6 py-4 text-text-muted">${booking.customer_name}</td>
                        <td class="px-6 py-4 text-text-muted">${booking.package_name}</td>
                        <td class="px-6 py-4 text-center">${statusBadge}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-text-muted">No schedules found.</td></tr>`;
        }
    }

    // Search for schedules
    const scheduleSearch = document.getElementById('schedule-search');
    scheduleSearch.addEventListener('input', (e) => {
        const query = e.target.value;
        // Re-use the booking search for the schedule view
        loadTrainerSchedule(query);
    });


    // 9. Notification Logic
    function setupNotifications() {
        const bell = document.getElementById('notification-bell');
        const panel = document.getElementById('notification-panel');
        const list = document.getElementById('notification-list');
        const badge = document.getElementById('notification-badge');
        const markAllReadBtn = document.getElementById('mark-all-read-btn');
        let unreadIds = [];

        async function loadNotifications() {
            const notifications = await fetchAdminData('getNotifications');
            list.innerHTML = '';
            unreadIds = [];
            let unreadCount = 0;

            if (notifications && notifications.length > 0) {
                notifications.forEach(n => {
                    if (n.is_read == 0) {
                        unreadCount++;
                        unreadIds.push(n.id);
                    }
                    const timeAgo = formatTimeAgo(n.created_at);
                    const item = document.createElement('a');
                    item.href = n.link || '#';
                    item.className = `block p-3 rounded-lg hover:bg-white/5 ${n.is_read == 0 ? 'bg-primary/5' : ''}`;
                    item.innerHTML = `
                        <div class="flex justify-between items-start">
                            <p class="font-semibold text-text-primary text-sm">${n.title}</p>
                            ${n.is_read == 0 ? '<div class="size-2 bg-primary rounded-full flex-shrink-0 mt-1.5 ml-2"></div>' : ''}
                        </div>
                        <p class="text-text-muted text-sm mt-1">${n.message}</p>
                        <p class="text-xs text-text-muted/70 mt-2">${timeAgo}</p>
                    `;
                    list.appendChild(item);
                });
            } else {
                list.innerHTML = '<p class="text-center text-text-muted p-4">No notifications yet.</p>';
            }

            badge.textContent = unreadCount;
            badge.classList.toggle('hidden', unreadCount === 0);
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

        bell.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                // Mark as read after a short delay when opening
                setTimeout(() => markAsRead(unreadIds), 2000);
            }
        });

        markAllReadBtn.addEventListener('click', () => markAsRead(unreadIds));
        document.addEventListener('click', (e) => {
            if (!panel.contains(e.target) && !bell.contains(e.target)) {
                panel.classList.add('hidden');
            }
        });

        loadNotifications();
        setInterval(loadNotifications, 60000); // Refresh every minute
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString + ' UTC');
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " years ago";
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " months ago";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days ago";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hours ago";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " minutes ago";
        return "Just now";
    }

    // Load all data on page load
    document.addEventListener('DOMContentLoaded', () => {
        // Check if user is logged in as admin, otherwise redirect
        // This is a client-side check, the backend provides the real security
        <?php
            if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
                echo "window.location.href = '../auth/login.php';";
            }
        ?>
        loadAdminDetails();
        loadDashboardStats();
        loadLowStockParts();
        loadAtRiskCustomers();
        loadAllBookings();
        loadLoginHistory();
        loadInventoryData();
        loadContactMessages();
        setupNotifications();
        loadServices();
        loadReportData();
        setupServiceModal();
        loadInvoices();
        loadTrainerSchedule(); // Load trainer schedule on page load
        loadOrders();
        setupOrderStatusModal();

        // Schedule section new appointment button
             // The :has selector is not well-supported in querySelector, so we'll do it manually.
        const scheduleSection = document.getElementById('schedule');
        if (scheduleSection) {
            const scheduleButtons = scheduleSection.querySelectorAll('button');
            scheduleButtons.forEach(button => {
                if (button.textContent.includes('New Appointment')) {
                    button.addEventListener('click', openNewBookingModal);
                }
            });
        }
        // Services section add new service button
        document.getElementById('add-new-service-btn').addEventListener('click', () => document.getElementById('service-modal').classList.remove('hidden'));

        // Add event listener for status update buttons
        // document.getElementById('all-bookings-table').addEventListener('click', handleUpdateStatus);
        document.getElementById('contact-messages-table').addEventListener('click', handleContactAction);

        // Inventory event listeners
        addNewPartBtn.addEventListener('click', () => openPartModal());
        closePartModalBtn.addEventListener('click', closePartModal);
        partForm.addEventListener('submit', handlePartFormSubmit);
        document.getElementById('inventory-table-body').addEventListener('click', (e) => {
            if (e.target.classList.contains('edit-part-btn')) {
                openPartModal(e.target.dataset.id);
            }
            if (e.target.classList.contains('delete-part-btn')) {
                const partId = e.target.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await fetch(`${API_URL}?action=deletePart`, { method: 'POST', body: JSON.stringify({ id: partId }) });
                        loadInventoryData();
                    }
                });
            }
        });
    });
  </script>
</body>

</html>
</body>

</html>