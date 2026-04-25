<?php
namespace App\Models;

use App\Core\Database;

class ContactRequest
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    private function ensureTable()
    {
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

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO contact_requests (name, email, message)
            VALUES (:name, :email, :message)
        ");
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message']
        ]);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM contact_requests ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function updateStatusAndResponse($id, $status, $response)
    {
        $stmt = $this->db->prepare("
            UPDATE contact_requests
            SET status = :status, admin_response = :response
            WHERE request_id = :id
        ");
        return $stmt->execute([
            'status' => $status,
            'response' => $response,
            'id' => (int)$id
        ]);
    }
}
