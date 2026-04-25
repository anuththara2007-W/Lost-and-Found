<?php
namespace App\Models;

use App\Core\Database;

class SystemConfig
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllConfigs()
    {
        $stmt = $this->db->prepare("SELECT setting_key, setting_value FROM system_config");
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $configs = [];
        foreach($results as $row) {
            $configs[$row['setting_key']] = $row['setting_value'];
        }
        return $configs;
    }

    public function updateConfig($key, $value)
    {
        $stmt = $this->db->prepare("
            INSERT INTO system_config (setting_key, setting_value) 
            VALUES (:key, :value) 
            ON DUPLICATE KEY UPDATE setting_value = :value
        ");
        return $stmt->execute(['key' => $key, 'value' => $value]);
    }
}
