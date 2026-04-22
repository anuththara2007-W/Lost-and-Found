<?php
namespace App\Controllers;

use App\Core\Controller;

class ItemController extends Controller
{
    private $itemModel;

    public function __construct()
    {
        $this->itemModel = $this->model('Item');
    }

    public function index()
    {
        // Now redirects to search to utilize the full advanced filter capabilities
        $this->search();
    }

    public function search()
    {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        
        $items = $this->itemModel->searchItems($keyword, $type, $category_id, $location, $date);
        $categories = $this->itemModel->getCategories();
        
        $data = [
            'title' => 'Browse & Search - Lost and Found',
            'items' => $items,
            'categories' => $categories,
            'keyword' => $keyword,
            'type' => $type,
            'category_id' => $category_id,
            'location' => $location,
            'date' => $date
        ];
        
        $this->view('items/index', $data);
    }