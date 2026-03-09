<?php
namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller
{
     private $userModel;
    private $itemModel;

 //initialize UserController with User and Item models and enforce login
    public function __construct()
    {
        requireLogin();
        $this->userModel = $this->model('User');
        $this->itemModel = $this->model('Item');
    }

    //feat: add dashboard method to display user reports and account info
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
   //feat: implement profile view for logged-in users
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

}