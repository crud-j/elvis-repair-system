-- ================================================
-- ELVIS AUTO REPAIR DATABASE - FINAL INFINITYFREE & PHPMYADMIN COMPATIBLE
-- Tested & Working 100% - November 21, 2025
-- Just paste entire script into phpMyAdmin → SQL → Go
-- ================================================

CREATE DATABASE IF NOT EXISTS avnadmin DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE avnadmin;

-- ================================================
-- 1. USERS TABLE
-- ================================================
CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  password varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  role enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  profile_picture varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  verification_code varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  is_verified tinyint(1) NOT NULL DEFAULT 0,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO users (id, username, email, password, role, is_verified) VALUES
(1, 'Admin', 'support.admin@elvis.com', '$2y$10$0ZOOeFN/UwpPjFialGJv9.Y6w/DW0sqru8doTv3DI/YgHRojRm2B2', 'admin', 1);
-- ================================================
-- 1.1 USER ADDRESSES
-- ================================================
CREATE TABLE IF NOT EXISTS user_addresses (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  address_line1 VARCHAR(255) NOT NULL,
  address_line2 VARCHAR(255) NULL,
  city VARCHAR(100) NOT NULL,
  state_province_region VARCHAR(100) NOT NULL,
  postal_code VARCHAR(20) NOT NULL,
  country VARCHAR(100) NOT NULL,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO user_addresses (id, user_id, address_line1, city, state_province_region, postal_code, country, is_default) VALUES
(1, 2, '123 Graceland Hwy', 'Memphis', 'Tennessee', '38116', 'USA', 1);

-- ================================================
-- 2. TECHNICIANS
-- ================================================
CREATE TABLE IF NOT EXISTS technicians (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  specialty varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO technicians (id, name, specialty) VALUES
(1, 'John Doe', 'Engine Specialist'),
(2, 'Jane Smith', 'Brake & Suspension Expert');

-- ================================================
-- 3. VEHICLES
-- ================================================
CREATE TABLE IF NOT EXISTS vehicles (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  vin varchar(17) COLLATE utf8mb4_unicode_ci NOT NULL,
  nickname varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  make varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  model varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  year varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  plate_number varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  car_photo_url varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  issues text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO vehicles (id, user_id, vin, nickname, make, model, year) VALUES
(1, 2, 'VIN123456789', 'My Car', 'Toyota', 'Camry', '2020');

-- ================================================
-- 4. SERVICES
-- ================================================
CREATE TABLE IF NOT EXISTS services (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  price decimal(10,2) NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE services ADD COLUMN est_time varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER price;
ALTER TABLE services ADD COLUMN  category varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER est_time;
ALTER TABLE services ADD COLUMN image_url VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER price;
ALTER TABLE services ADD COLUMN  recommended_for VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER category;

INSERT IGNORE INTO services (name, description, price, image_url, category) VALUES
('Engine Repair', 'Expert diagnostics and repair for engine issues.', 8500.00, 'assets/img/Services/services-1.png', 'repair'),
('Oil Change', 'Regular oil changes to keep your engine running smoothly.', 1200.00, 'assets/img/Services/services-2.png', 'maintenance'),
('Brake Service', 'Brake inspection, repair, and replacement.', 3500.00, 'assets/img/Services/services-3.png', 'repair'),
('Tire Services', 'Tire mounting, rotation, and balancing.', 2800.00, 'assets/img/Services/services-4.png', 'maintenance'),
('Suspension & Alignment', 'Suspension checks and wheel alignment.', 4800.00, 'assets/img/Services/services-5.png', 'maintenance'),
('Battery & Electrical', 'Electrical diagnostics and battery replacement.', 2500.00, 'assets/img/Services/services-6.png', 'diagnostics'),
('Air Conditioning', 'A/C diagnostics, refill, and repair.', 3800.00, 'assets/img/Services/services-7.png', 'repair'),
('Transmission Repair', 'Transmission rebuilds and fluid service.', 15000.00, 'assets/img/Services/services-8.png', 'repair'),
('Vehicle Inspection', 'Full safety and performance check.', 1500.00, 'assets/img/Services/services-9.png', 'diagnostics');

-- ================================================
-- 5. APPOINTMENTS
-- ================================================
CREATE TABLE IF NOT EXISTS appointments (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  vehicle_id int(11) DEFAULT NULL,
  vehicle_name varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  service_id int(11) DEFAULT NULL,
  technician_id int(11) NOT NULL,
  appointment_date date NOT NULL,
  appointment_time time NOT NULL,
  notes text COLLATE utf8mb4_unicode_ci,
  status enum('pending','confirmed','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  payment_reference varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gcash_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  amount decimal(10,2) DEFAULT NULL,
  package_name varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
  FOREIGN KEY (technician_id) REFERENCES technicians(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE appointments ADD COLUMN technician_notes text COLLATE utf8mb4_unicode_ci AFTER notes;

-- ================================================
-- 6. INVENTORY (FULL SAMPLE DATA)
-- ================================================
CREATE TABLE IF NOT EXISTS inventory (
  id int(11) NOT NULL AUTO_INCREMENT,
  part_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  part_id varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  stock_level int(11) NOT NULL,
  threshold int(11) NOT NULL DEFAULT 10,
  supplier varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  price decimal(10,2) NOT NULL,
  image_url text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY part_id (part_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO inventory (part_name, part_id, stock_level, threshold, supplier, price, image_url) VALUES
('Engine Oil 5W-30 Synthetic (4L)', 'EO-5W30-4L', 18, 10, 'Castrol EDGE', 2850.00, 'https://images.unsplash.com/photo-1621375146053-1e47da1fdf5b?w=800'),
('Engine Oil 10W-40 (4L)', 'EO-10W40-4L', 22, 10, 'Shell Helix', 2200.00, 'https://m.media-amazon.com/images/I/61DMV98wkKL._AC_UF1000,1000_QL80_.jpg'),
('Oil Filter', 'OF-101', 35, 15, 'Bosch', 650.00, 'https://www.shutterstock.com/image-illustration/oil-filter-isolated-on-white-260nw-2618195753.jpg'),
('Air Filter', 'AF-202', 28, 10, 'K&N', 950.00, 'https://m.media-amazon.com/images/I/61IsayyRN-L._AC_UF894,1000_QL80_.jpg'),
('Cabin Air Filter', 'CAF-303', 20, 10, 'Mann-Filter', 1200.00, 'https://c8.alamy.com/comp/WPPPCB/cabin-air-pollen-filter-car-on-white-background-3d-illustration-WPPPCB.jpg'),
('Brake Pads Front (Ceramic)', 'BP-F-404', 8, 8, 'Brembo', 4500.00, 'https://m.media-amazon.com/images/I/5139TChF-EL._AC_UF894,1000_QL80_.jpg'),
('Brake Disc/Rotor Front (Pair)', 'BD-F-606', 4, 5, 'Zimmermann', 8900.00, 'https://m.media-amazon.com/images/I/41OGQVIfQML._AC_UF894,1000_QL80_.jpg'),
('Spark Plugs (Set of 4)', 'SP-707', 40, 15, 'NGK Iridium', 2400.00, 'https://www.modernperformance.com/images/uploads/1183_10936_large.jpg'),
('Alternator 120A', 'ALT-909', 4, 3, 'Bosch Reman', 12500.00, 'https://www.idparts.com/images/2295_a4120aalternator.jpg'),
('Car Battery 65Ah', 'BAT-65AH', 10, 5, 'Motolite Gold', 6800.00, 'https://image.made-in-china.com/2f0j00VIropkOaaBbQ/Aokly-65ah-Maintenance-Free-Mf-Automotive-Auto-Battery-for-Japan-Automobile-Car-Factory-Wholesale-Price-78-675mf.webp');

-- ================================================
-- REMAINING TABLES (ALL COMPATIBLE)
-- ================================================
CREATE TABLE IF NOT EXISTS login_history (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  login_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip_address varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifications (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  message text COLLATE utf8mb4_unicode_ci NOT NULL,
  link varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  is_read tinyint(1) NOT NULL DEFAULT 0,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  total_amount decimal(10,2) NOT NULL,
  order_status enum('pending','processing','shipped','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  payment_status enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  payment_method varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  payment_reference VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gcash_number VARCHAR(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE orders ADD COLUMN shipping_address_id INT(11) NULL AFTER user_id;
ALTER TABLE orders ADD CONSTRAINT fk_shipping_address FOREIGN KEY (shipping_address_id) REFERENCES user_addresses(id) ON DELETE SET NULL;
ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL AFTER gcash_number;

CREATE TABLE IF NOT EXISTS order_items (
  id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  quantity int(11) NOT NULL,
  price_at_purchase decimal(10,2) NOT NULL,
  subtotal decimal(10,2) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES inventory(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS invoices (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  appointment_id int(11) DEFAULT NULL,
  order_id int(11) DEFAULT NULL,
  invoice_data text COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cart_items (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  quantity int(11) NOT NULL DEFAULT 1,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY user_product_unique (user_id, product_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES inventory(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  email VARCHAR(255) NOT NULL,
  token VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY token (token),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payments (
  payment_id int(11) NOT NULL AUTO_INCREMENT,
  booking_id int(11) NOT NULL,
  amount decimal(10,2) NOT NULL,
  currency varchar(3) NOT NULL DEFAULT 'PHP',
  payment_method varchar(50) NOT NULL,
  status enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  paymongo_payment_id varchar(255) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (payment_id),
  KEY booking_id (booking_id),
  CONSTRAINT payments_ibfk_1 FOREIGN KEY (booking_id) REFERENCES appointments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS stats_cache (
    id INT PRIMARY KEY,
    total_bookings INT,
    services_in_progress INT,
    weekly_revenue DECIMAL(10,2),
    monthly_revenue DECIMAL(10,2),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO stats_cache (id) VALUES (1);

-- ================================================
-- INDEXES
-- ================================================
ALTER TABLE appointments ADD INDEX idx_status_appointment_date (status, appointment_date);
ALTER TABLE notifications ADD INDEX idx_user_id_is_read (user_id, is_read);
ALTER TABLE login_history ADD INDEX idx_user_id_login_time (user_id, login_time);
ALTER TABLE invoices ADD INDEX idx_created_at (created_at);
ALTER TABLE inventory ADD INDEX idx_stock_level_threshold (stock_level, threshold);
ALTER TABLE inventory ADD INDEX idx_stock_level (stock_level);

-- ================================================
-- ALL DONE! Database ready for Elvis Auto Repair
-- ================================================
ALTER TABLE users MODIFY COLUMN is_verified TINYINT(1) DEFAULT 1;
select * from users;