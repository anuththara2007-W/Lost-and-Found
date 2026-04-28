<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    private $itemModel;

    public function __construct()
    {
        // Load Item model if file exists (skip if not)
        if(file_exists(ROOT . '/app/Models/Item.php')) {
            $this->itemModel = $this->model('Item');
        }
    }

    public function index() //pol
    {
        // store recent items
        $recentItems = [];

        //if item model is loaded get 6 recent reports from DB
        if($this->itemModel) {
            $recentItems = $this->itemModel->getRecentReports(6);
        }

        // Data is send to the home
        $data = [
            'title' => 'Lost & Found - Home',   //page title
            'recentItems' => $recentItems   
        ];

        $this->view('home', $data);     //load home view with data
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
