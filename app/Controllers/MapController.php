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

    public function index()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT r.report_id, r.title, r.type, r.latitude, r.longitude, r.image_path, r.date_posted, r.location, c.name as category_name FROM reports r LEFT JOIN categories c ON r.category_id = c.category_id WHERE r.status = 'open' ORDER BY r.date_posted DESC");
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Global Map - Lost and Found',
            'items' => $items,
            'css' => ['map']
        ];

        $this->view('map', $data);
    }
}