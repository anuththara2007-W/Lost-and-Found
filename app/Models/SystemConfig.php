
//This model is used to store and manage system settings of your app
<?php
namespace App\Models;

// Import database connection class
use App\Core\Database;

// SystemConfig model handles app settings
class SystemConfig
{
    // Store database connection
    private $db;

    // Constructor runs on object creation
    public function __construct()
    {
        // Get singleton database connection
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all system configurations
    public function getAllConfigs()
    {
        // Fetch key-value settings from database
        $stmt = $this->db->prepare("SELECT setting_key, setting_value FROM system_config");

        // Execute query
        $stmt->execute();

        // Get all results
        $results = $stmt->fetchAll();

        // Store configs in key-value format
        $configs = [];

        // Convert rows into associative array
        foreach($results as $row) {
            $configs[$row['setting_key']] = $row['setting_value'];
        }

        // Return final config array
        return $configs;
    }

    // Insert or update configuration
    public function updateConfig($key, $value)
    {
        // Insert new or update existing config
        $stmt = $this->db->prepare("
            INSERT INTO system_config (setting_key, setting_value) 
            VALUES (:key, :value) 
            ON DUPLICATE KEY UPDATE setting_value = :value
        ");

        // Execute query with values
        return $stmt->execute(['key' => $key, 'value' => $value]);
    }
}