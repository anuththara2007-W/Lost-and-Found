<?php
namespace App\Controllers;

use App\Core\Controller;

class MapController extends Controller
{
    // This will store the Item model
    private $itemModel;

    public function __construct()
    {
        // Load Item model so we can use it if needed
        $this->itemModel = $this->model('Item');
    }

    // This function loads the main map page
    public function index()
    {
        // Get database connection
        $databaseObject = \App\Core\Database::getInstance();
        $connection = $databaseObject->getConnection();

        // SQL query to get all open reports
        // "open" means active items (not closed)
        $query = "
            SELECT 
                r.report_id,
                r.title,
                r.type,
                r.latitude,
                r.longitude,
                r.image_path,
                r.date_posted,
                r.location,
                c.name as category_name
            FROM reports r
            LEFT JOIN categories c 
                ON r.category_id = c.category_id
            WHERE r.status = 'open'
            ORDER BY r.date_posted DESC
        ";

        // Prepare the query
        $stmt = $connection->query($query);

        // Fetch all results as associative array
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Prepare data to send to view
        $data = [
            'title' => 'Global Map - Lost and Found', // page title
            'items' => $items, // all reports
            'css' => ['map'] // load map.css file
        ];

        // Load the map view and send data
        $this->view('map', $data);
    }

    // This function is used as an API (returns JSON data)
    public function api_markers()
    {
        // Tell browser this is JSON response
        header('Content-Type: application/json');

        // Get database connection
        $databaseObject = \App\Core\Database::getInstance();
        $connection = $databaseObject->getConnection();

        // SQL query (same as above)
        $query = "
            SELECT 
                r.report_id,
                r.title,
                r.type,
                r.latitude,
                r.longitude,
                r.image_path,
                r.date_posted,
                r.location,
                c.name as category_name
            FROM reports r
            LEFT JOIN categories c 
                ON r.category_id = c.category_id
            WHERE r.status = 'open'
            ORDER BY r.date_posted DESC
        ";

        // Run query
        $stmt = $connection->query($query);

        // Get data
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Convert PHP array to JSON and output it
        echo json_encode([
            'items' => $items
        ]);

        // Stop script execution
        exit;
    }
}