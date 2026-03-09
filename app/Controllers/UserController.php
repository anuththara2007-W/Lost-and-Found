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

}