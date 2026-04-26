<?php
namespace App\Models;

// Import database class
use App\Core\Database;

// Announcement model handles announcement data
class Announcement
{
    // Database connection storage
    private $db;

    // Constructor runs automatically on object creation
    public function __construct()
    {
        // Get single database connection instance
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all announcements
    public function getAll()
    {
        // Prepare SQL to fetch all announcements sorted latest first
        $stmt = $this->db->prepare("SELECT * FROM announcements ORDER BY date_posted DESC");

        // Execute query
        $stmt->execute();

        // Return all results
        return $stmt->fetchAll();
    }

    // Get only active announcements
    public function getActive()
    {
        // Prepare SQL for active announcements only
        $stmt = $this->db->prepare("
            SELECT * FROM announcements 
            WHERE expiry_date IS NULL 
            OR expiry_date >= NOW()
            ORDER BY date_posted DESC
        ");

        // Execute query
        $stmt->execute();

        // Return results as associative array
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Create new announcement
    public function create($data)
    {
        // Prepare insert query with placeholders
        $stmt = $this->db->prepare("INSERT INTO announcements (title, content, type, is_active) VALUES (:title, :content, :type, :is_active)");

        // Execute query with data binding
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'],
            'is_active' => $data['is_active'] ?? 1
        ]);
    }

    // Delete announcement by ID
    public function delete($id)
    {
        // Prepare delete query
        $stmt = $this->db->prepare("DELETE FROM announcements WHERE announcement_id = :id");

        // Execute delete operation
        return $stmt->execute(['id' => $id]);
    }

    // Update announcement active status
    public function toggleActive($id, $status)
    {
        // Prepare update query for status change
        $stmt = $this->db->prepare("UPDATE announcements SET is_active = :status WHERE announcement_id = :id");

        // Execute update query
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}