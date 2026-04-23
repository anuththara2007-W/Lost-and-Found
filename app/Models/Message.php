<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Message
{
    private $db;
    private $columnCache = [];
    private $tableCache = [];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCommentsByReport($report_id)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.profile_image 
            FROM comments c
            JOIN users u ON c.user_id = u.user_id
            WHERE c.report_id = :report_id
            ORDER BY c.created_at ASC
        ");
        $stmt->execute(['report_id' => $report_id]);
        return $stmt->fetchAll();
    }

    public function getConversationsForUser($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT r.*, c.name as category_name
            FROM reports r
            LEFT JOIN categories c ON r.category_id = c.category_id
            LEFT JOIN comments msg ON msg.report_id = r.report_id
            WHERE r.user_id = :user_id1 OR msg.user_id = :user_id2
            ORDER BY r.date_posted DESC
        ");
        $stmt->execute([
            'user_id1' => $user_id,
            'user_id2' => $user_id
        ]);
        return $stmt->fetchAll();
    }

    public function addComment($report_id, $user_id, $comment_text, $parent_id = 0)
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (report_id, user_id, comment_text, parent_id)
            VALUES (:report_id, :user_id, :comment_text, :parent_id)
        ");
        return $stmt->execute([
            'report_id' => $report_id,
            'user_id' => $user_id,
            'comment_text' => $comment_text,
            'parent_id' => $parent_id
        ]);
    }

    public function addCommentWithAttachment($report_id, $user_id, $comment_text, $attachment_path, $parent_id = 0)
    {
        // Keep compatibility with older schemas that may not have attachment_path/parent_id.
        $hasAttachment = $this->hasColumn('comments', 'attachment_path');
        $hasParent = $this->hasColumn('comments', 'parent_id');

        $columns = ['report_id', 'user_id', 'comment_text'];
        $placeholders = [':report_id', ':user_id', ':comment_text'];
        $params = [
            'report_id' => $report_id,
            'user_id' => $user_id,
            'comment_text' => $comment_text
        ];

        if ($hasAttachment) {
            $columns[] = 'attachment_path';
            $placeholders[] = ':attachment_path';
            $params['attachment_path'] = $attachment_path;
        }
        if ($hasParent) {
            $columns[] = 'parent_id';
            $placeholders[] = ':parent_id';
            $params['parent_id'] = $parent_id;
        }

        $sql = "INSERT INTO comments (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateUserActivity($user_id) {
        if (!$this->hasColumn('users', 'last_activity')) {
            return true;
        }
        $stmt = $this->db->prepare("UPDATE users SET last_activity = NOW() WHERE user_id = :uid");
        return $stmt->execute(['uid' => $user_id]);
    }

    public function setTyping($report_id, $user_id, $is_typing) {
        if (!$this->hasTable('chat_status')) {
            return false;
        }
        $stmt = $this->db->prepare("
            INSERT INTO chat_status (report_id, user_id, is_typing, last_typed)
            VALUES (:rid, :uid, :typing, NOW())
            ON DUPLICATE KEY UPDATE is_typing = :typing, last_typed = NOW()
        ");
        return $stmt->execute(['rid' => $report_id, 'uid' => $user_id, 'typing' => $is_typing]);
    }

    public function getTypingStatus($report_id, $exclude_user_id) {
        if (!$this->hasTable('chat_status')) {
            return [];
        }
        $stmt = $this->db->prepare("
            SELECT u.username 
            FROM chat_status cs
            JOIN users u ON cs.user_id = u.user_id
            WHERE cs.report_id = :rid 
              AND cs.user_id != :uid 
              AND cs.is_typing = 1 
              AND cs.last_typed > DATE_SUB(NOW(), INTERVAL 5 SECOND)
        ");
        $stmt->execute(['rid' => $report_id, 'uid' => $exclude_user_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isUserOnline($user_id) {
        if (!$this->hasColumn('users', 'last_activity')) {
            return false;
        }
        $stmt = $this->db->prepare("
            SELECT 1 FROM users 
            WHERE user_id = :uid AND last_activity > DATE_SUB(NOW(), INTERVAL 15 SECOND)
        ");
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetchColumn() ? true : false;
    }

    private function hasTable($table)
    {
        if (array_key_exists($table, $this->tableCache)) {
            return $this->tableCache[$table];
        }

        // MariaDB can reject placeholders in SHOW ... LIKE on some setups.
        $safeTable = $this->db->quote($table);
        $stmt = $this->db->query("SHOW TABLES LIKE {$safeTable}");
        $this->tableCache[$table] = $stmt ? (bool)$stmt->fetchColumn() : false;
        return $this->tableCache[$table];
    }

    private function hasColumn($table, $column)
    {
        $key = $table . '.' . $column;
        if (array_key_exists($key, $this->columnCache)) {
            return $this->columnCache[$key];
        }

        // Allow only known table names that this model checks.
        $allowedTables = ['comments', 'users', 'chat_status'];
        if (!in_array($table, $allowedTables, true)) {
            $this->columnCache[$key] = false;
            return false;
        }

        // MariaDB compatibility: avoid placeholders with SHOW ... LIKE.
        $safeColumn = $this->db->quote($column);
        $stmt = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE {$safeColumn}");
        $this->columnCache[$key] = $stmt ? (bool)$stmt->fetchColumn() : false;
        return $this->columnCache[$key];
    }
}