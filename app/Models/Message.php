<?php

namespace App\Models;
use App\Core\Database;
use PDO;

/**
 * Message Model
 * 
 * This model handles all database operations related to the 
 * messaging and commenting system of the Lost and Found system.
 * It includes retrieving conversations, adding comments, 
 * managing typing indicators, and checking user activity status.
 */
class Message
{
     private $db;

    public function __construct()//Initializes the database connection using the Database singleton.
    {
        $this->db = Database::getInstance()->getConnection();
    }

     public function getCommentsByReport($report_id)// This function retrieves all comments posted for a particular lost or found report along with the user information.
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

    public function getConversationsForUser($user_id)//Get all conversations related to a specific user
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

     public function addComment($report_id, $user_id, $comment_text, $parent_id = 0)//Add a new comment to a report
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
}