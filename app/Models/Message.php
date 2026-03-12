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
}