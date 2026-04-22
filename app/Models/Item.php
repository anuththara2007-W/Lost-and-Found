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

    public function getResolvedReports($limit = 20)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'resolved'
            ORDER BY r.date_posted DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchItems($keyword, $type, $category_id = null, $location = null, $date = null)
    {
        $query = "
            SELECT r.*, u.username, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'open'
        ";
        $params = [];
        
        if (!empty($keyword)) {
            $query .= " AND (r.title LIKE :keyword_title OR r.description LIKE :keyword_desc OR r.custom_category LIKE :keyword_cat)";
            $params[':keyword_title'] = '%' . $keyword . '%';
            $params[':keyword_desc'] = '%' . $keyword . '%';
            $params[':keyword_cat'] = '%' . $keyword . '%';
        }
        
        if (!empty($type) && in_array($type, ['lost', 'found'])) {
            $query .= " AND r.type = :type";
            $params[':type'] = $type;
        }

        if (!empty($category_id)) {
            $query .= " AND r.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }

        if (!empty($location)) {
            $query .= " AND r.location LIKE :location";
            $params[':location'] = '%' . $location . '%';
        }

        if (!empty($date)) {
            if ($date === 'today') {
                $query .= " AND DATE(r.date_posted) = CURDATE()";
            } elseif ($date === 'week') {
                $query .= " AND r.date_posted >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            } elseif ($date === 'month') {
                $query .= " AND r.date_posted >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            }
        }
        
        $query .= " ORDER BY r.date_posted DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
