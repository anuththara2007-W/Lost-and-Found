<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    private $itemModel;

    public function __construct()
    {
        // Try not to fail if the Item model isn't built yet
        if(file_exists(ROOT . '/app/Models/Item.php')) {
            $this->itemModel = $this->model('Item');
        }
    }

    public function index()
    {
        // Get recent items
        $recentItems = [];
        if($this->itemModel) {
            $recentItems = $this->itemModel->getRecentReports(6);
        }

        $data = [
            'title' => 'Lost & Found - Home',
            'recentItems' => $recentItems
        ];

        $this->view('home', $data);
    }

    public function success_stories()
    {
        $resolvedItems = [];
        if($this->itemModel) {
            $resolvedItems = $this->itemModel->getResolvedReports(20);
        }

        $data = [
            'title' => 'Success Stories & Rewards - Lost and Found',
            'resolvedItems' => $resolvedItems,
            'css' => ['success']
        ];

        $this->view('success_stories', $data);
    }
}
