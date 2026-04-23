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
     public function login($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                return $user;
            }
        }
        return false;
    }

     public function updateProfile($userId, $data)
    {
        $query = "UPDATE users SET full_name = :full_name, phone = :phone";
        $params = [
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'user_id' => $userId
        ];

        if (array_key_exists('profile_image', $data)) {
            $query .= ", profile_image = :profile_image";
            $params['profile_image'] = $data['profile_image'];
        }

        $query .= " WHERE user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT user_id, username, email, full_name, phone, date_joined as created_at, badge_status, is_banned FROM users ORDER BY date_joined DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

}