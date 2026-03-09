<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
     private $db;
 //feat: initialize User model with database connection
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}