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

    //feat: add method to retrieve user by email
    
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    
}