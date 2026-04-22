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

    public function create()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $_SESSION['old'] = $_POST;

            // Handle multiple file uploads
            $imagePath = null;
            $uploadedImages = [];
            if (isset($_FILES['images']) && is_array($_FILES['images']['name']) && count($_FILES['images']['name']) > 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $uploadDir = ROOT . '/public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['images']['name'] as $key => $fileName) {
                    if ($_FILES['images']['error'][$key] == 0) {
                        $fileTmp = $_FILES['images']['tmp_name'][$key];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        if (in_array($fileExt, $allowed)) {
                            $newFileName = uniqid() . '_' . $key . '.' . $fileExt;
                            if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                                $uploadedImages[] = $newFileName;
                                // Primary image is just the first uploaded one
                                if ($imagePath === null) {
                                    $imagePath = $newFileName;
                                }
                            }
                        }
                    }
                }
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'type' => $_POST['type'] ?? 'lost',
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'location' => trim($_POST['location']),
                'reward_amount' => !empty($_POST['reward_amount']) ? trim($_POST['reward_amount']) : null,
                'contact_info' => trim($_POST['contact_info'] ?? ''),
                'latitude' => !empty($_POST['latitude']) ? trim($_POST['latitude']) : null,
                'longitude' => !empty($_POST['longitude']) ? trim($_POST['longitude']) : null,
                'image_path' => $imagePath,
                'images' => $uploadedImages,
                'custom_category' => $_POST['custom_category'] ?? null,
                'whatsapp_contact' => $_POST['whatsapp_contact'] ?? null,
                'allow_platform_message' => isset($_POST['allow_platform_message']) ? 1 : 0
            ];

            if (empty($data['title']) || empty($data['description']) || empty($data['location'])) {
                $_SESSION['flash_error'] = 'Please fill out all required fields.';
                redirect('/item/create?type=' . $data['type']);
            }

            if ($this->itemModel->addReport($data)) {
                clearOld();
                $_SESSION['flash_success'] = 'Report successfully submitted.';
                redirect('/home/index');
            } else {
                $_SESSION['flash_error'] = 'Something went wrong. Please try again.';
                redirect('/item/create?type=' . $data['type']);
            }

        } else {
            $categories = $this->itemModel->getCategories();
            $type = isset($_GET['type']) && in_array($_GET['type'], ['lost', 'found']) ? $_GET['type'] : 'lost';

            $data = [
                'title' => 'Report an Item - Lost and Found',
                'categories' => $categories,
                'type' => $type
            ];

            $this->view('items/create', $data);
        }
    }
}



