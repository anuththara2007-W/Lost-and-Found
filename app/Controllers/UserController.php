<?php

namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller
{
    private $userModel;
    private $itemModel;

    public function __construct()
    {
        // check if user logged in
        requireLogin();

        // load models step by step
        $userModelObject = $this->model('User');
        $itemModelObject = $this->model('Item');

        $this->userModel = $userModelObject;
        $this->itemModel = $itemModelObject;
    }

    public function dashboard()
    {
        // check if admin
        if (isset($_SESSION['is_admin'])) {
            if ($_SESSION['is_admin'] == true) {
                redirect('/admin/dashboard');
            }
        }

        // get user id from session
        $userId = $_SESSION['user_id'];

        // get user details
        $user = $this->userModel->findById($userId);

        // get reports
        $myReports = $this->itemModel->getReportsByUser($userId);

        // prepare data array step by step
        $data = array();
        $data['title'] = 'My Dashboard - Lost and Found';
        $data['user'] = $user;
        $data['reports'] = $myReports;

        // load view
        $this->view('dashboard', $data);
    }

    public function profile()
    {
        // check admin again
        if (isset($_SESSION['is_admin'])) {
            if ($_SESSION['is_admin'] == true) {
                redirect('/admin/dashboard');
            }
        }

        $userId = $_SESSION['user_id'];

        // get user info
        $user = $this->userModel->findById($userId);

        $data = array();
        $data['title'] = 'My Profile - Lost and Found';
        $data['user'] = $user;

        $this->view('user/profile', $data);
    }

    public function updateProfile()
    {
        // check request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $userId = $_SESSION['user_id'];

            // get current user
            $user = $this->userModel->findById($userId);

            // collect form data
            $data = array();

            $data['full_name'] = trim($_POST['full_name']);
            $data['phone'] = trim($_POST['phone']);

            // image upload
            if (isset($_FILES['profile_image'])) {

                if ($_FILES['profile_image']['error'] == 0) {

                    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

                    $fileName = $_FILES['profile_image']['name'];
                    $fileTmp = $_FILES['profile_image']['tmp_name'];

                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $fileExt = strtolower($fileExt);

                    // check extension
                    if (in_array($fileExt, $allowedTypes)) {

                        $newFileName = uniqid('avatar_') . '.' . $fileExt;

                        $uploadDir = ROOT . '/public/uploads/avatars/';

                        // create folder if not exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $destination = $uploadDir . $newFileName;

                        // move file
                        $uploadSuccess = move_uploaded_file($fileTmp, $destination);

                        if ($uploadSuccess) {

                            $data['profile_image'] = $newFileName;

                            // delete old image
                            if (!empty($user['profile_image'])) {

                                $oldFile = $uploadDir . $user['profile_image'];

                                if (file_exists($oldFile)) {
                                    unlink($oldFile);
                                }
                            }
                        }

                    } else {
                        $_SESSION['flash_error'] = 'Invalid image format.';
                        redirect('/user/profile');
                    }
                }
            }

            // update profile in database
            $result = $this->userModel->updateProfile($userId, $data);

            if ($result) {
                $_SESSION['flash_success'] = 'Profile updated successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to update profile.';
            }

            redirect('/user/profile');
        }
    }
}