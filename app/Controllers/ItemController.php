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
    
    // Show details for a single item
    public function show($id)
    {
        $item = $this->itemModel->getReportById($id);

        if (!$item) {
            $_SESSION['flash_error'] = 'Item not found.';
            redirect('/item/index');
        }

        // Fetch comments
        $messageModel = $this->model('Message');
        $comments = $messageModel->getCommentsByReport($id);

        // Fetch all images for this report
        $db = \App\Core\Database::getInstance()->getConnection();
        $imgStmt = $db->prepare("SELECT image_path FROM report_images WHERE report_id = :id ORDER BY created_at ASC");
        $imgStmt->execute(['id' => $id]);
        $images = $imgStmt->fetchAll(\PDO::FETCH_COLUMN);

        // If no images in report_images but there is an image_path in reports, use that as fallback
        if (empty($images) && !empty($item['image_path'])) {
            $images = [$item['image_path']];
        }

        // Fetch Potential Matches (same category, opposite type)
        $oppositeType = $item['type'] === 'lost' ? 'found' : 'lost';
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM reports WHERE type = :type AND category_id = :cat AND status = 'open' AND report_id != :id ORDER BY date_posted DESC LIMIT 3");
        $stmt->execute(['type' => $oppositeType, 'cat' => $item['category_id'], 'id' => $id]);
        $potentialMatches = $stmt->fetchAll();

        $data = [
            'title' => $item['title'] . ' - Lost and Found',
            'item' => $item,
            'images' => $images,
            'comments' => $comments,
            'potentialMatches' => $potentialMatches
        ];

        $this->view('items/show', $data);
    }


