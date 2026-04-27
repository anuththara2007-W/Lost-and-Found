# Lost & Found - Final Viva Report (Full File Linking + Website Flow)

This report explains **how every part of the website connects**, in simple English, with correct technical flow.

It is written for viva use: you can explain the project as a story from request -> controller -> model -> database -> view -> CSS/JS.

---

## 1. Project Story: How one page request works

1. User opens a URL like `/item/show/12`.
2. Apache rewrite rule in `public/.htaccess` sends that request to `public/index.php?url=item/show/12`.
3. `public/index.php` loads:
   - `config/config.php` (project constants, DB config, session)
   - `includes/helpers.php` (global functions like `redirect`, `escape`, `requireLogin`)
4. `public/index.php` creates `App\Core\App`.
5. `app/Core/App.php` reads URL parts:
   - controller: `ItemController`
   - method: `show`
   - parameter: `12`
6. `ItemController::show(12)` gets data through model methods (`Item`, `Message`) and DB queries.
7. Controller calls `$this->view('items/show', $data)`.
8. View `resources/views/items/show.php` is rendered.
9. View includes layout:
   - `resources/views/layouts/header.php` (global nav + base style)
   - page-specific CSS (like `item-detail.css`)
   - `resources/views/layouts/footer.php` (footer + optional JS includes)
10. User sees final styled page.

So the simple flow is:

**Browser -> .htaccess -> index.php -> Core App Router -> Controller -> Model -> Database -> View -> CSS/JS -> Browser**

---

## 2. Core Architecture Files (System Backbone)

### Entry + Config

- `public/.htaccess`  
  Rewrites friendly URLs to `index.php`.
- `public/index.php`  
  Front controller. Loads config/helpers and starts app router.
- `config/config.php`  
  Defines `ROOT`, `BASE_URL`, DB constants, debug mode, timezone, session settings.
- `includes/helpers.php`  
  Common helpers:
  - `redirect()`
  - `escape()`
  - `requireLogin()`
  - flash message system
  - language helper `t()`
  - hook helper wrappers

### Core MVC Classes

- `app/Core/App.php`  
  URL parser and request dispatcher.
- `app/Core/Controller.php`  
  Base `model()` and `view()` loader.
- `app/Core/Database.php`  
  PDO singleton DB connection used by models.
- `app/Core/HookManager.php`  
  Action/filter mechanism.

---

## 3. Controllers, Models, and Their Direct Connections

## Controllers (`app/Controllers`)

- `AuthController.php`
  - Uses model: `User`
  - Handles: login, register, forgot, reset, logout
  - Views: `auth/login`, `auth/register`, `auth/forgot`, `auth/reset`
  - Note: hardcoded admin credential route exists here.

- `HomeController.php`
  - Uses model: `Item`
  - Handles: homepage + success stories
  - Views: `home`, `success_stories`

- `ItemController.php`
  - Uses models: `Item`, `Message`
  - Handles: item search/index, show detail, create item, resolve item
  - Views: `items/index`, `items/show`, `items/create`

- `MessageController.php`
  - Uses models: `Message`, `Item`
  - Handles: inbox page, chat page, chat APIs
  - Views: `messages/index`, `messages/chat`
  - APIs:
    - `/message/apiGetMessages/{report_id}`
    - `/message/apiSetTyping`
    - `/message/apiSendMessage`

- `UserController.php`
  - Uses models: `User`, `Item`
  - Handles: user dashboard, profile update, avatar upload
  - Views: `dashboard`, `user/profile`

- `MapController.php`
  - Uses model: `Item` (plus direct DB query)
  - Handles: map page + marker API
  - View: `map`
  - API: `/map/api_markers`

- `PageController.php`
  - Uses model: `ContactRequest` for contact form
  - Handles: static pages + language switch
  - Views: `pages/about`, `pages/faq`, `pages/contact`
  - Also tries to load: `pages/terms`, `pages/privacy` (missing view files, explained later)

- `AdminController.php`
  - Uses models: `User`, `Item`, `SystemConfig`, `ContactRequest`, `Announcement`
  - Handles admin dashboard, users, reports, items, settings, monitor, backup, contact requests, announcements, export CSV
  - Views: `admin/dashboard`, `admin/users`, `admin/reports`, `admin/items`, `admin/edit_report`, `admin/settings` (name mismatch), `admin/monitor`, `admin/contact_requests`, `admin/backup`, `admin/announcements`

## Models (`app/Models`)

- `User.php` -> user lookup/auth/register/profile/badge/ban
- `Item.php` -> report CRUD/search/category/admin report ops
- `Message.php` -> comments/chat conversations/typing/online status
- `Announcement.php` -> announcement CRUD + active announcements
- `ContactRequest.php` -> contact request create/list/update
- `SystemConfig.php` -> key-value platform settings
- `Admin.php` -> present but currently empty

## Service (`app/Services`)

- `NotificationService.php` -> email/SMS scaffold (currently not wired into main flows)

---

## 4. Full Website Route/Page Flow (with file links)

| URL                  | Controller@Method      | Models Used | View File                  | CSS Linked in View                |
| -------------------- | ---------------------- | ----------- | -------------------------- | --------------------------------- |
| `/` or `/home/index` | `HomeController@index` | `Item`      | `resources/views/home.php` | `home.css` (+ global `style.css`) |

| `/home/success_stories` | `HomeController@success_stories` | `Item` | `resources/views/success_stories.php` | via controller `css => ['success']` + `style.css` |

| `/item/index` | `ItemController@index` -> `search()` | `Item` | `resources/views/items/index.php` | `search.css` + `style.css` |

| `/item/search` | `ItemController@search` | `Item` | `resources/views/items/index.php` | `search.css` + `style.css` |

| `/item/show/{id}` | `ItemController@show` | `Item`, `Message` | `resources/views/items/show.php` | `item-detail.css` + Leaflet CSS + `style.css` |

| `/item/create` | `ItemController@create` | `Item` | `resources/views/items/create.php` | `item-form.css` + Leaflet CSS + `style.css` |

| `/item/resolve/{id}` | `ItemController@resolve` | `Item` | no page (POST action + redirect) | - |

| `/map/index` | `MapController@index` | `Item` (+ DB) | `resources/views/map.php` | `map.css` + Leaflet CSS + `style.css` |

| `/map/api_markers` | `MapController@api_markers` | DB | JSON response | - |

| `/message/index` | `MessageController@index` | `Message` | `resources/views/messages/index.php` | `messages/index-inbox.css` + `style.css` |

| `/message/chat/{report_id}` | `MessageController@chat` | `Message`, `Item` | `resources/views/messages/chat.php` | `messages/chat-conversation.css` + `style.css` |

| `/message/store` | `MessageController@store` | `Message`, `Item` | no page (POST action + redirect) | - |

| `/message/apiGetMessages/{id}` | `MessageController@apiGetMessages` | `Message`, `Item` | JSON response | - |

| `/message/apiSetTyping` | `MessageController@apiSetTyping` | `Message` | JSON response | - |

| `/message/apiSendMessage` | `MessageController@apiSendMessage` | `Message`, `Item` | JSON response | - |

| `/user/dashboard` | `UserController@dashboard` | `User`, `Item` | `resources/views/dashboard.php` | `dashboard.css` + `style.css` |

| `/user/profile` | `UserController@profile` | `User` | `resources/views/user/profile.php` | tries `profile.css` (actual file is `Profile.css`) + `style.css` |

| `/user/updateProfile` | `UserController@updateProfile` | `User` | no page (POST action + redirect) | - |

| `/auth/login` | `AuthController@login` | `User` | `resources/views/auth/login.php` | `login.css` + `style.css` |

| `/auth/register` | `AuthController@register` | `User` | `resources/views/auth/register.php` | inline styles + `style.css` |

| `/auth/forgot` | `AuthController@forgot` | (none/model not needed) | `resources/views/auth/forgot.php` | inline styles + `style.css` |

| `/auth/reset` | `AuthController@reset` | (none/model not needed) | `resources/views/auth/reset.php` | inline styles + `style.css` |

| `/auth/logout` | `AuthController@logout` | - | no view (session destroy + redirect) | - |

| `/page/about` | `PageController@about` | - | `resources/views/pages/about.php` | inline styles + `style.css` |

| `/page/faq` | `PageController@faq` | - | `resources/views/pages/faq.php` | inline styles + `style.css` |

| `/page/contact` | `PageController@contact` | `ContactRequest` | `resources/views/pages/contact.php` | inline styles + `style.css` |

| `/page/set_language/{lang}` | `PageController@set_language` | - | no view (redirect back) | - |

| `/admin/dashboard` | `AdminController@dashboard` | `User`, `Item` | `resources/views/admin/dashboard.php` | `admin/admin_dashboard.css` + `style.css` |

| `/admin/users` | `AdminController@users` | `User` | `resources/views/admin/users.php` | `admin/admin_dashboard.css`, `admin/users.css`, `style.css` |

| `/admin/reports` | `AdminController@reports` | `Item` | `resources/views/admin/reports.php` | `admin/admin_dashboard.css`, `admin/reports.css`, `style.css` |

| `/admin/items` | `AdminController@items` | `Item` | `resources/views/admin/items.php` | `admin/admin_dashboard.css` + `style.css` |

| `/admin/edit_report/{id}` | `AdminController@edit_report` | `Item` | `resources/views/admin/edit_report.php` | mainly inline + `style.css` |

| `/admin/update_report/{id}` | `AdminController@update_report` | `Item` | no view (POST action + redirect) | - |

| `/admin/delete_report/{id}` | `AdminController@delete_report` | `Item` | no view (POST action + redirect) | - |

| `/admin/settings` | `AdminController@settings` | `SystemConfig`, `Item` | expects `resources/views/admin/settings.php` (actual file is `setting.php`) | `admin/admin_dashboard.css` + inline + `style.css` |

| `/admin/add_category` | `AdminController@add_category` | direct DB | no view (POST action + redirect) | - |

| `/admin/delete_category/{id}` | `AdminController@delete_category` | direct DB | no view (POST action + redirect) | - |

| `/admin/update_config` | `AdminController@update_config` | `SystemConfig` | no view (POST action + redirect) | - |

| `/admin/monitor` | `AdminController@monitor` | - | `resources/views/admin/monitor.php` | `admin/admin_dashboard.css` + `style.css` |

| `/admin/monitor_stats` | `AdminController@monitor_stats` | direct DB | JSON response | - |

| `/admin/contact_requests` | `AdminController@contact_requests` | `ContactRequest` | `resources/views/admin/contact_requests.php` | `admin/admin_dashboard.css` + `style.css` |

| `/admin/resolve_contact/{id}` | `AdminController@resolve_contact` | `ContactRequest` | no view (POST action + redirect) | - |

| `/admin/backup` | `AdminController@backup` | - | `resources/views/admin/backup.php` | `admin/admin_dashboard.css` + `style.css` |

| `/admin/backup_download` | `AdminController@backup_download` | DB | SQL download | - |

| `/admin/restore_backup` | `AdminController@restore_backup` | DB | no view (POST action + redirect) | - |

| `/admin/announcements` | `AdminController@announcements` | `Announcement` | `resources/views/admin/announcements.php` | `admin/admin_dashboard.css`, `admin/announcements.css`, `style.css` |

| `/admin/add_announcement` | `AdminController@add_announcement` | `Announcement` | no view (POST action + redirect) | - |

| `/admin/delete_announcement/{id}` | `AdminController@delete_announcement` | `Announcement` | no view (POST action + redirect) | - |

| `/admin/toggle_announcement/{id}` | `AdminController@toggle_announcement` | `Announcement` | no view (POST action + redirect) | - |

| `/admin/export_data` | `AdminController@export_data` | `Item` | CSV download | - |

---

## 5. Layout and Shared UI Linking

## `resources/views/layouts/header.php`

What it does for almost all pages:

- Sets HTML head title.
- Loads fonts and Font Awesome.
- Loads **global base stylesheet**: `public/assets/css/style.css`.
- Optional additional CSS from `$data['css']`.
- Loads `assets/js/fluid-effect.js` (currently missing file).
- Loads active global announcements from `Announcement` model.
- Builds top navbar (browse, map, success, dashboard/profile/admin links).
- Adds language switch (`/page/set_language/{lang}`).
- Adds dark/light theme toggle.
- Shows flash toasts through helper.

## `resources/views/layouts/footer.php`

What it does:

- Renders platform/resource/legal footer links.
- Optional JS includes from `$data['js']` array.
- Closes page HTML.

---

## 6. CSS and JS Linking Map

## CSS files and where they are linked

- `public/assets/css/style.css` -> `layouts/header.php` (global on almost every MVC page)
- `public/assets/css/home.css` -> `resources/views/home.php`
- `public/assets/css/search.css` -> `resources/views/items/index.php`, `resources/views/search.php`
- `public/assets/css/item-form.css` -> `resources/views/items/create.php`
- `public/assets/css/item-detail.css` -> `resources/views/items/show.php`
- `public/assets/css/map.css` -> `resources/views/map.php`
- `public/assets/css/dashboard.css` -> `resources/views/dashboard.php`
- `public/assets/css/login.css` -> `resources/views/auth/login.php`
- `public/assets/css/success.css` -> loaded by controller data (`HomeController@success_stories`)
- `public/assets/css/messages/index-inbox.css` -> `resources/views/messages/index.php`
- `public/assets/css/messages/chat-conversation.css` -> `resources/views/messages/chat.php`
- `public/assets/css/admin/admin_dashboard.css` -> most admin pages
- `public/assets/css/admin/users.css` -> `resources/views/admin/users.php`
- `public/assets/css/admin/reports.css` -> `resources/views/admin/reports.php`
- `public/assets/css/admin/announcements.css` -> `resources/views/admin/announcements.php`
- `public/assets/css/Profile.css` -> physical file exists; profile view references lowercase `profile.css`
- Additional CSS files present but not clearly linked in current code flow:
  - `public/assets/css/chat.css`
  - `public/assets/css/admin-main.css`
  - `public/assets/css/admin-users.css`
  - `public/assets/css/admin/admin-reports.css`
  - `public/assets/css/admin/admin-items.css`
  - `public/assets/css/pages/about.css`
  - `public/assets/css/pages/contact.css`
  - `public/assets/css/pages/faq.css`
  - `public/assets/css/auth/forgot.css`
  - `public/assets/css/auth/reset.css`
  - `public/assets/css/register.css`

## JS files

- `public/assets/js/auth.js` -> present (password toggles, validation, preview, toasts), but no clear script include found in current rendered views.
- `public/assets/js/main.js` -> present but empty.
- `public/assets/js/map.js` -> present but empty.
- `public/assets/js/fluid-effect.js` -> referenced in header, but file is currently missing.

---

## 7. Database Layer and Data Files

- `config/setup.sql`
  - Main schema bootstrap.
  - Creates tables like users, categories, reports, comments, admins, announcements, chat_status.
  - Inserts seed categories and sample users.
- `config/sql.db`
  - SQLite DB file present (legacy/dev artifact in this structure).
- Runtime DB access:
  - All main models use MySQL through `Database.php` PDO singleton.

---

## 8. Complete File Inventory (Current 97 files detected)

Below is the full file list detected from the project folder.

## Root docs + theme

- `README.md`
- `Theme/lost-found-palette.html`
- `Theme/work.md`

## Includes + config + legal

- `includes/helpers.php`
- `config/config.php`
- `config/setup.sql`
- `config/sql.db`
- `legal/privacy.php`
- `legal/terms.php`

## Core

- `app/Core/App.php`
- `app/Core/Controller.php`
- `app/Core/Database.php`
- `app/Core/HookManager.php`

## Controllers

- `app/Controllers/AdminController.php`
- `app/Controllers/AuthController.php`
- `app/Controllers/HomeController.php`
- `app/Controllers/ItemController.php`
- `app/Controllers/MapController.php`
- `app/Controllers/MessageController.php`
- `app/Controllers/PageController.php`
- `app/Controllers/UserController.php`

## Models

- `app/Models/Admin.php`
- `app/Models/Announcement.php`
- `app/Models/ContactRequest.php`
- `app/Models/Item.php`
- `app/Models/Message.php`
- `app/Models/SystemConfig.php`
- `app/Models/User.php`

## Services

- `app/Services/NotificationService.php`

## Public entry + server files

- `public/.htaccess`
- `public/index.php`
- `public/debug_log.txt`

## Public JS

- `public/assets/js/auth.js`
- `public/assets/js/main.js`
- `public/assets/js/map.js`

## Public CSS

- `public/assets/css/style.css`
- `public/assets/css/home.css`
- `public/assets/css/search.css`
- `public/assets/css/item-form.css`
- `public/assets/css/item-detail.css`
- `public/assets/css/map.css`
- `public/assets/css/dashboard.css`
- `public/assets/css/login.css`
- `public/assets/css/register.css`
- `public/assets/css/success.css`
- `public/assets/css/chat.css`
- `public/assets/css/admin-main.css`
- `public/assets/css/admin-users.css`
- `public/assets/css/Profile.css`
- `public/assets/css/auth/forgot.css`
- `public/assets/css/auth/reset.css`
- `public/assets/css/pages/about.css`
- `public/assets/css/pages/contact.css`
- `public/assets/css/pages/faq.css`
- `public/assets/css/messages/index-inbox.css`
- `public/assets/css/messages/chat-conversation.css`
- `public/assets/css/admin/admin_dashboard.css`
- `public/assets/css/admin/admin-items.css`
- `public/assets/css/admin/admin-reports.css`
- `public/assets/css/admin/announcements.css`
- `public/assets/css/admin/reports.css`
- `public/assets/css/admin/users.css`

## Public uploads (sample files currently present)

- `public/uploads/69abdc0f39334.png`
- `public/uploads/img_69ed8da22698e1.24234088_0.jpeg`

## Views - layouts

- `resources/views/layouts/header.php`
- `resources/views/layouts/footer.php`

## Views - top level

- `resources/views/home.php`
- `resources/views/dashboard.php`
- `resources/views/map.php`
- `resources/views/search.php`
- `resources/views/success_stories.php`

## Views - auth

- `resources/views/auth/login.php`
- `resources/views/auth/register.php`
- `resources/views/auth/forgot.php`
- `resources/views/auth/reset.php`

## Views - pages

- `resources/views/pages/about.php`
- `resources/views/pages/contact.php`
- `resources/views/pages/faq.php`

## Views - user

- `resources/views/user/profile.php`

## Views - items

- `resources/views/items/index.php`
- `resources/views/items/create.php`
- `resources/views/items/show.php`

## Views - messages

- `resources/views/messages/index.php`
- `resources/views/messages/chat.php`

## Views - admin

- `resources/views/admin/dashboard.php`
- `resources/views/admin/users.php`
- `resources/views/admin/reports.php`
- `resources/views/admin/items.php`
- `resources/views/admin/edit_report.php`
- `resources/views/admin/monitor.php`
- `resources/views/admin/contact_requests.php`
- `resources/views/admin/backup.php`
- `resources/views/admin/announcements.php`
- `resources/views/admin/setting.php`
- `resources/views/admin/login.php`

## Other placeholders

- `security/New Text Document.txt`
- `storage/New Text Document.txt`

---

## 9. Important Viva Notes (Current Linking Issues You Should Know)

These are not guesses; these are from current file/controller links:

1. **Admin settings view name mismatch**  
   Controller loads `admin/settings`, but file is `resources/views/admin/setting.php`.

2. **Terms/privacy view mismatch**  
   `PageController` tries `pages/terms` and `pages/privacy`, but only `legal/terms.php` and `legal/privacy.php` exist.

3. **Forgot password link mismatch**  
   Login view points to `/auth/forgot-password`, but controller route is `/auth/forgot`.

4. **Profile CSS case mismatch**  
   View references `assets/css/profile.css`, file is `Profile.css`.

5. **Missing JS file**  
   Header references `assets/js/fluid-effect.js`, file not present.

6. **Missing print CSS file**  
   `items/show.php` expects `assets/css/flyer-print.css`, file not present.

7. **Admin login page route mismatch risk**  
   `resources/views/admin/login.php` posts to `/admin/login`, but `AdminController` has no `login()` method.

These points are useful in viva when examiners ask:
**"What is implemented, what is wired, and what still needs cleanup?"**

---

## 10. One-Line Viva Summary

This project is a **custom PHP MVC Lost & Found platform** where request routing starts at `.htaccess` and `index.php`, flows through Core Router -> Controllers -> Models -> MySQL, and renders modular Views with shared Header/Footer and page-level CSS/JS for user, item, chat, map, and admin workflows.
