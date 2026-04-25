-- Lost & Found Database Setup Script
-- To deploy: Copy this entire block and paste it into the "SQL" tab in phpMyAdmin.
-- This will create the database, tables, and insert default test data so you can log in immediately.

CREATE DATABASE IF NOT EXISTS lost_and_found
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE lost_and_found;

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
  badge_status ENUM('none', 'bronze', 'silver', 'gold') DEFAULT 'none',
  language VARCHAR(10) DEFAULT 'en',
  date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_activity DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS categories (
  category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS reports (
  report_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED,
  type ENUM('lost', 'found') NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(255),

  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),

  contact_info VARCHAR(255),
  reward_amount DECIMAL(10,2) DEFAULT 0.00,
  status ENUM('open', 'resolved', 'cancelled') DEFAULT 'open',
  image_path VARCHAR(255),
  date_posted DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_reports_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_reports_category
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
    ON DELETE SET NULL ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS comments (
  comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  report_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  comment_text TEXT NOT NULL,
  attachment_path VARCHAR(255) DEFAULT NULL,
  parent_id INT UNSIGNED DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_comments_report
    FOREIGN KEY (report_id) REFERENCES reports(report_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_comments_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS admins (
  admin_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS announcements (
  announcement_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  content TEXT NOT NULL,
  image_path VARCHAR(255),
  date_posted DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expiry_date DATETIME,
  is_active TINYINT(1) DEFAULT 1,
  CONSTRAINT fk_announcements_admin
    FOREIGN KEY (admin_id) REFERENCES admins(admin_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS chat_status (
  report_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  is_typing TINYINT(1) DEFAULT 0,
  last_typed DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (report_id, user_id),
  CONSTRAINT fk_chat_status_report FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE,
  CONSTRAINT fk_chat_status_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Base Categories
INSERT IGNORE INTO categories (name, description) VALUES
('Electronics', 'Phones, laptops, headphones, smartwatches'),
('Wallets & Bags', 'Purses, backpacks, wallets, briefcases'),
('Documents', 'IDs, Passports, Certificates'),
('Keys', 'Car keys, house keys, key fobs'),
('Clothing', 'Jackets, hats, scarves'),
('Jewelry', 'Rings, necklaces, bracelets'),
('Pets', 'Dogs, cats, birds'),
('Other', 'Miscellaneous items');

-- Insert Dummy Users 
-- Password is 'password123' hashed with BCRYPT
INSERT IGNORE INTO users (username, full_name, email, password_hash) VALUES 
('test_user', 'John Doe', 'john@example.com', '$2y$10$wT3S/.wT7r5iU4a7LgX.a.37KxKx/Hl3bI/c9V8IeC7C.q6Q9z4lO'),
('good_samaritan', 'Jane Smith', 'jane@test.com', '$2y$10$wT3S/.wT7r5iU4a7LgX.a.37KxKx/Hl3bI/c9V8IeC7C.q6Q9z4lO');

-- Insert Dummy Admin
-- Password is 'admin123' hashed with BCRYPT
INSERT IGNORE INTO admins (username, full_name, password_hash) VALUES 
('admin_user', 'System Admin', '$2y$10$vO.VvKj9g6d0G.f1tP226Of1uJwZ1Jv7qV8H2M6rZ3Z9Zt9rKqM9S');

-- Insert Dummy Reports
INSERT IGNORE INTO reports (user_id, category_id, type, title, description, location) VALUES 
(1, 1, 'lost', 'Lost my iPhone 14 Pro Max', 'Black iPhone in a clear case. Lost it near the food court on Level 2.', 'Food Court, Level 2'),
(2, 4, 'found', 'Found a set of Honda Car Keys', 'Found a pair of car keys with a red lanyard near the library entrance.', 'Library Main Entrance');
