<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Item
{ //encap
    private $db;

    public function __construct()
    {
        // Get database connection using Singleton pattern
        $this->db = Database::getInstance()->getConnection();
    }

    private function normalizeCategoryId($categoryId)
    {
        if ($categoryId === null) {
            return null;
        }

        $raw = trim((string)$categoryId);
        if ($raw === '' || strtolower($raw) === 'other' || !ctype_digit($raw)) {
            return null;
        }

        $normalized = (int)$raw;
        if ($normalized <= 0) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT 1 FROM categories WHERE category_id = :category_id LIMIT 1");
        $stmt->execute(['category_id' => $normalized]);

        return $stmt->fetchColumn() ? $normalized : null;
    }

    public function getRecentReports($limit = 6)
    {
        // Prepare SQL query to fetch reports with user and category info
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'open'
            ORDER BY r.date_posted DESC
            LIMIT :limit
        ");

         // Bind limit parameter safely as integer
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // Return all results as an array
        return $stmt->fetchAll();
    }

    public function getResolvedReports($limit = 20)
    {
        // Prepare SQL query to fetch resolved reports with user and category details
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'resolved'
            ORDER BY r.date_posted DESC
            LIMIT :limit
        ");
        // Bind limit value securely as integer
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // Return all fetched results
        return $stmt->fetchAll();
    }

    public function searchItems($keyword, $type, $category_id = null, $location = null, $date = null)
    {
        // Base query: get open reports with user and category details
        $query = "
            SELECT r.*, u.username, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'open'
        ";
        $params = [];   // Store values for prepared statement
        
        // Filter by keyword
        if (!empty($keyword)) {
            $query .= " AND (r.title LIKE :keyword_title OR r.description LIKE :keyword_desc OR r.custom_category LIKE :keyword_cat)";
            $params[':keyword_title'] = '%' . $keyword . '%';
            $params[':keyword_desc'] = '%' . $keyword . '%';
            $params[':keyword_cat'] = '%' . $keyword . '%';
        }
        
        // Filter by type 
        if (!empty($type) && in_array($type, ['lost', 'found'])) {
            $query .= " AND r.type = :type";
            $params[':type'] = $type;
        }

        // Filter by category
        if (!empty($category_id)) {
            $query .= " AND r.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }

         // Filter by location
        if (!empty($location)) {
            $query .= " AND r.location LIKE :location";
            $params[':location'] = '%' . $location . '%';
        }

        // Filter by date 
        if (!empty($date)) {
            if ($date === 'today') {
                $query .= " AND DATE(r.date_posted) = CURDATE()";
            } elseif ($date === 'week') {
                $query .= " AND r.date_posted >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            } elseif ($date === 'month') {
                $query .= " AND r.date_posted >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            }
        }
        
         // Sort results by newest first
        $query .= " ORDER BY r.date_posted DESC";
        
        // Prepare and execute query with parameters
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // Return results
        return $stmt->fetchAll();
    }

    public function addReport($data)
    {
        $categoryId = $this->normalizeCategoryId($data['category_id'] ?? null);

        // Prepare SQL query to insert report data
        $stmt = $this->db->prepare("
            INSERT INTO reports 
            (user_id, category_id, type, title, description, location, reward_amount, contact_info, image_path, latitude, longitude, custom_category, whatsapp_contact, allow_platform_message)
            VALUES 
            (:user_id, :category_id, :type, :title, :description, :location, :reward_amount, :contact_info, :image_path, :latitude, :longitude, :custom_category, :whatsapp_contact, :allow_platform_message)
        ");

        // Execute query with data
        $success = $stmt->execute([
            'user_id' => $data['user_id'],
            'category_id' => $categoryId,
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

        // If insert is successful
        if ($success) {
            $report_id = $this->db->lastInsertId();
            
            // Insert multiple images if provided
            if (!empty($data['images']) && is_array($data['images'])) {
                $imgStmt = $this->db->prepare("INSERT INTO report_images (report_id, image_path) VALUES (?, ?)");
                foreach ($data['images'] as $img) {
                    $imgStmt->execute([$report_id, $img]);
                }
            }
            return true; //success
        }
        return false;  //failed    
    }

    public function getCategories()
    {
        // Prepare query to fetch all categories sorted by name
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();    // Return all categories as an array
    }

    public function getReportsByUser($user_id)
    {
         // Prepare query to fetch user's reports with category name
        $stmt = $this->db->prepare("
            SELECT r.*, c.name as category_name
            FROM reports r
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.user_id = :user_id
            ORDER BY r.date_posted DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();    // Return all reports of that user
    }

     public function getReportById($id)
    {
        // Prepare query to fetch report with user and category details
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, u.email, u.phone, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.report_id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();    // Return single report
    }

     public function markResolved($id, $user_id)
    {
        // Prepare query to update report status to 'resolved'
        $stmt = $this->db->prepare("UPDATE reports SET status = 'resolved' WHERE report_id = :id AND user_id = :user_id");
        
        // Execute query with report ID and user ID
        return $stmt->execute([
            'id' => $id,
            'user_id' => $user_id
        ]);
    }

    public function delete($id, $user_id)
    {
        // Prepare query to delete report
        $stmt = $this->db->prepare("DELETE FROM reports WHERE report_id = :id AND user_id = :user_id");
        
        // Execute query with report ID and user ID
        return $stmt->execute([
            'id' => $id,
            'user_id' => $user_id
        ]);
    }

     // --- Admin Methods ---
    public function getAllReports()
    {
        // Prepare query to fetch all reports with user and category details
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, u.email, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            ORDER BY r.date_posted DESC
        ");
        $stmt->execute();

        // Fetch all results and return as an array
        return $stmt->fetchAll();
    }

    public function getAdminReports(array $filters = [])
    {
         // Base query to get reports with user and category details
        $query = "
            SELECT r.*, u.username, u.email, c.name as category_name
            FROM reports r
            LEFT JOIN users u ON r.user_id = u.user_id
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE 1=1
        ";
        $params = [];    // Array to store query parameters

        // If search keyword is given
        if (!empty($filters['q'])) {
            $query .= " AND (r.title LIKE :q OR r.description LIKE :q OR r.location LIKE :q OR u.username LIKE :q)";
            $params['q'] = '%' . $filters['q'] . '%';
        }

        // Filter by type
        if (!empty($filters['type']) && in_array($filters['type'], ['lost', 'found'], true)) {
            $query .= " AND r.type = :type";
            $params['type'] = $filters['type'];
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query .= " AND r.status = :status";
            $params['status'] = $filters['status'];
        }

        //Filter by specific user
        if (!empty($filters['user_id'])) {
            $query .= " AND r.user_id = :user_id";
            $params['user_id'] = (int)$filters['user_id'];
        }

        // Order results by latest reports
        $query .= " ORDER BY r.date_posted DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();   // Return all matching results
    }

    public function updateReportAdmin($reportId, array $data)
    {
        $categoryId = $this->normalizeCategoryId($data['category_id'] ?? null);

        // Prepare SQL query to update a report
        $stmt = $this->db->prepare("
            UPDATE reports
            SET title = :title,
                description = :description,
                location = :location,
                status = :status,
                type = :type,
                category_id = :category_id
            WHERE report_id = :id
        ");

        // Execute query with actual values
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'status' => $data['status'],
            'type' => $data['type'],
            'category_id' => $categoryId,
            'id' => (int)$reportId
        ]);
    }

    public function deleteReport($id)
    {
        try {
            // Start a transaction
            $this->db->beginTransaction();

            // Delete related comments % images of the report
            $this->db->prepare("DELETE FROM comments WHERE report_id = :id")->execute(['id' => $id]);
            $this->db->prepare("DELETE FROM report_images WHERE report_id = :id")->execute(['id' => $id]);

            // Now delete the main report
            $stmt = $this->db->prepare("DELETE FROM reports WHERE report_id = :id");
            $stmt->execute(['id' => $id]);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {

            // If any error occurs, undo all changes
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }
}


