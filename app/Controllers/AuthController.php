<?php
namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{

 private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }
    
    public function login()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            
            $_SESSION['old'] = $_POST;

            if (empty($email) || empty($password)) {
                $_SESSION['flash_error'] = 'Please fill in all fields';
                redirect('/auth/login');
            }

            // Hardcoded Admin Check
            if ($email === 'admin@gmail.com' && $password === '1234567890') {
                $_SESSION['user_id'] = 'admin';
                $_SESSION['username'] = 'Administrator';
                $_SESSION['user_email'] = 'admin@gmail.com';
                $_SESSION['is_admin'] = true;
                $_SESSION['flash_success'] = 'Welcome, Admin.';
                redirect('/admin/dashboard');
                exit;
            }

            $loggedInUser = $this->userModel->login($email, $password);

            if ($loggedInUser) {
                if (!empty($loggedInUser['is_banned'])) {
                    $_SESSION['flash_error'] = 'Your account has been banned due to policy violations.';
                    redirect('/auth/login');
                    exit;
                }
                
                // Create session
                $_SESSION['user_id'] = $loggedInUser['user_id'];
                $_SESSION['username'] = $loggedInUser['username'];
                $_SESSION['user_email'] = $loggedInUser['email'];
                $_SESSION['is_admin'] = false; // Regular user
                $_SESSION['flash_success'] = 'Welcome back, ' . htmlspecialchars($loggedInUser['username']);
                redirect('/home/index');
            } else {
                $_SESSION['flash_error'] = 'Password incorrect or user not found';
                redirect('/auth/login');
            }
        } else {
            // Init data
            $data = [
                'title' => 'Login - Lost and Found'
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }
}