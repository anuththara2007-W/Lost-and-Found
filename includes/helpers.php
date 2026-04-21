<?php
/**
 * Application Helpers
 */

if (!function_exists('escape')) {
    /**
     * Escape HTML entities for XSS protection
     *
     * @param string $string
     * @return string
     */
    function escape(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * includes/helpers.php
 * Global helper functions
 */

/**
 * Redirect to a specific URL safely
 */
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

/**
 * Sanitize output for HTML to prevent XSS
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format a date string
 */
function formatDate($dateString) {
    return date('M j, Y, g:i A', strtotime($dateString));
}

/**
 * Check if the user is currently logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Ensure a user is logged in, or redirect them to login page
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['flash_error'] = 'Please log in to access that page.';
        redirect('/auth/login');
    }

    // Verify user still exists in DB
    $db = \App\Core\Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT user_id FROM users WHERE user_id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    if (!$stmt->fetchColumn()) {
        unset($_SESSION['user_id']);
        $_SESSION['flash_error'] = 'Your session is invalid or your account was removed. Please log in again.';
        redirect('/auth/login');
    }
}

/**
 * Form helpers: get old value if validation fails
 */
function old($key, $default = '') {
    return isset($_SESSION['old'][$key]) ? escape($_SESSION['old'][$key]) : $default;
}

/**
 * Clear old input from session (call at the end of form rendering)
 */
function clearOld() {
    unset($_SESSION['old']);
}

/**
 * Display and clear Flash Messages
 */
function displayFlashMessages() {
    if (isset($_SESSION['flash_success'])) {
        echo '<div class="alert alert-success" style="background:var(--sage-lt); color:var(--sage); padding:10px; border-radius:8px; margin-bottom:15px;">' . escape($_SESSION['flash_success']) . '</div>';
        unset($_SESSION['flash_success']);
    }
    if (isset($_SESSION['flash_error'])) {
        echo '<div class="alert alert-error" style="background:var(--terracotta-lt); color:var(--terracotta); padding:10px; border-radius:8px; margin-bottom:15px;">' . escape($_SESSION['flash_error']) . '</div>';
        unset($_SESSION['flash_error']);
    }
}
