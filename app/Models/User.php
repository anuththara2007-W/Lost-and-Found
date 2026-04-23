<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{

    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
        public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    
    public function register($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, full_name, email, phone, password_hash) VALUES (:username, :full_name, :email, :phone, :password_hash)");
        
        return $stmt->execute([
            'username' => $data['username'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password_hash' => $data['password_hash']
        ]);
    }
}