<?php
/**
 * Lost & Found — Application Configuration
 * C:\xampp\htdocs\Lost & Found\Lost-and-Found\config\config.php
 */

// ── Base Paths ──────────────────────────────────────────────
define('ROOT',     'C:/xampp/htdocs/Lost & Found/Lost-and-Found');
define('BASE_URL', 'http://localhost/Lost%20&%20Found/Lost-and-Found');

// ── Database ─────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'lost_and_found');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ── App ──────────────────────────────────────────────────────
define('APP_NAME',    'Lost & Found');
define('APP_ENV',     'development');   // 'production' on live
define('APP_DEBUG',   true);

// ── Session ──────────────────────────────────────────────────
define('SESSION_NAME',     'laf_session');
define('SESSION_LIFETIME', 7200);        // 2 hours

// ── Mail ─────────────────────────────────────────────────────
define('MAIL_HOST',     'smtp.mailtrap.io');
define('MAIL_PORT',     2525);
define('MAIL_USER',     '');
define('MAIL_PASS',     '');
define('MAIL_FROM',     'noreply@lostandfound.sg');
define('MAIL_FROM_NAME','Lost & Found');

// ── Upload limits ────────────────────────────────────────────
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024); // 2 MB
define('UPLOAD_DIR',      ROOT . '/public/uploads/');

// ── Error display ────────────────────────────────────────────
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// ── Autoloader ───────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $base = ROOT . '/';
    $map  = [
        'App\\Controllers\\' => 'app/Controllers/',
        'App\\Models\\'      => 'app/Models/',
        'App\\Core\\'        => 'app/Core/',
    ];
    foreach ($map as $prefix => $dir) {
        if (strncmp($prefix, $class, strlen($prefix)) === 0) {
            $relative = substr($class, strlen($prefix));
            $file     = $base . $dir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// ── Bootstrap helpers ────────────────────────────────────────
require_once ROOT . '/includes/helpers.php';

// ── Start session ────────────────────────────────────────────
session_name(SESSION_NAME);
session_start();