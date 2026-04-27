<?php

namespace App\Models;

// Import database connection class
use App\Core\Database;
use PDO;

// User model handles all user-related database operations
class User
{
    // Database connection storage
    private $db;

    // Constructor runs automatically when object is created
    public function __construct()
    {
        // Get singleton database instance
        $databaseObject = Database::getInstance();

        // Get PDO connection from database object
        $connection = $databaseObject->getConnection();

        // Store connection in class property
        $this->db = $connection;
    }

    // Find user by email
    public function findByEmail($email)
    {
        // SQL query to get user by email
        $query = "SELECT * FROM users WHERE email = :email";

        // Prepare query for security
        $stmt = $this->db->prepare($query);

        // Bind email parameter
        $params = array();
        $params['email'] = $email;

        // Execute query with parameters
        $stmt->execute($params);

        // Fetch single user record
        $result = $stmt->fetch();

        // Return user data
        return $result;
    }

    // Find user by username
    public function findByUsername($username)
    {
        // SQL query for username search
        $query = "SELECT * FROM users WHERE username = :username";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Bind username parameter
        $params = array();
        $params['username'] = $username;

        // Execute query
        $stmt->execute($params);

        // Fetch user data
        $user = $stmt->fetch();

        // Return user
        return $user;
    }

    // Find user by ID
    public function findById($id)
    {
        // SQL query to get user by ID
        $query = "SELECT * FROM users WHERE user_id = :id";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Bind ID parameter
        $params = array();
        $params['id'] = $id;

        // Execute query
        $stmt->execute($params);

        // Fetch user data
        $data = $stmt->fetch();

        // Return result
        return $data;
    }

    // Register new user
    public function register($data)
    {
        // Insert query for new user
        $query = "INSERT INTO users 
                  (username, full_name, email, phone, password_hash) 
                  VALUES 
                  (:username, :full_name, :email, :phone, :password_hash)";

        // Prepare SQL statement
        $stmt = $this->db->prepare($query);

        // Default phone value
        $phoneValue = null;

        // Check if phone exists
        if (isset($data['phone'])) {
            $phoneValue = $data['phone'];
        }

        // Bind parameters
        $params = array();
        $params['username'] = $data['username'];
        $params['full_name'] = $data['full_name'];
        $params['email'] = $data['email'];
        $params['phone'] = $phoneValue;
        $params['password_hash'] = $data['password_hash'];

        // Execute insert query
        $result = $stmt->execute($params);

        // Return success status
        return $result;
    }

    // User login check
    public function login($email, $password)
    {
        // Find user by email
        $user = $this->findByEmail($email);

        // If user exists
        if ($user) {

            // Get stored password hash
            $hash = $user['password_hash'];

            // Verify password
            if (password_verify($password, $hash)) {

                // Return user data if correct
                return $user;
            }
        }

        // Return false if login fails
        return false;
    }

    // Update user profile
    public function updateProfile($userId, $data)
    {
        // Base update query
        $query = "UPDATE users SET full_name = :full_name, phone = :phone";

        // Parameters array
        $params = array();
        $params['full_name'] = $data['full_name'];
        $params['phone'] = $data['phone'];
        $params['user_id'] = $userId;

        // If profile image exists
        if (isset($data['profile_image'])) {

            // Add image update to query
            $query = $query . ", profile_image = :profile_image";

            // Bind image parameter
            $params['profile_image'] = $data['profile_image'];
        }

        // Complete query with WHERE condition
        $query = $query . " WHERE user_id = :user_id";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Execute update
        $result = $stmt->execute($params);

        // Return result
        return $result;
    }

    // Get all users list
    public function getAllUsers()
    {
        // SQL query for all users
        $query = "SELECT user_id, username, email, full_name, phone, date_joined as created_at, badge_status, is_banned 
                  FROM users 
                  ORDER BY date_joined DESC";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Execute query
        $stmt->execute();

        // Fetch all users
        $users = $stmt->fetchAll();

        // Return users list
        return $users;
    }

    // Update user badge
    public function updateBadge($userId, $badge)
    {
        try {
            // SQL update query for badge
            $query = "UPDATE users SET badge_status = :badge WHERE user_id = :id";

            // Prepare statement
            $stmt = $this->db->prepare($query);

            // Bind parameters
            $params = array();
            $params['badge'] = $badge;
            $params['id'] = $userId;

            // Execute update
            $result = $stmt->execute($params);

            // Return success
            return $result;

        } catch (\PDOException $e) {

            // Log error if column missing or failure
            error_log("Failed to update badge. Missing column? " . $e->getMessage());

            // Return failure
            return false;
        }
    }

    // Ban or unban user
    public function toggleBan($userId, $isBanned)
    {
        // SQL query for ban update
        $query = "UPDATE users SET is_banned = :ban WHERE user_id = :id";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Bind values
        $params = array();
        $params['ban'] = $isBanned;
        $params['id'] = $userId;

        // Execute update
        $result = $stmt->execute($params);

        // Return result
        return $result;
    }
}