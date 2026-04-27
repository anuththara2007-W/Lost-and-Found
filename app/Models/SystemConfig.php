<?php

// This model is used to store and manage system settings of your app

namespace App\Models;

use App\Core\Database;

class SystemConfig
{
    // Store database connection
    private $db;

    // Constructor runs automatically when object is created
    public function __construct()
    {
        // Get singleton database connection
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all system configurations (key-value format)
    public function getAllConfigs()
    {
        $stmt = $this->db->prepare("
            SELECT setting_key, setting_value 
            FROM system_config
        ");

        $stmt->execute();

        $results = $stmt->fetchAll();

        $configs = [];

        foreach ($results as $row) {
            $configs[$row['setting_key']] = $row['setting_value'];
        }

        return $configs;
    }

    // Insert or update configuration
    public function updateConfig($key, $value)
    {
        $stmt = $this->db->prepare("
            INSERT INTO system_config (setting_key, setting_value)
            VALUES (:key, :value1)
            ON DUPLICATE KEY UPDATE setting_value = :value2
        ");

        return $stmt->execute([
            'key' => $key,
            'value1' => $value,
            'value2' => $value
        ]);
    }
}