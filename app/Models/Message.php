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
}