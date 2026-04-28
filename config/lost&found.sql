-- =========================
-- CREATE DATABASE
-- =========================
CREATE DATABASE IF NOT EXISTS lost_and_found  --Create the database if it does not already exist
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE lost_and_found;

-- =========================
-- USERS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS users (
  user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(20),
  password_hash VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  profile_image VARCHAR(255),
  trust_level TINYINT UNSIGNED DEFAULT 0,
  badge_status ENUM('none','bronze','silver','gold') DEFAULT 'none',
  language VARCHAR(10) DEFAULT 'en',
  date_joined DATETIME DEFAULT CURRENT_TIMESTAMP,
  last_activity DATETIME NULL
);

-- =========================
-- CATEGORIES TABLE
-- =========================
CREATE TABLE IF NOT EXISTS categories (
  category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT
);

-- =========================
-- REPORTS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS reports (
  report_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED,

  type ENUM('lost','found') NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(255),

  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),

  contact_info VARCHAR(255),
  whatsapp_contact VARCHAR(20),

  reward_amount DECIMAL(10,2) DEFAULT 0.00,
  status ENUM('open','resolved','cancelled') DEFAULT 'open',

  image_path VARCHAR(255),

  custom_category VARCHAR(255),
  allow_platform_message TINYINT(1) DEFAULT 1,

  date_posted DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

-- =========================
-- COMMENTS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS comments (
  comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  report_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  comment_text TEXT NOT NULL,
  attachment_path VARCHAR(255),
  parent_id INT UNSIGNED DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =========================
-- ADMINS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS admins (
  admin_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(100)
);

-- =========================
-- ANNOUNCEMENTS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS announcements (
  announcement_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  content TEXT NOT NULL,
  image_path VARCHAR(255),
  date_posted DATETIME DEFAULT CURRENT_TIMESTAMP,
  expiry_date DATETIME,
  is_active TINYINT(1) DEFAULT 1,

  FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE CASCADE
);

-- =========================
-- CHAT STATUS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS chat_status (
  report_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  is_typing TINYINT(1) DEFAULT 0,
  last_typed DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (report_id, user_id),

  FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =========================
-- INDEXES
-- =========================
-- indexes  creates a search shortcut for the database.
CREATE INDEX idx_reports_user_id ON reports(user_id);
CREATE INDEX idx_reports_category_id ON reports(category_id);
CREATE INDEX idx_reports_type_status ON reports(type, status);
CREATE INDEX idx_comments_report_id ON comments(report_id);

-- =========================
-- SAMPLE DATA
-- =========================


-- INSERT IGNORE is used to Insert data, but if it already exists, don’t crash — just skip it.
INSERT IGNORE INTO categories (name, description) VALUES
('Electronics', 'Phones, laptops, headphones'),
('Wallets & Bags', 'Purses, backpacks'),
('Documents', 'IDs, passports'),
('Keys', 'Car keys, house keys'),
('Pets', 'Dogs, cats'),
('Other', 'Misc items');

INSERT IGNORE INTO users (username, full_name, email, password_hash) VALUES
('test_user', 'John Doe', 'john@example.com', 'password123'),
('good_samaritan', 'Jane Smith', 'jane@test.com', 'password123');

INSERT IGNORE INTO admins (username, full_name, password_hash) VALUES
('admin_user', 'System Admin', 'admin123');

INSERT IGNORE INTO reports (user_id, category_id, type, title, description, location) VALUES
(1, 1, 'lost', 'Lost iPhone', 'Lost near food court', 'Colombo'),
(2, 4, 'found', 'Found Keys', 'Found near entrance', 'Galle');