<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        // step by step getting database
        $databaseObject = Database::getInstance();
        $connection = $databaseObject->getConnection();
        $this->db = $connection;
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->db->prepare($query);

        $params = array();
        $params['email'] = $email;

        $stmt->execute($params);

        $result = $stmt->fetch();

        return $result;
    }

    public function findByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";

        $stmt = $this->db->prepare($query);

        $params = array();
        $params['username'] = $username;

        $stmt->execute($params);

        $user = $stmt->fetch();

        return $user;
    }

    public function findById($id)
    {
        $query = "SELECT * FROM users WHERE user_id = :id";

        $stmt = $this->db->prepare($query);

        $params = array();
        $params['id'] = $id;

        $stmt->execute($params);

        $data = $stmt->fetch();

        return $data;
    }

    public function register($data)
    {
        $query = "INSERT INTO users 
                  (username, full_name, email, phone, password_hash) 
                  VALUES 
                  (:username, :full_name, :email, :phone, :password_hash)";

        $stmt = $this->db->prepare($query);

        $phoneValue = null;
        if (isset($data['phone'])) {
            $phoneValue = $data['phone'];
        }

        $params = array();
        $params['username'] = $data['username'];
        $params['full_name'] = $data['full_name'];
        $params['email'] = $data['email'];
        $params['phone'] = $phoneValue;
        $params['password_hash'] = $data['password_hash'];

        $result = $stmt->execute($params);

        return $result;
    }

    public function login($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user) {
            $hash = $user['password_hash'];

            if (password_verify($password, $hash)) {
                return $user;
            }
        }

        return false;
    }

    public function updateProfile($userId, $data)
    {
        $query = "UPDATE users SET full_name = :full_name, phone = :phone";

        $params = array();
        $params['full_name'] = $data['full_name'];
        $params['phone'] = $data['phone'];
        $params['user_id'] = $userId;

        if (isset($data['profile_image'])) {
            $query = $query . ", profile_image = :profile_image";
            $params['profile_image'] = $data['profile_image'];
        }

        $query = $query . " WHERE user_id = :user_id";

        $stmt = $this->db->prepare($query);

        $result = $stmt->execute($params);

        return $result;
    }

    public function getAllUsers()
    {
        $query = "SELECT user_id, username, email, full_name, phone, date_joined as created_at, badge_status, is_banned 
                  FROM users 
                  ORDER BY date_joined DESC";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $users = $stmt->fetchAll();

        return $users;
    }

    public function updateBadge($userId, $badge)
    {
        try {
            $query = "UPDATE users SET badge_status = :badge WHERE user_id = :id";

            $stmt = $this->db->prepare($query);

            $params = array();
            $params['badge'] = $badge;
            $params['id'] = $userId;

            $result = $stmt->execute($params);

            return $result;

        } catch (\PDOException $e) {

            error_log("Failed to update badge. Missing column? " . $e->getMessage());

            return false;
        }
    }

    public function toggleBan($userId, $isBanned)
    {
        $query = "UPDATE users SET is_banned = :ban WHERE user_id = :id";

        $stmt = $this->db->prepare($query);

        $params = array();
        $params['ban'] = $isBanned;
        $params['id'] = $userId;

        $result = $stmt->execute($params);

        return $result;
    }
}