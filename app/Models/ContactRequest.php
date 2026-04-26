<?php
namespace App\Models;

// Import database connection class
use App\Core\Database;

// ContactRequest model handles contact form messages
class ContactRequest
{
    // Store database connection
    private $db;

    // Constructor runs automatically on object creation
    public function __construct()
    {
        // Get singleton database connection
        $this->db = Database::getInstance()->getConnection();

        // Ensure table exists on startup
        $this->ensureTable();
    }

    // Create table if not exists
    private function ensureTable()
    {
        // Execute SQL to create contact_requests table
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS contact_requests (
                request_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(120) NOT NULL,
                email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('new','in_progress','resolved') DEFAULT 'new',
                admin_response TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }

    // Insert new contact request
    public function create(array $data)
    {
        // Prepare insert query with placeholders
        $stmt = $this->db->prepare("
            INSERT INTO contact_requests (name, email, message)
            VALUES (:name, :email, :message)
        ");

        // Execute insert with user data
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message']
        ]);
    }

    // Get all contact requests
    public function getAll()
    {
        // Fetch all records ordered by newest first
        $stmt = $this->db->query("SELECT * FROM contact_requests ORDER BY created_at DESC");

        // Return results
        return $stmt->fetchAll();
    }

    // Update status and admin response
    public function updateStatusAndResponse($id, $status, $response)
    {
        // Prepare update query for status and response
        $stmt = $this->db->prepare("
            UPDATE contact_requests
            SET status = :status, admin_response = :response
            WHERE request_id = :id
        ");

        // Execute update with provided values
        return $stmt->execute([
            'status' => $status,
            'response' => $response,
            'id' => (int)$id
        ]);
    }
}