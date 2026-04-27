<p align="center">
  <img src="https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8+"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache"/>
  <img src="https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white" alt="XAMPP"/>
  <img src="https://img.shields.io/badge/License-Educational-blue?style=for-the-badge" alt="License"/>
</p>

<h1 align="center">🔍 Lost & Found</h1>

<p align="center">
  <strong>A community-driven web platform for reporting and recovering lost and found items.</strong><br/>
  Built with a custom PHP MVC framework · Served via XAMPP / Apache
</p>

---

## 📑 Table of Contents

- [✨ Overview](#-overview)
- [🚀 Features](#-features)
- [🛠️ Tech Stack](#️-tech-stack)
- [📂 Project Structure](#-project-structure)
- [⚡ Getting Started](#-getting-started)
- [📖 Usage](#-usage)
- [🔐 Admin Panel](#-admin-panel)
- [💬 Messaging System](#-messaging-system)
- [🛡️ Security](#️-security)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

---

## 📸 Screenshots

<details>
<summary>Click to expand screenshots</summary>
<br/>

<img width="100%" alt="Home Page" src="https://github.com/user-attachments/assets/aa24be5e-9859-4713-8043-f6391070324e" />

<br/><br/>

<img width="100%" alt="Browse Reports" src="https://github.com/user-attachments/assets/6eb129ac-0dba-439d-adac-bec07ba224ab" />

<br/><br/>

<img width="100%" alt="Item Detail View" src="https://github.com/user-attachments/assets/597f9f28-d38a-4d75-9e52-a9d3959eaa24" />

</details>

---

## ✨ Overview

**Lost & Found** is a full-stack PHP web application that connects people who have lost items with those who have found them. Users can:

- 📝 Post reports for lost or found items
- 🔎 Search by category and browse listings
- 🗺️ Explore an interactive map
- 💬 Communicate directly via a real-time chat interface

All within a clean, modern UI built with **Cormorant Garamond** and **DM Sans** typography.

---

## 🚀 Features

### 👤 User Features

| Feature | Description |
|---------|-------------|
| **Account Management** | Register, log in, update profile (name, phone, avatar), and manage sessions |
| **Lost & Found Reports** | Post, browse, and search lost or found item listings with image uploads |
| **Item Detail View** | View full report details with an integrated comment/chat section |
| **Real-time Chat** | Per-report threaded messaging with image attachments, typing indicators, and online presence |
| **Inbox** | View all conversations linked to reports you've participated in |
| **Dashboard** | Personal dashboard displaying all your submitted reports |
| **Success Stories** | Gallery of resolved/reunited items with reward information |
| **Password Recovery** | Self-service forgot / reset password flow |
| **Interactive Map** | Browse reports by geographic location |
| **Contact & FAQ** | Static informational pages |

### 🛠️ Admin Features

| Feature | Description |
|---------|-------------|
| **Admin Dashboard** | Overview metrics (total users, active reports, resolved items) and recent activity table |
| **User Management** | View all users, assign trust badges, and ban/unban accounts |
| **Report Management** | Review, moderate, and manage all submitted reports |
| **Announcements** | Broadcast global messages (Info / Warning / Success / Danger) with publish/hide toggles |
| **System Settings** | Platform configuration panel |
| **Export CSV** | Download report data |

---

## 🛠️ Tech Stack

| Layer | Technology |
|:------|:-----------|
| **Language** | PHP 8+ (custom MVC, no external framework) |
| **Database** | MySQL (via PDO with `utf8mb4`) |
| **Server** | Apache (XAMPP) with `.htaccess` URL rewriting |
| **Frontend** | Vanilla HTML / CSS / JavaScript |
| **Fonts** | Google Fonts — Cormorant Garamond, DM Sans |
| **Icons** | Font Awesome |
| **Session** | PHP native sessions (`laf_session`, 2 h TTL) |
| **Mail** | SMTP (Mailtrap-compatible configuration) |
| **File Uploads** | Server-side PHP, max 2 MB |

---

## 📂 Project Structure

```
Lost-and-Found/
│
├── 📁 app/
│   ├── 📁 Controllers/
│   │   ├── AuthController.php          # Login, register, logout, password reset
│   │   ├── UserController.php          # Dashboard, profile, avatar upload
│   │   ├── MessageController.php       # Inbox, chat, AJAX API endpoints
│   │   ├── HomeController.php          # Landing, search, success stories
│   │   ├── ItemController.php          # CRUD for lost/found reports
│   │   ├── AdminController.php         # Admin panel actions
│   │   └── MapController.php           # Map data endpoint
│   │
│   ├── 📁 Models/
│   │   ├── User.php                    # User CRUD, auth, badge & ban management
│   │   ├── Item.php                    # Report CRUD and queries
│   │   ├── Message.php                 # Comments, conversations, typing, presence
│   │   └── Announcement.php            # Admin announcement model
│   │
│   ├── 📁 Core/
│   │   ├── App.php                     # Router / front controller
│   │   ├── Controller.php              # Base controller (view/model helpers)
│   │   └── Database.php                # PDO singleton
│   │
│   └── 📁 Services/
│
├── 📁 config/
│   ├── config.php                      # App constants, DB credentials, autoloader
│   └── sql.db                          # (legacy / dev SQLite snapshot)
│
├── 📁 includes/
│   └── helpers.php                     # Global helper functions (redirect, escape, …)
│
├── 📁 public/                           # Apache document root
│   ├── index.php                       # Application entry point
│   ├── .htaccess                       # URL rewriting rules
│   └── 📁 assets/
│       ├── 📁 css/                     # Global + per-page stylesheets
│       └── 📁 js/                      # Client-side scripts
│
├── 📁 resources/views/
│   ├── 📁 layouts/                     # header.php, footer.php
│   ├── 📁 auth/                        # login, register, forgot, reset
│   ├── 📁 items/                       # index (browse), show, create
│   ├── 📁 messages/                    # inbox index, per-report chat
│   ├── 📁 admin/                       # dashboard, users, reports, announcements, settings
│   ├── 📁 pages/                       # about, contact, faq
│   ├── dashboard.php                   # User personal dashboard
│   ├── map.php
│   ├── search.php
│   └── success_stories.php
│
├── 📁 security/
├── 📁 storage/
├── 📁 Theme/
└── 📁 legal/
```

---

## ⚡ Getting Started

### Prerequisites

- **XAMPP** (or any Apache + MySQL + PHP 8+ stack)
- PHP `pdo_mysql` extension enabled
- Apache `mod_rewrite` enabled

### 1️⃣ Installation

Clone the repository into your XAMPP `htdocs` directory:

```bash
git clone https://github.com/your-username/Lost-and-Found.git "C:/xampp/htdocs/Lost & Found/Lost-and-Found"
```

Start **Apache** and **MySQL** via the XAMPP Control Panel.

Enable `mod_rewrite` in `httpd.conf` (if not already):

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Ensure your VirtualHost / Directory block contains:

```apache
AllowOverride All
```

### 2️⃣ Database Setup

1. Open **phpMyAdmin** at [`http://localhost/phpmyadmin`](http://localhost/phpmyadmin).
2. Create a new database named **`lost_and_found`** with collation `utf8mb4_unicode_ci`.
3. Import the SQL schema (if provided) or run the CREATE TABLE statements for:

| Table | Key Columns |
|:------|:------------|
| `users` | `user_id`, `username`, `full_name`, `email`, `phone`, `password_hash`, `profile_image`, `badge_status`, `is_banned`, `last_activity`, `date_joined` |
| `reports` | `report_id`, `user_id`, `category_id`, `title`, `description`, `type` (`lost`/`found`), `image_path`, `reward_amount`, `status`, `date_posted` |
| `categories` | `category_id`, `name` |
| `comments` | `comment_id`, `report_id`, `user_id`, `comment_text`, `attachment_path`, `parent_id`, `created_at` |
| `chat_status` | `report_id`, `user_id`, `is_typing`, `last_typed` |
| `announcements` | `announcement_id`, `title`, `content`, `type`, `is_active`, `date_posted` |

### 3️⃣ Configuration

Open `config/config.php` and update the following constants:

```php
// Base paths
define('ROOT',     'C:/xampp/htdocs/Lost & Found/Lost-and-Found');
define('BASE_URL', 'http://localhost/Lost%20%26%20Found/Lost-and-Found');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'lost_and_found');
define('DB_USER', 'root');
define('DB_PASS', '');          // Set your MySQL password here

// Mail (optional — uses Mailtrap by default)
define('MAIL_HOST', 'smtp.mailtrap.io');
define('MAIL_PORT', 2525);
define('MAIL_USER', '');        // Your Mailtrap username
define('MAIL_PASS', '');        // Your Mailtrap password
```

> [!NOTE]
> Set `APP_ENV` to `'production'` and `APP_DEBUG` to `false` before deploying to a live server.

---

## 📖 Usage

### User Roles

| Role | Access | Credentials |
|:-----|:-------|:------------|
| **Admin** | Full admin panel | `admin@gmail.com` / `1234567890` *(change before production)* |
| **User** | Public + dashboard | Self-registered via `/auth/register` |

### Route Map

<details>
<summary>📍 View all routes</summary>

| URL | Description |
|:----|:------------|
| `/` | Home — browse all reports |
| `/items/lost` | Filter: lost items only |
| `/items/found` | Filter: found items only |
| `/items/create` | Post a new report *(login required)* |
| `/item/show/{id}` | View a specific report + chat |
| `/map` | Interactive map view |
| `/message/index` | Message inbox *(login required)* |
| `/message/chat/{report_id}` | Chat for a specific report |
| `/user/dashboard` | Personal dashboard |
| `/user/profile` | Edit profile & avatar |
| `/auth/login` | Login page |
| `/auth/register` | Registration page |
| `/auth/forgot` | Forgot password |
| `/contact` | Contact page |
| `/pages/faq` | FAQ |
| `/pages/about` | About page |

</details>

---

## 🔐 Admin Panel

Access the admin panel at **`/admin/dashboard`** after logging in with admin credentials.

| Section | Route | Description |
|:--------|:------|:------------|
| **Dashboard** | `/admin/dashboard` | Key metrics, recent reports table, CSV export |
| **Manage Users** | `/admin/users` | View all users, set trust badge, ban / unban accounts |
| **Manage Reports** | `/admin/reports` | Browse and moderate all submitted reports |
| **Announcements** | `/admin/announcements` | Create / publish / hide / delete global announcements |
| **System Settings** | `/admin/settings` | Platform-level configuration |

**Announcement types:** `info` · `warning` · `success` · `danger`

---

## 💬 Messaging System

The chat system is built on top of the existing reports table — every report acts as a conversation thread.

| Feature | How it works |
|:--------|:-------------|
| **Real-time polling** | Client fetches new messages via `GET /message/apiGetMessages/{report_id}` every few seconds |
| **Typing indicators** | Sent via `POST /message/apiSetTyping` and cleared after 5 s of inactivity |
| **Online status** | A user is considered online if `last_activity` is within the last 15 seconds |
| **File attachments** | Images (JPG, JPEG, PNG, GIF) uploaded to `public/uploads/chat/` with unique filenames |
| **Threaded replies** | Comments support a `parent_id` for reply-to functionality |

---

## 🛡️ Security

| Measure | Details |
|:--------|:--------|
| **Password hashing** | `password_hash()` / `password_verify()` (bcrypt) |
| **Input sanitization** | `htmlspecialchars()`, `filter_input()`, prepared PDO statements — no raw SQL concatenation |
| **Sessions** | Custom name (`laf_session`), 2-hour lifetime |
| **Account banning** | Checked on login before session creation |
| **Debug mode** | `APP_DEBUG` should be `false` in production |

> [!WARNING]
> **Before going live:** Change the hardcoded admin credentials in `AuthController.php`, set a strong DB password, and configure HTTPS.

---

## 🤝 Contributing

1. **Fork** this repository
2. **Create** a feature branch: `git checkout -b feature/your-feature-name`
3. **Commit** your changes: `git commit -m 'feat: add your feature'`
4. **Push** to the branch: `git push origin feature/your-feature-name`
5. **Open** a Pull Request

> Please follow the existing code style and namespace convention (`App\Controllers`, `App\Models`, `App\Core`).

---

## 📄 License

This project is for **educational and personal use**. See the [`legal/`](legal/) directory for any additional licensing information.

---

<p align="center">
  Made with ❤️ for the community
</p>
