-- SIDAI RESORT — Complete Database Schema
-- MySQL 8.0+ | UTF8MB4 Collation
-- Run this once to initialize the database

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET COLLATION_CONNECTION = utf8mb4_unicode_ci;

-- ━━ GUESTS TABLE ━━
CREATE TABLE IF NOT EXISTS guests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) UNIQUE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  id_number VARCHAR(30) UNIQUE,
  nationality VARCHAR(80),
  country_code VARCHAR(3),
  special_requests TEXT,
  newsletter_opt_in TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  KEY idx_email (email),
  KEY idx_phone (phone),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ ROOMS TABLE ━━
CREATE TABLE IF NOT EXISTS rooms (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  type ENUM('standard','deluxe','suite','pool_villa','honeymoon') NOT NULL,
  capacity INT NOT NULL DEFAULT 2,
  price_per_night DECIMAL(10,2) NOT NULL,
  discount_percentage DECIMAL(5,2) DEFAULT 0,
  description TEXT,
  amenities JSON,
  is_available TINYINT(1) DEFAULT 1,
  images JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_type (type),
  KEY idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ HALLS TABLE ━━
CREATE TABLE IF NOT EXISTS halls (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hall_number INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  capacity INT NOT NULL,
  capacity_details JSON,
  price_full_day DECIMAL(10,2) NOT NULL,
  price_half_day DECIMAL(10,2),
  price_evening DECIMAL(10,2),
  description TEXT,
  features JSON,
  setup_options JSON,
  is_available TINYINT(1) DEFAULT 1,
  images JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_capacity (capacity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ BOOKINGS TABLE ━━
CREATE TABLE IF NOT EXISTS bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_ref VARCHAR(20) UNIQUE NOT NULL,
  guest_id INT UNSIGNED NOT NULL,
  booking_type ENUM('room','pool','hall','dining','event','music_shoot','conference') NOT NULL,
  room_id INT UNSIGNED NULL,
  hall_id INT UNSIGNED NULL,
  check_in DATE NOT NULL,
  check_out DATE NULL,
  num_nights INT GENERATED ALWAYS AS (IF(check_out IS NOT NULL, DATEDIFF(check_out, check_in), 0)) STORED,
  event_date DATE NULL,
  event_type VARCHAR(100) NULL,
  event_setup ENUM('theatre','classroom','banquet','cocktail','u_shape') NULL,
  num_guests INT DEFAULT 1,
  subtotal DECIMAL(10,2) NOT NULL,
  discount_amount DECIMAL(10,2) DEFAULT 0,
  tax_amount DECIMAL(10,2) DEFAULT 0,
  total_amount DECIMAL(10,2) NOT NULL,
  deposit_amount DECIMAL(10,2) DEFAULT 0,
  balance_due DECIMAL(10,2) GENERATED ALWAYS AS (total_amount - deposit_amount) STORED,
  status ENUM('inquiry','pending','confirmed','checked_in','checked_out','cancelled') DEFAULT 'pending',
  payment_status ENUM('unpaid','partial','paid') DEFAULT 'unpaid',
  payment_method ENUM('mpesa','cash','bank','card') NULL,
  notes TEXT,
  cancellation_reason TEXT,
  cancelled_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE RESTRICT,
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
  FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE SET NULL,
  KEY idx_booking_ref (booking_ref),
  KEY idx_status (status),
  KEY idx_payment_status (payment_status),
  KEY idx_check_in (check_in),
  KEY idx_guest_id (guest_id),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ MENU CATEGORIES ━━
CREATE TABLE IF NOT EXISTS menu_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  KEY idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ MENU ITEMS ━━
CREATE TABLE IF NOT EXISTS menu_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(8,2) NOT NULL,
  image VARCHAR(255),
  is_vegetarian TINYINT(1) DEFAULT 0,
  is_spicy TINYINT(1) DEFAULT 0,
  is_available TINYINT(1) DEFAULT 1,
  allergen_info VARCHAR(255),
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE,
  KEY idx_category (category_id),
  KEY idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ ORDERS TABLE ━━
CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_ref VARCHAR(20) UNIQUE NOT NULL,
  booking_id INT UNSIGNED NULL,
  guest_id INT UNSIGNED NULL,
  items JSON NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  tax DECIMAL(10,2) DEFAULT 0,
  total DECIMAL(10,2) NOT NULL,
  delivery_type ENUM('dine_in','room_service','takeaway') DEFAULT 'dine_in',
  delivery_location VARCHAR(255),
  special_instructions TEXT,
  status ENUM('pending','preparing','ready','delivered','cancelled') DEFAULT 'pending',
  prepared_by INT UNSIGNED NULL,
  delivered_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE SET NULL,
  KEY idx_order_ref (order_ref),
  KEY idx_status (status),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ PAYMENTS TABLE ━━
CREATE TABLE IF NOT EXISTS payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  payment_ref VARCHAR(30) UNIQUE NOT NULL,
  booking_id INT UNSIGNED NULL,
  order_id INT UNSIGNED NULL,
  invoice_number VARCHAR(30),
  amount DECIMAL(10,2) NOT NULL,
  method ENUM('mpesa','cash','bank','card','cheque') NOT NULL,
  status ENUM('pending','completed','failed','refunded','disputed') DEFAULT 'pending',
  receipt_number VARCHAR(50) NULL,
  mpesa_checkout_request_id VARCHAR(100) NULL,
  mpesa_receipt_number VARCHAR(50) NULL,
  mpesa_phone VARCHAR(20) NULL,
  bank_reference VARCHAR(100) NULL,
  bank_name VARCHAR(100) NULL,
  cheque_number VARCHAR(50) NULL,
  cash_received_by INT UNSIGNED NULL,
  notes TEXT,
  refund_reason TEXT,
  refunded_amount DECIMAL(10,2) NULL,
  refunded_at TIMESTAMP NULL,
  paid_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
  KEY idx_payment_ref (payment_ref),
  KEY idx_mpesa_checkout (mpesa_checkout_request_id),
  KEY idx_status (status),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ GALLERY ITEMS ━━
CREATE TABLE IF NOT EXISTS gallery_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150),
  description TEXT,
  image_path VARCHAR(255) NOT NULL,
  image_thumb VARCHAR(255),
  category ENUM('pool','dining','events','rooms','nature','weddings','parties','exterior','interior','conference') NOT NULL,
  sort_order INT DEFAULT 0,
  is_featured TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  uploaded_by INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_category (category),
  KEY idx_featured (is_featured),
  KEY idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ TESTIMONIALS ━━
CREATE TABLE IF NOT EXISTS testimonials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  guest_name VARCHAR(100) NOT NULL,
  guest_email VARCHAR(200),
  rating TINYINT(1) NOT NULL CHECK (rating BETWEEN 1 AND 5),
  message TEXT NOT NULL,
  source ENUM('website','google','tripadvisor','booking','email') DEFAULT 'website',
  is_approved TINYINT(1) DEFAULT 0,
  is_featured TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_approved (is_approved),
  KEY idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ STAFF TABLE ━━
CREATE TABLE IF NOT EXISTS staff (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) UNIQUE NOT NULL,
  phone VARCHAR(20),
  role ENUM('super_admin','manager','receptionist','finance','kitchen','housekeeping') NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  last_login TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  remember_token_expires TIMESTAMP NULL,
  two_factor_enabled TINYINT(1) DEFAULT 0,
  two_factor_secret VARCHAR(100) NULL,
  login_attempts INT DEFAULT 0,
  locked_until TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  KEY idx_email (email),
  KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ AUDIT LOG ━━
CREATE TABLE IF NOT EXISTS audit_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  staff_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(50) NOT NULL,
  entity_id INT UNSIGNED NULL,
  old_values JSON NULL,
  new_values JSON NULL,
  ip_address VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_entity (entity_type, entity_id),
  KEY idx_staff (staff_id),
  KEY idx_created_at (created_at),
  KEY idx_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ CONTACT MESSAGES ━━
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL,
  phone VARCHAR(20),
  subject VARCHAR(200),
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  read_at TIMESTAMP NULL,
  replied_at TIMESTAMP NULL,
  reply_message TEXT,
  assigned_to INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (assigned_to) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_is_read (is_read),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ NEWSLETTER SUBSCRIBERS ━━
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(200) UNIQUE NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  verified_at TIMESTAMP NULL,
  unsubscribed_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_email (email),
  KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ SITE SETTINGS ━━
CREATE TABLE IF NOT EXISTS site_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) UNIQUE NOT NULL,
  setting_value TEXT,
  setting_type ENUM('string','integer','boolean','json','email','phone','url') DEFAULT 'string',
  setting_group VARCHAR(50),
  updated_by INT UNSIGNED NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (updated_by) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_key (setting_key),
  KEY idx_group (setting_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ ROOM AVAILABILITY ━━
CREATE TABLE IF NOT EXISTS room_availability (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_id INT UNSIGNED NOT NULL,
  date_from DATE NOT NULL,
  date_to DATE NOT NULL,
  booking_id INT UNSIGNED NULL,
  status ENUM('available','booked','maintenance','blocked') DEFAULT 'available',
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  KEY idx_room_date (room_id, date_from, date_to),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ HALL AVAILABILITY ━━
CREATE TABLE IF NOT EXISTS hall_availability (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hall_id INT UNSIGNED NOT NULL,
  date_from DATE NOT NULL,
  date_to DATE NOT NULL,
  booking_id INT UNSIGNED NULL,
  time_slot ENUM('full_day','half_day_am','half_day_pm','evening') DEFAULT 'full_day',
  status ENUM('available','booked','maintenance','blocked') DEFAULT 'available',
  FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE CASCADE,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  KEY idx_hall_date (hall_id, date_from, date_to)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ INSERT DEFAULT STAFF ACCOUNT ━━
-- Password: "Admin@1234" (change immediately in production)
INSERT IGNORE INTO staff (full_name, email, phone, role, password_hash, is_active)
VALUES ('Administrator', 'admin@sidairesort.com', '+254720000000', 'super_admin', 
        '$2y$10$V5z/hqSeZf6qFgLbquQf9OfZQZ7zcV4nqGwwswDyGY/NxpufbEcRu', 1);

-- ━━ INSERT SAMPLE MENU CATEGORIES ━━
INSERT IGNORE INTO menu_categories (name, description, sort_order)
VALUES 
  ('Breakfast', 'Morning delights', 1),
  ('Lunch', 'Midday specials', 2),
  ('Dinner', 'Evening cuisine', 3),
  ('Appetizers', 'Starters and small bites', 4),
  ('Main Courses', 'Signature dishes', 5),
  ('Desserts', 'Sweet endings', 6),
  ('Beverages', 'Drinks and refreshments', 7),
  ('Cocktails', 'Alcoholic cocktails', 8),
  ('Wines', 'Wine selection', 9),
  ('Juices', 'Fresh juices', 10);

-- ━━ AUDIT LOG TABLE ━━
CREATE TABLE IF NOT EXISTS audit_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  staff_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(50) NOT NULL,
  entity_id INT UNSIGNED NULL,
  old_values JSON NULL,
  new_values JSON NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_action (action),
  KEY idx_entity (entity_type, entity_id),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━ SITE SETTINGS TABLE ━━
CREATE TABLE IF NOT EXISTS site_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  setting_type ENUM('string','integer','boolean','json','email') DEFAULT 'string',
  setting_group VARCHAR(50) DEFAULT 'general',
  updated_by INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (updated_by) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_group (setting_group),
  UNIQUE KEY uk_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Done!

-- ?? INSERT MAASAI ROOMS (11 Rooms) ??
INSERT IGNORE INTO rooms (room_number, name, type, capacity, price_per_night, description, amenities) VALUES
('101', 'Elalai', 'suite', 4, 3200.00, 'Elalai is where nothing but the best stops being a promise and becomes a lived experience. Our most distinguished sanctuary.', '["Panoramic Forest View", "King Super Bed", "Private Seating Deck", "Premium Wi-Fi"]'),
('102', 'Emirishoi', 'standard', 2, 2000.00, 'Step into Emirishoi and feel the morning claim you gently. This sanctuary wakes with the forest.', '["Forest View", "King Bed", "Wi-Fi"]'),
('103', 'Enchipai', 'standard', 2, 1200.00, 'Enchipai wraps around you like the warmth of a fire you didn''t know you needed. Rich earth tones mirror the landscape.', '["Garden View", "Queen Bed", "Wi-Fi"]'),
('104', 'Eserian', 'standard', 2, 1500.00, 'Eserian is grace made tangible. The room moves with a soft elegance - clean lines softened by warm textures.', '["Canopy View", "Double Bed", "Wi-Fi"]'),
('105', 'Empiris', 'deluxe', 3, 2500.00, 'There is something deeply grounding about Empiris. Like the ancient trees that surround it, this sanctuary stands firm.', '["Forest View", "King Bed", "Wi-Fi"]'),
('106', 'Ewangan', 'deluxe', 2, 1800.00, 'Ewangan is a homecoming for people who have never been here before. It holds the particular magic of a place that feels familiar.', '["Nature View", "Queen Bed", "Wi-Fi"]'),
('107', 'Enkanasa', 'deluxe', 4, 1900.00, 'Enkanasa holds an energy that encourages lingering. Morning coffee takes twice as long here - in the best possible way.', '["Courtyard View", "Double Bed", "Wi-Fi"]'),
('108', 'Esipil', 'standard', 2, 1400.00, 'Named for the cool and flowing, Esipil carries the energy of a mountain stream - fresh, clear, and endlessly renewing.', '["Garden View", "Twin Beds", "Wi-Fi"]'),
('109', 'Enkipai', 'suite', 2, 2800.00, 'Enkipai is, in the truest sense, a blessed space. There is a quality of light here - warm even on overcast days.', '["Forest View", "King Bed", "Balcony Access"]'),
('110', 'Eripoto', 'deluxe', 3, 1700.00, 'Eripoto rewards those who pay attention. The deeper you look at the handcrafted headboard, the more beauty you find.', '["Nature View", "Queen Bed", "Artisan D�cor"]'),
('111', 'Ereto', 'suite', 2, 3000.00, 'There are rooms you sleep in, and then there is Ereto - a sanctuary engineered for the kind of rest that changes you.', '["Forest View", "King Bed", "Blackout Curtains"]');
