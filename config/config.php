<?php
/**
 * config/config.php
 * C:/xampp/htdocs/Lost & Found/Lost-and-Found/config/config.php
 *
 * Uses __DIR__ so ROOT always resolves correctly regardless of which
 * file includes this. BASE_URL uses a plain URL (no %20 encoding)
 * which is fine for <a href> but NOT used in header() redirects
 * (we use relative redirects instead).
 */

// Absolute filesystem path to project root (no trailing slash)
define('ROOT', dirname(__DIR__));

// Web-accessible base URL — auto-detected from current request

        //Detect protocol (secure or not) like if true HTTPS is on,otherwise http
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

        //$_SERVER['HTTP_HOST'] → current domain | ?? 'localhost' → fallback if not set
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        //Get correct folder path for project
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));

// Remove trailing slash for clean URL path
$basePath = rtrim($scriptDir, '/');

/*$scheme → http/https
'://' → standard URL separator
$host → domain
$basePath → project folder */
define('BASE_URL', $scheme . '://' . $host . $basePath);

// ── Database ───────────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_PORT',    '3306');
define('DB_NAME',    'lost_and_found');
define('DB_USER',    'root');    // default XAMPP user
define('DB_PASS',    '');        // default XAMPP password (blank)
define('DB_CHARSET', 'utf8mb4'); // Full Unicode support including emojis and all languages

// ── Debug ──────────────────────────────────────────────────────────
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// ── Timezone ───────────────────────────────────────────────────────
date_default_timezone_set('Asia/Singapore');

// ── Session — only start once ──────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { // Check session not started

    session_name('laf_sess'); // Set custom session name

    session_set_cookie_params([ // Set session cookie settings
        'lifetime' => 7200, // Session expires in 2 hours
        'path'     => '/', // Available for entire site
        'httponly' => true, // Block JavaScript access
        'samesite' => 'Lax', // Prevent Cross-Site Request Forgery attacks
    ]);// CSRF is an attack where a user is tricked into sending unwanted requests while logged in

    session_start(); // Start session
}