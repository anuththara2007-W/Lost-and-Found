<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Session;

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->requireAuth();
    }

    // ─────────────────────────────────────────────
    // PROFILE – View
    // ─────────────────────────────────────────────

    public function profile(int $userId = 0): void
    {
        if ($userId === 0) {
            $userId = Session::get('user_id');
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            http_response_code(404);
            require_once ROOT . '/resources/views/errors/404.php';
            exit;
        }

        $isOwner = ($userId === Session::get('user_id'));
        require_once ROOT . '/resources/views/pages/profile.php';
    }

    // ─────────────────────────────────────────────
    // PROFILE – Edit
    // ─────────────────────────────────────────────

    public function showEditProfile(): void
    {
        $user = $this->userModel->findById(Session::get('user_id'));
        require_once ROOT . '/resources/views/pages/edit_profile.php';
    }

    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile/edit');
            exit;
        }

        $userId    = Session::get('user_id');
        $full_name = trim($_POST['full_name'] ?? '');
        $phone     = trim($_POST['phone']     ?? '');
        $address   = trim($_POST['address']   ?? '');
        $language  = $_POST['language']       ?? 'en';

        $errors = [];

        if (empty($full_name)) {
            $errors[] = 'Full name cannot be empty.';
        }

        $allowedLangs = ['en', 'ms', 'zh', 'ta'];
        if (!in_array($language, $allowedLangs)) {
            $language = 'en';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            header('Location: /profile/edit');
            exit;
        }

        $data = [
            'full_name' => $full_name,
            'phone'     => $phone ?: null,
            'address'   => $address ?: null,
            'language'  => $language,
        ];

        // Handle profile image upload
        if (!empty($_FILES['profile_image']['name'])) {
            $image = $this->handleImageUpload($_FILES['profile_image']);
            if ($image) {
                $data['profile_image'] = $image;
            }
        }

        $this->userModel->update($userId, $data);

        // Refresh session data
        $updated = $this->userModel->findById($userId);
        Session::refresh($updated);

        Session::flash('success', 'Profile updated successfully.');
        header('Location: /profile');
        exit;
    }

    // ─────────────────────────────────────────────
    // CHANGE PASSWORD
    // ─────────────────────────────────────────────

    public function changePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile/edit');
            exit;
        }

        $userId      = Session::get('user_id');
        $current     = $_POST['current_password']  ?? '';
        $newPass     = $_POST['new_password']       ?? '';
        $confirm     = $_POST['confirm_password']   ?? '';

        $user   = $this->userModel->findById($userId);
        $errors = [];

        if (!password_verify($current, $user['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        }
        if (strlen($newPass) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        }
        if ($newPass !== $confirm) {
            $errors[] = 'New passwords do not match.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            header('Location: /profile/edit#security');
            exit;
        }

        $hashed = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userModel->updatePassword($userId, $hashed);

        Session::flash('success', 'Password changed successfully.');
        header('Location: /profile/edit#security');
        exit;
    }

    // ─────────────────────────────────────────────
    // DELETE ACCOUNT
    // ─────────────────────────────────────────────

    public function deleteAccount(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile/edit');
            exit;
        }

        $userId   = Session::get('user_id');
        $password = $_POST['confirm_delete_password'] ?? '';
        $user     = $this->userModel->findById($userId);

        if (!password_verify($password, $user['password_hash'])) {
            Session::flash('errors', ['Incorrect password. Account not deleted.']);
            header('Location: /profile/edit#danger');
            exit;
        }

        $this->userModel->delete($userId);
        Session::logout();

        header('Location: /?account_deleted=1');
        exit;
    }

    // ─────────────────────────────────────────────
    // ADMIN – User list
    // ─────────────────────────────────────────────

    public function index(): void
    {
        $this->requireAdmin();

        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $search = trim($_GET['search'] ?? '');
        $users  = $this->userModel->paginate($limit, $offset, $search);
        $total  = $this->userModel->count($search);

        require_once ROOT . '/resources/views/admin/users.php';
    }

    public function updateTrust(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        $userId      = (int)($_POST['user_id']     ?? 0);
        $trustLevel  = (int)($_POST['trust_level'] ?? 0);
        $badgeStatus = $_POST['badge_status']       ?? 'none';

        $allowed = ['none', 'bronze', 'silver', 'gold'];
        if (!in_array($badgeStatus, $allowed)) {
            $badgeStatus = 'none';
        }

        $this->userModel->updateTrust($userId, max(0, min(100, $trustLevel)), $badgeStatus);

        Session::flash('success', 'User trust updated.');
        header('Location: /admin/users');
        exit;
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    private function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            Session::put('intended_url', $_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit;
        }
    }

    private function requireAdmin(): void
    {
        if (!Session::isAdmin()) {
            http_response_code(403);
            require_once ROOT . '/resources/views/errors/403.php';
            exit;
        }
    }

    private function handleImageUpload(array $file): ?string
    {
        $allowed   = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize   = 2 * 1024 * 1024;
        $uploadDir = ROOT . '/public/uploads/profiles/';

        if ($file['error'] !== UPLOAD_ERR_OK)   return null;
        if (!in_array($file['type'], $allowed))  return null;
        if ($file['size'] > $maxSize)            return null;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $dest     = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;

        return '/public/uploads/profiles/' . $filename;
    }
}