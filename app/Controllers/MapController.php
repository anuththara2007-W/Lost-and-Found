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
        // Get ALL open items coordinates in the map JS

        //r is an alias for the reports table.
        $stmt = $db->query("SELECT r.report_id, r.title, r.type, r.latitude, r.longitude, r.image_path, r.date_posted, r.location, c.name as category_name FROM reports r LEFT JOIN categories c ON r.category_id = c.category_id WHERE r.status = 'open' ORDER BY r.date_posted DESC");
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Global Map - Lost and Found',
            'items' => $items,
            'css' => ['map']
        ];

        $this->view('map', $data);
    }

    public function api_markers()
    {
        //JSON is used to convert PHP data into a format that JavaScript can easily read and use in frontend applications.

        header('Content-Type: application/json');
        
        // Get a database connection instance using Singleton pattern (:: for static method access)
// This ensures only one shared DB connection is used throughout the application
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT r.report_id, r.title, r.type, r.latitude, r.longitude, r.image_path, r.date_posted, r.location, c.name as category_name
            FROM reports r
            LEFT JOIN categories c ON r.category_id = c.category_id
            WHERE r.status = 'open'
            ORDER BY r.date_posted DESC
        ");
        // Send database results as JSON response for frontend
        echo json_encode(['items' => $stmt->fetchAll(\PDO::FETCH_ASSOC)]);
        //$stmt now holds the executed query results, which are fetched as an associative array and encoded to JSON format for the API response.
        exit;
    }
}
