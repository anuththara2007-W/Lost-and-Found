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

}