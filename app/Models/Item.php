<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Item
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRecentReports($limit = 6)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'open'
            ORDER BY r.date_posted DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
