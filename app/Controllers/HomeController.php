<?php
namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Example data (important because your view expects it)
        $recentItems = [];

        // Load the view
        require_once ROOT . '/resources/views/home.php';
    }
}