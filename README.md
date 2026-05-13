# Lost and Found - Community Recovery Platform

A full-stack PHP web application designed to connect community members who have lost or found items. Built on a custom MVC framework with secure database operations and responsive design.

---

## Overview

Lost and Found is a comprehensive platform where users can report, search, and communicate about lost or found items. The system features location-based discovery, real-time messaging, and admin management capabilities all built with security-first practices.

### Core Capabilities

- Report lost or found items with detailed information and photos
- Advanced search and filtering by category, location, and date range
- Real-time messaging system between users
- Location tracking with interactive maps
- User authentication and profile management
- Admin dashboard for system oversight and moderation

---

## Core Features

### User Capabilities

| Feature | Description |
|---------|-----------|
| Account Management | Register, login, profile editing, and secure session management |
| Item Reporting | Create lost/found reports with photos and detailed descriptions |
| Search and Filtering | Advanced search by category, location, date range, and keywords |
| Real-time Messaging | Direct user-to-user communication linked to specific reports |
| Inbox System | View all active conversations and message threads in one place |
| Personal Dashboard | Manage your submitted reports and track responses |
| Interactive Maps | Browse and discover items by geographic location |
| Success Tracking | View resolved cases and reward information |
| Password Recovery | Self-service password reset with email verification |

### Admin Capabilities

| Feature | Description |
|---------|-----------|
| Dashboard Analytics | System metrics, user statistics, and activity overview |
| User Management | Manage accounts, assign trust badges, and moderate bans |
| Report Moderation | Review, approve, or remove item reports |
| Announcements | Create and publish system-wide notifications |
| System Settings | Configure platform-level parameters and policies |
| Data Export | Export reports and user data to CSV format |

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8+ with custom MVC architecture |
| Database | MySQL 5.7+ (UTF-8 multibyte character support) |
| Server | Apache with XAMPP stack |
| Frontend | HTML5, CSS3, and vanilla JavaScript |
| Maps | Leaflet.js for interactive geographic mapping |
| Typography | Google Fonts (Cormorant Garamond, DM Sans) |
| Icons | Font Awesome library |
| Security | PDO prepared statements, bcrypt password hashing |
| Sessions | PHP native sessions with custom configuration |

---

## Project Structure

```
Lost-and-Found/
├── app/
│   ├── Core/                    Application framework foundation
│   │   ├── App.php              Router and request dispatcher
│   │   ├── Controller.php       Base controller for all routes
│   │   └── Database.php         PDO database connection manager
│   │
│   ├── Controllers/             Request handlers for business logic
│   │   ├── AuthController.php   User authentication and registration
│   │   ├── ItemController.php   Item CRUD and search operations
│   │   ├── UserController.php   User profile and dashboard
│   │   ├── MessageController.php Messaging and chat system
│   │   ├── AdminController.php  Admin panel operations
│   │   ├── HomeController.php   Public pages and navigation
│   │   ├── MapController.php    Geographic mapping features
│   │   └── PageController.php   Static content pages
│   │
│   ├── Models/                  Data entities and queries
│   │   ├── User.php             User accounts and authentication
│   │   ├── Item.php             Item reports and listings
│   │   ├── Message.php          Chat and messaging data
│   │   ├── Announcement.php     System announcements
│   │   ├── ContactRequest.php   Contact form submissions
│   │   └── SystemConfig.php     Platform configuration
│   │
│   └── Services/                Specialized business services
│       └── NotificationService.php  Email and alert handling
│
├── config/
│   ├── config.php               Central environment configuration
│   ├── setup.sql                Database schema setup script
│   └── lost&found.sql           Sample data and migrations
│
├── includes/
│   └── helpers.php              Global utility and helper functions
│
├── public/                       Web-accessible directory
│   ├── index.php                Application entry point
│   ├── .htaccess                Apache URL rewriting rules
│   │
│   └── assets/
│       ├── css/                 Stylesheets (global and page-specific)
│       │   ├── style.css        Main stylesheet
│       │   ├── dashboard.css    Dashboard styling
│       │   ├── admin-main.css   Admin interface styles
│       │   └── auth/            Authentication page styles
│       │
│       ├── js/                  Client-side JavaScript
│       │   ├── auth.js          Authentication form handling
│       │   └── chat.js          Real-time messaging scripts
│       │
│       └── uploads/             User-uploaded files
│           └── chat/            Chat attachment storage
│
├── resources/
│   └── views/                   View templates (MVC presentation layer)
│       ├── layouts/
│       │   └── main.php         Master page layout
│       │
│       ├── auth/                Authentication templates
│       │   ├── login.php
│       │   ├── register.php
│       │   ├── forgot.php
│       │   └── reset.php
│       │
│       ├── items/               Item listing and detail templates
│       │   ├── list.php
│       │   ├── detail.php
│       │   └── form.php
│       │
│       ├── messages/            Messaging interface templates
│       │   ├── index.php
│       │   └── chat.php
│       │
│       ├── admin/               Admin panel templates
│       │   ├── dashboard.php
│       │   ├── users.php
│       │   ├── reports.php
│       │   ├── announcements.php
│       │   └── settings.php
│       │
│       └── pages/               Static content pages
│           ├── home.php
│           ├── about.php
│           ├── faq.php
│           └── contact.php
│
├── storage/                     File storage directory
├── security/                    Security documentation
├── SETUP_REPORT.md              Architecture and file interconnections
├── ARCHITECTURE_REFERENCE.md    Detailed system design
└── README.md                    This file
```

---

## Getting Started

### Requirements

- XAMPP (Apache, MySQL, PHP 8+) or equivalent stack
- PHP `pdo_mysql` extension enabled
- Apache `mod_rewrite` enabled for URL routing
- MySQL 5.7+ database server
- Modern web browser for frontend

### Installation

**Step 1: Clone the Repository**

```bash
git clone https://github.com/anuththara2007-W/Lost-and-Found.git
cd Lost-and-Found
```

**Step 2: Start Services**

1. Open XAMPP Control Panel
2. Start Apache and MySQL services
3. Verify both services show green indicators

**Step 3: Enable Apache Rewriting**

Edit `httpd.conf` and ensure this module is loaded:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

For your project directory or VirtualHost, ensure:

```apache
AllowOverride All
```

**Step 4: Configure the Database**

1. Visit `http://localhost/phpmyadmin`
2. Create a new database named `lost_and_found`
3. Set collation to `utf8mb4_unicode_ci`
4. Import schema from `config/setup.sql`

**Step 5: Update Environment Configuration**

Edit `config/config.php` and verify:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'lost_and_found');
define('DB_USER', 'root');
define('DB_PASS', '');              // Your MySQL password
define('APP_ENV', 'development');   // Use 'production' for live servers
define('APP_DEBUG', true);          // Set to false in production
```

**Step 6: Set File Permissions**

Ensure these directories are writable:

```bash
chmod -R 755 public/uploads/
chmod -R 755 storage/
chmod -R 755 resources/views/
```

**Step 7: Access the Application**

Open your browser and navigate to:

```
http://localhost/Lost%20%26%20Found/Lost-and-Found/
```

---

## Authentication Credentials

### Test Accounts

| Role | Email | Password | Purpose |
|------|-------|----------|---------|
| Admin | admin@gmail.com | 1234567890 | Administrative access |
| Demo User | demo@example.com | demo123456 | Regular user testing |

> Important: Change admin credentials before deploying to production. See Security section below.

---

## Application Routes

### Public Routes

| URL | Method | Description |
|-----|--------|-----------|
| `/` | GET | Home page with item feed |
| `/items/lost` | GET | Filter for lost items |
| `/items/found` | GET | Filter for found items |
| `/item/show/{id}` | GET | View item details with comments |
| `/map` | GET | Interactive geographic map |
| `/pages/about` | GET | About page |
| `/pages/faq` | GET | Frequently asked questions |
| `/contact` | GET/POST | Contact form |

### Authentication Routes (No Login Required)

| URL | Method | Description |
|-----|--------|-----------|
| `/auth/login` | GET/POST | User login page |
| `/auth/register` | GET/POST | Create new account |
| `/auth/forgot` | GET/POST | Password recovery request |
| `/auth/reset/{token}` | GET/POST | Password reset form |

### User Routes (Login Required)

| URL | Method | Description |
|-----|--------|-----------|
| `/items/create` | GET/POST | Create new item report |
| `/item/edit/{id}` | GET/POST | Edit your report |
| `/item/delete/{id}` | POST | Delete your report |
| `/user/dashboard` | GET | Personal dashboard |
| `/user/profile` | GET/POST | Edit profile information |
| `/user/avatar` | POST | Upload profile photo |
| `/message/index` | GET | Message inbox |
| `/message/chat/{report_id}` | GET | Open chat for report |
| `/message/send` | POST | Send chat message |

### Admin Routes (Admin Login Required)

| URL | Method | Description |
|-----|--------|-----------|
| `/admin/dashboard` | GET | Admin overview and metrics |
| `/admin/users` | GET/POST | Manage user accounts |
| `/admin/users/{id}/ban` | POST | Ban a user account |
| `/admin/users/{id}/trust` | POST | Award trust badge |
| `/admin/reports` | GET | View all item reports |
| `/admin/reports/{id}/delete` | POST | Remove inappropriate report |
| `/admin/announcements` | GET/POST | Create system announcements |
| `/admin/announcements/{id}/toggle` | POST | Activate/deactivate announcement |
| `/admin/settings` | GET/POST | System configuration |
| `/admin/export` | POST | Export data to CSV |

---

## Security Implementation

### Password Security

All user passwords are hashed using PHP's built-in `password_hash()` function with bcrypt algorithm:

```php
// Registration
$password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Login verification
if (password_verify($login_password, $stored_hash)) {
    // Create session
}
```

### Database Security

All database queries use PDO prepared statements to prevent SQL injection:

```php
// Secure query example
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_banned = 0");
$stmt->execute([$email]);
$user = $stmt->fetch();
```

### Input Validation

User inputs are validated and sanitized before processing:

```php
// Email validation
$email = filter_var($input, FILTER_VALIDATE_EMAIL);

// HTML encoding for output
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

### Session Management

Sessions use secure configurations:

```php
// Custom session name
define('SESSION_NAME', 'laf_session');

// Session timeout (2 hours)
define('SESSION_TIMEOUT', 7200);

// Session cookie security flags
ini_set('session.cookie_httponly', 1);  // JavaScript cannot access
ini_set('session.cookie_secure', 1);    // HTTPS only (production)
```

### Account Protection

- Admin accounts are verified before granting access
- Failed login attempts can be rate-limited
- Users can be banned to prevent abuse
- All actions log user activity for audit trail

---

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    badge_status VARCHAR(20),        -- 'verified', 'trusted', 'standard'
    is_banned BOOLEAN DEFAULT FALSE,
    last_activity TIMESTAMP,
    date_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_email (email),
    KEY idx_banned (is_banned)
);
```

### Reports Table

```sql
CREATE TABLE reports (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    type ENUM('lost', 'found') NOT NULL,
    image_path VARCHAR(255),
    location VARCHAR(255),
    reward_amount DECIMAL(10, 2),
    status ENUM('open', 'resolved', 'closed') DEFAULT 'open',
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    KEY idx_type (type),
    KEY idx_status (status),
    KEY idx_date (date_posted)
);
```

### Messages/Comments Table

```sql
CREATE TABLE comments (
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    user_id INT NOT NULL,
    comment_text TEXT,
    attachment_path VARCHAR(255),
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    KEY idx_report (report_id),
    KEY idx_user (user_id)
);
```

---

## Troubleshooting

### Common Issues

**Issue: "Class not found" error**
- Ensure autoload is correctly configured in public/index.php
- Check that namespace matches file path structure
- Verify PSR-4 compliance in class definitions

**Issue: Database connection failed**
- Verify MySQL is running in XAMPP
- Check database credentials in config.php
- Ensure database user has permissions for the database
- Test connection with phpMyAdmin

**Issue: Uploads not working**
- Verify public/uploads/ directory exists
- Check directory permissions (755 or 777)
- Ensure file size limits in php.ini are sufficient
- Verify file type restrictions in ItemController

**Issue: Sessions not persisting**
- Check session save path permissions
- Verify SESSION_NAME constant is valid
- Ensure cookies are enabled in browser
- Check for session_start() calls in config.php

**Issue: 404 errors on all routes**
- Verify mod_rewrite is enabled in Apache
- Check .htaccess file in public/ directory
- Ensure AllowOverride All in Apache configuration
- Test that rewrite rules are being applied

---

## Code Organization Principles

### Namespace Convention

All application code follows PSR-4 autoloading under the `App` namespace:

```
App\Core\        - Framework foundation classes
App\Controllers\ - Request handlers
App\Models\      - Data entities and queries
App\Services\    - Business logic services
```

### Naming Conventions

- **Classes**: PascalCase (UserController, ItemModel)
- **Methods**: camelCase (getUserById, createNewItem)
- **Constants**: UPPER_CASE (DB_HOST, SESSION_TIMEOUT)
- **Properties**: camelCase ($userId, $itemTitle)
- **Files**: Match class names exactly (UserController.php)

### File Organization

- One class per file
- Logical grouping in directories
- Related tests in parallel structure
- Asset organization mirrors functionality

---

## Contributing Guidelines

We welcome contributions to the Lost and Found platform. To contribute:

1. Fork the repository
2. Create a feature branch from main
3. Make your changes with clear commit messages
4. Add tests for new functionality
5. Ensure all existing tests pass
6. Submit a pull request with a clear description

### Code Standards

- Follow PSR-12 PHP coding standards
- Use meaningful variable and function names
- Add inline comments for complex logic
- Test edge cases and error conditions
- Maintain existing code style

---

## Support and Documentation

For detailed information about the application architecture and file interconnections, see:

- **SETUP_REPORT.md** - Critical files per layer and their connections
- **ARCHITECTURE_REFERENCE.md** - Comprehensive system design guide
- **security/** directory - Security policies and best practices

---

## License

This project is for educational and personal use. See the `legal/` directory for additional licensing information.

---

## Contact and Support

For questions, issues, or suggestions:

1. Check the FAQ at `/pages/faq`
2. Use the contact form at `/contact`
3. Review security documentation in `security/` directory
4. Open an issue on GitHub repository

---

**Last Updated**: May 2026  
**Version**: 1.0  
**Maintained by**: Lost and Found Development Team
