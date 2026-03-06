<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Database;
use App\Core\Session;
use App\Core\Mailer;

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ─────────────────────────────────────────────
    // REGISTER
    // ─────────────────────────────────────────────

    public function showRegister(): void
    {
        if (Session::isLoggedIn()) {
            header('Location: /');
            exit;
        }
        require_once ROOT . '/resources/views/auth/register.php';
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $errors = [];

        $username  = trim($_POST['username']  ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email']     ?? '');
        $phone     = trim($_POST['phone']     ?? '');
        $password  = $_POST['password']       ?? '';
        $confirm   = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($username) || strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = 'Username must be between 3 and 50 characters.';
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Username may only contain letters, numbers, and underscores.';
        }
        if (empty($full_name)) {
            $errors[] = 'Full name is required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        // Check uniqueness
        if (empty($errors)) {
            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'That email address is already registered.';
            }
            if ($this->userModel->findByUsername($username)) {
                $errors[] = 'That username is already taken.';
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $_POST);
            header('Location: /register');
            exit;
        }

        // Handle profile image upload
        $profile_image = null;
        if (!empty($_FILES['profile_image']['name'])) {
            $profile_image = $this->handleImageUpload($_FILES['profile_image']);
        }

        $data = [
            'username'      => $username,
            'full_name'     => $full_name,
            'email'         => $email,
            'phone'         => $phone ?: null,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'profile_image' => $profile_image,
        ];

        $user_id = $this->userModel->create($data);

        if (!$user_id) {
            Session::flash('errors', ['Registration failed. Please try again.']);
            header('Location: /register');
            exit;
        }

        // Auto-login after registration
        $user = $this->userModel->findById($user_id);
        Session::login($user);

        Session::flash('success', 'Welcome to Lost & Found, ' . htmlspecialchars($full_name) . '!');
        header('Location: /');
        exit;
    }

    // ─────────────────────────────────────────────
    // LOGIN
    // ─────────────────────────────────────────────

    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            header('Location: /');
            exit;
        }
        require_once ROOT . '/resources/views/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password   = $_POST['password'] ?? '';
        $remember   = isset($_POST['remember']);

        if (empty($identifier) || empty($password)) {
            Session::flash('errors', ['Please enter your email/username and password.']);
            Session::flash('old', ['identifier' => $identifier]);
            header('Location: /login');
            exit;
        }

        // Find by email or username
        $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? $this->userModel->findByEmail($identifier)
            : $this->userModel->findByUsername($identifier);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Session::flash('errors', ['Invalid credentials. Please try again.']);
            Session::flash('old', ['identifier' => $identifier]);
            header('Location: /login');
            exit;
        }

        Session::login($user);

        if ($remember) {
            $this->setRememberMeCookie($user['user_id']);
        }

        $redirect = Session::get('intended_url') ?? '/';
        Session::forget('intended_url');

        header('Location: ' . $redirect);
        exit;
    }

    // ─────────────────────────────────────────────
    // LOGOUT
    // ─────────────────────────────────────────────

    public function logout(): void
    {
        $this->clearRememberMeCookie();
        Session::logout();
        header('Location: /login');
        exit;
    }

    // ─────────────────────────────────────────────
    // FORGOT PASSWORD
    // ─────────────────────────────────────────────

    public function showForgot(): void
    {
        if (Session::isLoggedIn()) {
            header('Location: /');
            exit;
        }
        require_once ROOT . '/resources/views/auth/forgot.php';
    }

    public function forgot(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /forgot-password');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('errors', ['Please enter a valid email address.']);
            header('Location: /forgot-password');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        // Always show success to prevent email enumeration
        Session::flash('success', 'If that email is registered, you will receive a reset link shortly.');

        if ($user) {
            $token     = bin2hex(random_bytes(32));
            $expires   = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $this->userModel->storeResetToken($user['user_id'], $token, $expires);

            $resetLink = rtrim(BASE_URL, '/') . '/reset-password?token=' . $token;
            Mailer::sendPasswordReset($user['email'], $user['full_name'], $resetLink);
        }

        header('Location: /forgot-password');
        exit;
    }

    // ─────────────────────────────────────────────
    // RESET PASSWORD
    // ─────────────────────────────────────────────

    public function showReset(): void
    {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            header('Location: /forgot-password');
            exit;
        }

        $record = $this->userModel->findByResetToken($token);
        if (!$record || strtotime($record['reset_expires']) < time()) {
            Session::flash('errors', ['This reset link is invalid or has expired.']);
            header('Location: /forgot-password');
            exit;
        }

        require_once ROOT . '/resources/views/auth/reset.php';
    }

    public function reset(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /forgot-password');
            exit;
        }

        $token    = $_POST['token']            ?? '';
        $password = $_POST['password']         ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        $record = $this->userModel->findByResetToken($token);
        if (!$record || strtotime($record['reset_expires']) < time()) {
            Session::flash('errors', ['This reset link is invalid or has expired.']);
            header('Location: /forgot-password');
            exit;
        }

        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userModel->updatePassword($record['user_id'], $hashed);
        $this->userModel->clearResetToken($record['user_id']);

        Session::flash('success', 'Your password has been reset. Please log in.');
        header('Location: /login');
        exit;
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    private function handleImageUpload(array $file): ?string
    {
        $allowed    = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize    = 2 * 1024 * 1024; // 2 MB
        $uploadDir  = ROOT . '/public/uploads/profiles/';

        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        if (!in_array($file['type'], $allowed))  return null;
        if ($file['size'] > $maxSize)             return null;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
        $dest     = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;

        return '/public/uploads/profiles/' . $filename;
    }

    private function setRememberMeCookie(int $userId): void
    {
        $token   = bin2hex(random_bytes(32));
        $expires = time() + (30 * 24 * 3600); // 30 days

        $this->userModel->storeRememberToken($userId, hash('sha256', $token), $expires);

        setcookie('remember_token', $token, [
            'expires'  => $expires,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function clearRememberMeCookie(): void
    {
        if (isset($_COOKIE['remember_token'])) {
            $userId = Session::get('user_id');
            if ($userId) {
                $this->userModel->clearRememberToken($userId);
            }
            setcookie('remember_token', '', time() - 3600, '/');
        }
    }
}