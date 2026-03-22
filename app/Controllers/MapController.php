<?php
namespace App\Controllers;

use App\Core\Controller;

class MapController extends Controller
{

 private $itemModel;

    public function __construct()
    {
        $this->itemModel = $this->model('Item');
    }
}