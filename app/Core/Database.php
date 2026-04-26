<?php
/**
 * Database.php
 * -------------------------
 * This class handles the database connection using PDO.
 * It follows the Singleton Design Pattern to ensure
 * only ONE database connection exists throughout the application.
 */

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    /**
     * Holds the single instance of this class
     * (Singleton pattern)
     */
    private static ?Database $instance = null;

    /**
     * PDO connection object
     */
    private PDO $pdo;

    /**
     * Constructor is private to prevent direct object creation
     * This ensures only one database connection is created.
     */
    private function __construct()
    {
        // Build DSN (Data Source Name) for MySQL connection
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );

        try {
            // Create PDO connection with secure options
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // associative arrays
                PDO::ATTR_EMULATE_PREPARES   => false,                  // real prepared statements
            ]);

        } catch (PDOException $e) {

            // If debugging is enabled, show error details
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                die('<b>Database connection failed:</b> ' . htmlspecialchars($e->getMessage()));
            }

            // Otherwise show generic message (production safe)
            die('Database connection failed. Please try again later.');
        }
    }

    /**
     * Get the single instance of Database class
     * -----------------------------------------
     * This ensures only ONE connection is used globally.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(); // create only once
        }
        return self::$instance;
    }

    /**
     * Return PDO connection object
     * Used by Models to run queries
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Prevent cloning of object
     * (protects Singleton pattern)
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}