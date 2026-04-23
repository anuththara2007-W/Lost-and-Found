<?php
/**
 * app/Core/Database.php
 * PDO Singleton — used by all models
 */

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        // Build DSN safely from config constants
        $dsn = 'mysql:host=' . \DB_HOST .
               ';port=' . \DB_PORT .
               ';dbname=' . \DB_NAME .
               ';charset=' . \DB_CHARSET;

        try {
            $this->pdo = new PDO($dsn, \DB_USER, \DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

        } catch (PDOException $e) {

            // Show real error in development
            if (defined('APP_DEBUG') && APP_DEBUG) {
                die("❌ Database Connection Failed: " . $e->getMessage());
            }

            // Safe message in production
            die("Database connection failed. Please try again later.");
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Prevent cloning/unserializing
    private function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize Database singleton");
    }
}