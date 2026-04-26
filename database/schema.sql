-- SIDAI RESORT - Complete Database Schema
-- MySQL 8.0+ | UTF8MB4

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET COLLATION_CONNECTION = utf8mb4_unicode_ci;

-- Guests
CREATE TABLE IF NOT EXISTS guests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  id_number VARCHAR(30) UNIQUE,
  nationality VARCHAR(80),
  country_code VARCHAR(3),
  special_requests TEXT,
  newsletter_opt_in TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  KEY idx_guests_email (email),
  KEY idx_guests_phone (phone),
  KEY idx_guests_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rooms
CREATE TABLE IF NOT EXISTS rooms (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(20) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  type ENUM('standard','deluxe','suite','pool_villa','honeymoon') NOT NULL,
  capacity INT NOT NULL DEFAULT 2,
  price_per_night DECIMAL(10,2) NOT NULL,
  discount_percentage DECIMAL(5,2) NOT NULL DEFAULT 0,
  description TEXT,
  amenities JSON,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  images JSON,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_rooms_type (type),
  KEY idx_rooms_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Halls
CREATE TABLE IF NOT EXISTS halls (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hall_number INT NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  capacity INT NOT NULL,
  capacity_details JSON,
  price_full_day DECIMAL(10,2) NOT NULL,
  price_half_day DECIMAL(10,2),
  price_evening DECIMAL(10,2),
  description TEXT,
  features JSON,
  setup_options JSON,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  images JSON,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_halls_capacity (capacity),
  KEY idx_halls_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Staff
CREATE TABLE IF NOT EXISTS staff (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  phone VARCHAR(20),
  role ENUM('super_admin','manager','receptionist','finance','kitchen','housekeeping') NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  remember_token_expires TIMESTAMP NULL,
  two_factor_enabled TINYINT(1) NOT NULL DEFAULT 0,
  two_factor_secret VARCHAR(100) NULL,
  login_attempts INT NOT NULL DEFAULT 0,
  locked_until TIMESTAMP NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  KEY idx_staff_email (email),
  KEY idx_staff_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings
CREATE TABLE IF NOT EXISTS bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_ref VARCHAR(20) NOT NULL UNIQUE,
  guest_id INT UNSIGNED NOT NULL,
  booking_type ENUM('room','pool','hall','dining','event','spa','music_shoot','conference') NOT NULL,
  room_id INT UNSIGNED NULL,
  hall_id INT UNSIGNED NULL,
  check_in DATE NOT NULL,
  check_out DATE NULL,
  num_nights INT GENERATED ALWAYS AS (
    CASE
      WHEN check_out IS NULL THEN 0
      WHEN check_out <= check_in THEN 0
      ELSE DATEDIFF(check_out, check_in)
    END
  ) STORED,
  event_date DATE NULL,
  event_type VARCHAR(100) NULL,
  event_setup ENUM('theatre','classroom','banquet','cocktail','u_shape') NULL,
  num_guests INT NOT NULL DEFAULT 1,
  subtotal DECIMAL(10,2) NOT NULL,
  discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  total_amount DECIMAL(10,2) NOT NULL,
  deposit_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  balance_due DECIMAL(10,2) GENERATED ALWAYS AS (GREATEST(total_amount - deposit_amount, 0)) STORED,
  status ENUM('inquiry','pending','confirmed','checked_in','checked_out','cancelled') NOT NULL DEFAULT 'pending',
  payment_status ENUM('unpaid','partial','paid','failed','refunded') NOT NULL DEFAULT 'unpaid',
  payment_method ENUM('mpesa','cash','bank','card') NULL,
  notes TEXT,
  cancellation_reason TEXT,
  cancelled_at TIMESTAMP NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  CONSTRAINT fk_bookings_guest FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE RESTRICT,
  CONSTRAINT fk_bookings_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
  CONSTRAINT fk_bookings_hall FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE SET NULL,
  KEY idx_bookings_ref (booking_ref),
  KEY idx_bookings_status (status),
  KEY idx_bookings_payment_status (payment_status),
  KEY idx_bookings_check_in (check_in),
  KEY idx_bookings_guest_id (guest_id),
  KEY idx_bookings_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu Categories
CREATE TABLE IF NOT EXISTS menu_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  KEY idx_menu_categories_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu Items
CREATE TABLE IF NOT EXISTS menu_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(8,2) NOT NULL,
  image VARCHAR(255),
  is_vegetarian TINYINT(1) NOT NULL DEFAULT 0,
  is_spicy TINYINT(1) NOT NULL DEFAULT 0,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  allergen_info VARCHAR(255),
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_menu_items_category FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE,
  KEY idx_menu_items_category (category_id),
  KEY idx_menu_items_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders
CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_ref VARCHAR(20) NOT NULL UNIQUE,
  booking_id INT UNSIGNED NULL,
  guest_id INT UNSIGNED NULL,
  items JSON NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  tax DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL,
  delivery_type ENUM('dine_in','room_service','takeaway') NOT NULL DEFAULT 'dine_in',
  delivery_location VARCHAR(255),
  special_instructions TEXT,
  status ENUM('pending','preparing','ready','delivered','cancelled') NOT NULL DEFAULT 'pending',
  prepared_by INT UNSIGNED NULL,
  delivered_at TIMESTAMP NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  CONSTRAINT fk_orders_guest FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE SET NULL,
  KEY idx_orders_ref (order_ref),
  KEY idx_orders_status (status),
  KEY idx_orders_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments
CREATE TABLE IF NOT EXISTS payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  payment_ref VARCHAR(30) NOT NULL UNIQUE,
  booking_id INT UNSIGNED NULL,
  order_id INT UNSIGNED NULL,
  invoice_number VARCHAR(30),
  amount DECIMAL(10,2) NOT NULL,
  method ENUM('mpesa','cash','bank','card','cheque') NOT NULL,
  status ENUM('pending','completed','failed','refunded','disputed') NOT NULL DEFAULT 'pending',
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
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
  KEY idx_payments_ref (payment_ref),
  KEY idx_payments_checkout (mpesa_checkout_request_id),
  KEY idx_payments_status (status),
  KEY idx_payments_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gallery Items
CREATE TABLE IF NOT EXISTS gallery_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150),
  description TEXT,
  image_path VARCHAR(255) NOT NULL,
  image_thumb VARCHAR(255),
  category ENUM('pool','dining','events','rooms','nature','weddings','parties','exterior','interior','conference') NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  uploaded_by INT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_gallery_category (category),
  KEY idx_gallery_featured (is_featured),
  KEY idx_gallery_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Testimonials
CREATE TABLE IF NOT EXISTS testimonials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  guest_name VARCHAR(100) NOT NULL,
  guest_email VARCHAR(200),
  rating TINYINT(1) NOT NULL,
  message TEXT NOT NULL,
  source ENUM('website','google','tripadvisor','booking','email') NOT NULL DEFAULT 'website',
  is_approved TINYINT(1) NOT NULL DEFAULT 0,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CHECK (rating BETWEEN 1 AND 5),
  KEY idx_testimonials_approved (is_approved),
  KEY idx_testimonials_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit Log
CREATE TABLE IF NOT EXISTS audit_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  staff_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(50) NOT NULL,
  entity_id INT UNSIGNED NULL,
  old_values JSON NULL,
  new_values JSON NULL,
  ip_address VARCHAR(45) NULL,
  user_agent TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_audit_staff FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_audit_entity (entity_type, entity_id),
  KEY idx_audit_staff (staff_id),
  KEY idx_audit_action (action),
  KEY idx_audit_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Messages
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL,
  phone VARCHAR(20),
  subject VARCHAR(200),
  message TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  read_at TIMESTAMP NULL,
  replied_at TIMESTAMP NULL,
  reply_message TEXT,
  assigned_to INT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_contact_assigned_to FOREIGN KEY (assigned_to) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_contact_is_read (is_read),
  KEY idx_contact_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter Subscribers
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(200) NOT NULL UNIQUE,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  verified_at TIMESTAMP NULL,
  unsubscribed_at TIMESTAMP NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_newsletter_email (email),
  KEY idx_newsletter_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site Settings
CREATE TABLE IF NOT EXISTS site_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT,
  setting_type ENUM('string','integer','boolean','json','email','phone','url') NOT NULL DEFAULT 'string',
  setting_group VARCHAR(50) DEFAULT 'general',
  updated_by INT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_site_settings_updated_by FOREIGN KEY (updated_by) REFERENCES staff(id) ON DELETE SET NULL,
  KEY idx_site_settings_group (setting_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Room Availability
CREATE TABLE IF NOT EXISTS room_availability (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_id INT UNSIGNED NOT NULL,
  date_from DATE NOT NULL,
  date_to DATE NOT NULL,
  booking_id INT UNSIGNED NULL,
  status ENUM('available','booked','maintenance','blocked') NOT NULL DEFAULT 'available',
  CONSTRAINT fk_room_availability_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
  CONSTRAINT fk_room_availability_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  KEY idx_room_availability_window (room_id, date_from, date_to),
  KEY idx_room_availability_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hall Availability
CREATE TABLE IF NOT EXISTS hall_availability (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hall_id INT UNSIGNED NOT NULL,
  date_from DATE NOT NULL,
  date_to DATE NOT NULL,
  booking_id INT UNSIGNED NULL,
  time_slot ENUM('full_day','half_day_am','half_day_pm','evening') NOT NULL DEFAULT 'full_day',
  status ENUM('available','booked','maintenance','blocked') NOT NULL DEFAULT 'available',
  CONSTRAINT fk_hall_availability_hall FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE CASCADE,
  CONSTRAINT fk_hall_availability_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  KEY idx_hall_availability_window (hall_id, date_from, date_to),
  KEY idx_hall_availability_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Admin Account
-- Password: Admin@1234 (change immediately in production)
INSERT IGNORE INTO staff (full_name, email, phone, role, password_hash, is_active)
VALUES (
  'Administrator',
  'admin@sidairesort.com',
  '+254720000000',
  'super_admin',
  '$2y$10$V5z/hqSeZf6qFgLbquQf9OfZQZ7zcV4nqGwwswDyGY/NxpufbEcRu',
  1
);

-- Seed Halls
INSERT IGNORE INTO halls (
  hall_number,
  name,
  capacity,
  price_full_day,
  price_half_day,
  price_evening,
  description,
  is_available
) VALUES
(1, 'Enchula', 80, 75000.00, 48000.00, 42000.00, 'Premium meeting hall for conferences and executive gatherings.', 1),
(2, 'Entumo', 220, 130000.00, 80000.00, 70000.00, 'Large event hall for celebrations, summits, and social functions.', 1);

-- Seed Rooms (11 Rooms)
INSERT IGNORE INTO rooms (room_number, name, type, capacity, price_per_night, description, amenities) VALUES
('101', 'Elalai', 'suite', 4, 3200.00, 'Elalai is our finest sanctuary where premium comfort meets nature views.', '["Panoramic Forest View", "King Super Bed", "Private Seating Deck", "Premium Wi-Fi"]'),
('102', 'Emirishoi', 'standard', 2, 2000.00, 'Emirishoi welcomes bright mornings and gentle forest ambience.', '["Forest View", "King Bed", "Wi-Fi"]'),
('103', 'Enchipai', 'standard', 2, 1200.00, 'Enchipai offers warm tones and a calm retreat for restful nights.', '["Garden View", "Queen Bed", "Wi-Fi"]'),
('104', 'Eserian', 'standard', 2, 1500.00, 'Eserian blends simple elegance with everyday comfort.', '["Canopy View", "Double Bed", "Wi-Fi"]'),
('105', 'Empiris', 'deluxe', 3, 2500.00, 'Empiris is grounded, spacious, and ideal for restorative stays.', '["Forest View", "King Bed", "Wi-Fi"]'),
('106', 'Ewangan', 'deluxe', 2, 1800.00, 'Ewangan feels instantly familiar with a cozy interior design.', '["Nature View", "Queen Bed", "Wi-Fi"]'),
('107', 'Enkanasa', 'deluxe', 4, 1900.00, 'Enkanasa is perfect for shared stays with space to relax.', '["Courtyard View", "Double Bed", "Wi-Fi"]'),
('108', 'Esipil', 'standard', 2, 1400.00, 'Esipil is fresh, peaceful, and ideal for short getaways.', '["Garden View", "Twin Beds", "Wi-Fi"]'),
('109', 'Enkipai', 'suite', 2, 2800.00, 'Enkipai is a blessed space with warm light and premium finishes.', '["Forest View", "King Bed", "Balcony Access"]'),
('110', 'Eripoto', 'deluxe', 3, 1700.00, 'Eripoto highlights artisan decor and thoughtful room details.', '["Nature View", "Queen Bed", "Artisan Decor"]'),
('111', 'Ereto', 'suite', 2, 3000.00, 'Ereto is designed for deep rest and uninterrupted comfort.', '["Forest View", "King Bed", "Blackout Curtains"]');

-- Seed Menu Categories
INSERT IGNORE INTO menu_categories (name, description, sort_order) VALUES
('Breakfast', 'Morning delights', 1),
('Local Dishes', 'Kenyan favorites and traditional plates', 2),
('Nyama Choma', 'Fire-grilled specialties', 3),
('Snacks', 'Quick bites and street-style favorites', 4),
('Desserts', 'Sweet finishes', 5),
('Soft Drinks', 'Chilled non-alcoholic drinks', 6),
('Hot Drinks', 'Tea, coffee, and warm beverages', 7),
('Traditional', 'Local traditional drinks and blends', 8),
('Beers', 'Beer and cider selection', 9);

-- Seed Core Site Settings
INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_type, setting_group) VALUES
('pool_hours', '8:00 AM - 6:00 PM daily', 'string', 'services'),
('pool_day_pass_adult', '300', 'integer', 'services'),
('pool_day_pass_child', '150', 'integer', 'services'),
('dining_hours_breakfast', '6:30 AM - 10:30 AM', 'string', 'services'),
('dining_hours_lunch', '12:00 PM - 3:30 PM', 'string', 'services'),
('dining_hours_dinner', '6:30 PM - 10:30 PM', 'string', 'services');

-- End of schema
