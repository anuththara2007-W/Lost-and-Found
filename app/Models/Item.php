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
    public function addReport($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO reports 
            (user_id, category_id, type, title, description, location, reward_amount, contact_info, image_path, latitude, longitude, custom_category, whatsapp_contact, allow_platform_message)
            VALUES 
            (:user_id, :category_id, :type, :title, :description, :location, :reward_amount, :contact_info, :image_path, :latitude, :longitude, :custom_category, :whatsapp_contact, :allow_platform_message)
        ");

        $success = $stmt->execute([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'] ?: null,
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'reward_amount' => !empty($data['reward_amount']) ? (float)$data['reward_amount'] : 0.00,
            'contact_info' => $data['contact_info'],
            'image_path' => $data['image_path'] ?? null,
            'latitude' => !empty($data['latitude']) ? (is_array($data['latitude']) ? reset($data['latitude']) : $data['latitude']) : null,
            'longitude' => !empty($data['longitude']) ? (is_array($data['longitude']) ? reset($data['longitude']) : $data['longitude']) : null,
            'custom_category' => $data['custom_category'] ?? null,
            'whatsapp_contact' => $data['whatsapp_contact'] ?? null,
            'allow_platform_message' => isset($data['allow_platform_message']) ? (int)$data['allow_platform_message'] : 1
        ]);

        if ($success) {
            $report_id = $this->db->lastInsertId();
            
            // Insert multiple images if provided
            if (!empty($data['images']) && is_array($data['images'])) {
                $imgStmt = $this->db->prepare("INSERT INTO report_images (report_id, image_path) VALUES (?, ?)");
                foreach ($data['images'] as $img) {
                    $imgStmt->execute([$report_id, $img]);
                }
            }
            return true;
        }
        return false;
    }
     public function getCategories()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getReportsByUser($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, c.name as category_name
            FROM reports r
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.user_id = :user_id
            ORDER BY r.date_posted DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

     public function getReportById($id)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, u.email, u.phone, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.report_id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function markResolved($id, $user_id)
    {
        $stmt = $this->db->prepare("UPDATE reports SET status = 'resolved' WHERE report_id = :id AND user_id = :user_id");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $user_id
        ]);
    }

    public function delete($id, $user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM reports WHERE report_id = :id AND user_id = :user_id");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $user_id
        ]);
    }
