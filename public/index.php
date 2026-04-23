<?php
/**
 * public/index.php
 * Entry point for the application.
 */

// Load config (must define ROOT)
require_once dirname(__DIR__) . '/config/config.php';

// Helpers (optional)
require_once ROOT . '/includes/helpers.php';

// PSR-4 Autoloader for App namespace
spl_autoload_register(function ($class) {

    $prefix = 'App\\';
    $base_dir = ROOT . '/app/';

    // Check if class uses App namespace
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Get relative class name
    $relative_class = substr($class, strlen($prefix));

    // Convert namespace to file path
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Load file if exists
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Debug (optional)
        die("Autoload failed: " . $file);
    }
});

// Run the application
$app = new \App\Core\App();