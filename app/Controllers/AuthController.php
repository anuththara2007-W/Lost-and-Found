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

    

    public function register()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form takes all form input ($_POST) and sanitizes it
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);


            //It stores the submitted form data into session.
            $_SESSION['old'] = $_POST;

            $data = [
                'username' => trim($_POST['username']),
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
            ];

            // Validate Email
            if (empty($data['email'])) {
                $_SESSION['flash_error'] = 'Please enter email';
                redirect('/auth/register');
            } else {
                if ($this->userModel->findByEmail($data['email'])) {
                    $_SESSION['flash_error'] = 'Email is already taken';
                    redirect('/auth/register');
                }
            }

            // Validate Username
            if (empty($data['username'])) {
                $_SESSION['flash_error'] = 'Please enter username';
                redirect('/auth/register');
            } else {
                if ($this->userModel->findByUsername($data['username'])) {
                    $_SESSION['flash_error'] = 'Username is already taken';
                    redirect('/auth/register');
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $_SESSION['flash_error'] = 'Please enter password';
                redirect('/auth/register');
            } elseif (strlen($data['password']) < 6) {
                $_SESSION['flash_error'] = 'Password must be at least 6 characters';
                redirect('/auth/register');
            } elseif ($data['password'] != $data['confirm_password']) {
                $_SESSION['flash_error'] = 'Passwords do not match';
                redirect('/auth/register');
            }

            // Hash Password
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Register User
            if ($this->userModel->register($data)) {
                clearOld();
                $_SESSION['flash_success'] = 'You are registered and can log in';
                redirect('/auth/login');
            } else {
                die('Something went wrong');
            }
        } else {
            // Init data
            $data = [
                'title' => 'Register - Lost and Found'
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }
    
    public function forgot()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            // Simulate sending reset link
            $_SESSION['flash_success'] = 'If an account exists for ' . escape($email) . ', a reset link was sent.';
            redirect('/auth/login');
        } else {
            $data = ['title' => 'Forgot Password'];
            $this->view('auth/forgot', $data);
        }
    }


    
    public function reset()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = trim($_POST['password']);
            $_SESSION['flash_success'] = 'Password successfully reset. Please login with your new password.';
            redirect('/auth/login');
        } else {
            $data = ['title' => 'Reset Password'];
            $this->view('auth/reset', $data);
        }
    }


    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['user_email']);
        session_destroy();
        redirect('/auth/login');
    }
//completed
}