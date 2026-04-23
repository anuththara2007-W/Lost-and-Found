<?php
namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{

 private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }
}