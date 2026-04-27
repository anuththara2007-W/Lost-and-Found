<?php
//creates a one single database connection for the whole system

namespace App\Core;

use PDO;
use PDOException;

class Database
{
   //hold the single instanec of this class
    private static ?Database $instance = null;

    //pdo connection object
    private PDO $pdo;

    //prevents creating multiple objects
    //connection is only cretate once
    private function __construct()
    {

        //create connection String
        $dsn = sprintf( //sprintf() = string formatter
            'mysql:host=%s;port=%s;dbname=%s;charset=%s', //%s = “put a string here”
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
                PDO::ATTR_EMULATE_PREPARES   => false,                  //  real prepared statements
                
            ]);

        } catch (PDOException $e) {

            // If debugging is enabled, show error details
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                die('<b>Database connection failed:</b> ' . htmlspecialchars($e->getMessage()));
            }

            // Otherwise show generic message
            die('Database connection failed. Please try again later.');
        }
    }
    
    //ensure only one connection is globally cerated
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(); // create only once
        }
        return self::$instance;
    }

   //used by models to run sql connection
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    //prevent cloning
    private function __clone() {}

    //stops restoring objects from the string
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}