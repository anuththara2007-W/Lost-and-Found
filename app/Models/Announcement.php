<?php
namespace App\Models;

use App\Core\Database;

class Announcement
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM announcements ORDER BY date_posted DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

public function getActive()
{
    $stmt = $this->db->prepare("
        SELECT * FROM announcements 
        WHERE expiry_date IS NULL 
        OR expiry_date >= NOW()
        ORDER BY date_posted DESC
    ");
    
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO announcements (title, content, type, is_active) VALUES (:title, :content, :type, :is_active)");
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'],
            'is_active' => $data['is_active'] ?? 1
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM announcements WHERE announcement_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleActive($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE announcements SET is_active = :status WHERE announcement_id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
