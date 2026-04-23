<?php
/**
 * public/index.php
 * Entry point for the application.
 */

// Load configuration which also starts the session
require_once dirname(__DIR__) . '/config/config.php';
require_once ROOT . '/includes/helpers.php';

// PSR-4 Autoloader for the App namespace
spl_autoload_register(function ($class) {
    // Example: App\Controllers\HomeController -> app/Controllers/HomeController.php
    $prefix = 'App\\';
    $base_dir = ROOT . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the Core App Route
$app = new App\Core\App();
