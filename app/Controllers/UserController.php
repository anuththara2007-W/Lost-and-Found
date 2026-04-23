<?php
namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller
{
    private $userModel;
    private $itemModel;

    public function __construct()
    {
        requireLogin();
        $this->userModel = $this->model('User');
        $this->itemModel = $this->model('Item');
    }

    public function dashboard()
    {
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            redirect('/admin/dashboard');
        }

        $userId = $_SESSION['user_id'];
        
        // Fetch user data
        $user = $this->userModel->findById($userId);
        
        // Fetch user's reports
        $myReports = $this->itemModel->getReportsByUser($userId);

        $data = [
            'title' => 'My Dashboard - Lost and Found',
            'user' => $user,
            'reports' => $myReports
        ];

        $this->view('dashboard', $data);
    }

    public function profile()
    {
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            redirect('/admin/dashboard');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $data = [
            'title' => 'My Profile - Lost and Found',
            'user' => $user
        ];
        $this->view('user/profile', $data);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $user = $this->userModel->findById($userId);
            
            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']) // Used for WhatsApp injection
            ];

            // Handle Profile Image Upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $fileName = $_FILES['profile_image']['name'];
                $fileTmp = $_FILES['profile_image']['tmp_name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExt, $allowed)) {
                    $newFileName = uniqid('avatar_') . '.' . $fileExt;
                    $uploadDir = ROOT . '/public/uploads/avatars/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                        $data['profile_image'] = $newFileName;
                        
                        // Delete old avatar if present
                        if (!empty($user['profile_image']) && file_exists($uploadDir . $user['profile_image'])) {
                            unlink($uploadDir . $user['profile_image']);
                        }
                    }
                } else {
                    $_SESSION['flash_error'] = 'Invalid image format.';
                    redirect('/user/profile');
                }
            }

            if ($this->userModel->updateProfile($userId, $data)) {
                $_SESSION['flash_success'] = 'Profile updated successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to update profile.';
            }
            redirect('/user/profile');
        }
    }
}