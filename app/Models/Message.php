<?php
namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Message Model
 * -------------
 * Handles all database operations related to:
 * - Messages (comments)
 * - Conversations
 * - Typing status
 * - Online status
 */
class Message
{
    private $db;//database connection
    private $columnCache = []; // store checked column
    private $tableCache = [];  //store checked tables 

    /**
     * Constructor
     * Establish database connection 
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all messages for a specific report (chat)
     */
    public function getCommentsByReport($report_id)
    {
        // Join comments with user data
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

    /**
     * Get all conversations related to a user
     */
    public function getConversationsForUser($user_id)
    {
        // Get reports where user is owner OR has sent messages
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

    /**
     * Add a simple comment (message)
     */
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

    /**
     * Add comment with optional attachment
     */
    public function addCommentWithAttachment($report_id, $user_id, $comment_text, $attachment_path, $parent_id = 0)
    {
        // Check if optional columns exist
        $hasAttachment = $this->hasColumn('comments', 'attachment_path');
        $hasParent = $this->hasColumn('comments', 'parent_id');

        // Base columns exists
        $columns = ['report_id', 'user_id', 'comment_text'];
        $placeholders = [':report_id', ':user_id', ':comment_text'];

        $params = [
            'report_id' => $report_id,
            'user_id' => $user_id,
            'comment_text' => $comment_text
        ];

        // Add attachment column if exists
        if ($hasAttachment) {
            $columns[] = 'attachment_path';
            $placeholders[] = ':attachment_path';
            $params['attachment_path'] = $attachment_path;
        }

        // Add parent_id if exists
        if ($hasParent) {
            $columns[] = 'parent_id';
            $placeholders[] = ':parent_id';
            $params['parent_id'] = $parent_id;
        }

        // Build dynamic SQL query
        $sql = "INSERT INTO comments (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Update user's last activity time (for online status)
     */
    public function updateUserActivity($user_id) {
        if (!$this->hasColumn('users', 'last_activity')) {
            return true;
        }

        $stmt = $this->db->prepare("
            UPDATE users 
            SET last_activity = NOW() 
            WHERE user_id = :uid
        ");

        return $stmt->execute(['uid' => $user_id]);
    }

    /**
     * Set typing status (real-time typing indicator)
     */
    public function setTyping($report_id, $user_id, $is_typing) {
        if (!$this->hasTable('chat_status')) {
            return false;
        }

        $stmt = $this->db->prepare("
            INSERT INTO chat_status (report_id, user_id, is_typing, last_typed)
            VALUES (:rid, :uid, :typing, NOW())
            ON DUPLICATE KEY UPDATE 
                is_typing = :typing, 
                last_typed = NOW()
        ");

        return $stmt->execute([
            'rid' => $report_id,
            'uid' => $user_id,
            'typing' => $is_typing
        ]);
    }

    /**
     * Get users currently typing in a chat
     */
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

        $stmt->execute([
            'rid' => $report_id,
            'uid' => $exclude_user_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Check if a user is online (based on recent activity)
     */
    public function isUserOnline($user_id) {
        if (!$this->hasColumn('users', 'last_activity')) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT 1 FROM users 
            WHERE user_id = :uid 
            AND last_activity > DATE_SUB(NOW(), INTERVAL 15 SECOND)
        ");

        $stmt->execute(['uid' => $user_id]);

        return $stmt->fetchColumn() ? true : false;
    }

    /**
     * Check if table exists (with caching)
     */
    private function hasTable($table)
    {
        if (isset($this->tableCache[$table])) {
            return $this->tableCache[$table];
        }

        $safeTable = $this->db->quote($table);
        $stmt = $this->db->query("SHOW TABLES LIKE {$safeTable}");

        $this->tableCache[$table] = $stmt ? (bool)$stmt->fetchColumn() : false;

        return $this->tableCache[$table];
    }

    /**
     * Check if column exists (with caching)
     */
    private function hasColumn($table, $column)
    {
        $key = $table . '.' . $column;

        if (isset($this->columnCache[$key])) {
            return $this->columnCache[$key];
        }

        $safeColumn = $this->db->quote($column);
        $stmt = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE {$safeColumn}");

        $this->columnCache[$key] = $stmt ? (bool)$stmt->fetchColumn() : false;

        return $this->columnCache[$key];
    }
}