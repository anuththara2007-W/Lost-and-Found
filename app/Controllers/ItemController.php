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
        // all item listings use the search/filter system
        $this->search();
    }


    public function search()
    {
        // Get search inputs from URL
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        
        $items = $this->itemModel->searchItems($keyword, $type, $category_id, $location, $date);
        $categories = $this->itemModel->getCategories();

        // Prepare data to send to the frontend page.
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
        
        //Load the view and pass data to display
        $this->view('items/index', $data);
    }


    // Show details for a single item
    public function show($id)   //$id comes from URL
    {
        //Get single item detail from DB
        $item = $this->itemModel->getReportById($id);

        //If item not found, show error
        if (!$item) {
            $_SESSION['flash_error'] = 'Item not found.';
            redirect('/item/index');
        }

        // Fetch comments
        $messageModel = $this->model('Message');
        $comments = $messageModel->getCommentsByReport($id);

       // Get database connection 
$db = \App\Core\Database::getInstance()->getConnection();

// Get all images related to this report
$imgStmt = $db->prepare("SELECT image_path FROM report_images WHERE report_id = :id ORDER BY created_at ASC");

// Execute query safely
$imgStmt->execute(['id' => $id]);

// Fetch all images
$images = $imgStmt->fetchAll(\PDO::FETCH_COLUMN);

// If no extra images, use main image as fallback   
if (empty($images) && !empty($item['image_path'])) {
    $images = [$item['image_path']]; // Wrap single image into array
}

// Decide opposite type for matching
$oppositeType = $item['type'] === 'lost' ? 'found' : 'lost';

// Get database connection again for static class access
$db = \App\Core\Database::getInstance()->getConnection();

// to find simiar items
$stmt = $db->prepare("
    SELECT * FROM reports 
    WHERE type = :type 
    AND category_id = :cat 
    AND status = 'open' 
    AND report_id != :id 
    ORDER BY date_posted DESC 
    LIMIT 3
");

// Execute query with safe parameters
$stmt->execute([
    'type' => $oppositeType,    // match opposite type (lost/found)
    'cat' => $item['category_id'],   // same category
    'id' => $id     // avoid current item
]);

// Get all matching items from the query result
$potentialMatches = $stmt->fetchAll();

        // Prepare data to send to the view page
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
        //user must logged-in
        requireLogin();

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);   //clean input data
            $_SESSION['old'] = $_POST;      //store old data

            // Handle multiple file uploads
            $imagePath = null;
            $uploadedImages = [];

            //check if images are uploaded
            if (isset($_FILES['images']) && isset($_FILES['images']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'jfif', 'avif', 'heic', 'heif', 'tif', 'tiff'];
                $uploadDir = ROOT . '/public/uploads/';

                // Check if the uploads directory does NOT exist
                    if (!is_dir($uploadDir)) {

                // Create the uploads directory with full permissions
                mkdir($uploadDir, 0777, true);
}                   
                //gets file data
                $fileNames = $_FILES['images']['name'];
                $fileTmps = $_FILES['images']['tmp_name'];
                $fileErrors = $_FILES['images']['error'];

                if (!is_array($fileNames)) {
                    $fileNames = [$fileNames];
                    $fileTmps = [$fileTmps];
                    $fileErrors = [$fileErrors];
                }

                //loop through each file
                foreach ($fileNames as $key => $fileName) {
                    if (!isset($fileErrors[$key]) || $fileErrors[$key] !== 0) {
                        continue;
                    }

                    $fileTmp = $fileTmps[$key];
                    //get file extention
                    $fileExt = strtolower(pathinfo((string)$fileName, PATHINFO_EXTENSION));

                    if (!in_array($fileExt, $allowed, true)) {
                        continue;
                    }

                    // Create unique file name & Move file to uploads folder
                    $newFileName = uniqid('img_', true) . '_' . $key . '.' . $fileExt;
                    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                        $uploadedImages[] = $newFileName;
                        
                        // first image is main image
                        if ($imagePath === null) {
                            $imagePath = $newFileName;
                        }
                    }
                }
            }

            //prepare data
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

            //validation
            if (empty($data['title']) || empty($data['description']) || empty($data['location'])) {
                $_SESSION['flash_error'] = 'Please fill out all required fields.';
                redirect('/item/create?type=' . $data['type']);
            }

            //save to DB
            if ($this->itemModel->addReport($data)) {
                clearOld();
                $_SESSION['flash_success'] = 'Report successfully submitted.';
                redirect('/home/index');
            } else {
                $_SESSION['flash_error'] = 'Something went wrong. Please try again.';
                redirect('/item/create?type=' . $data['type']);
            }

        } else {
            // If not submitted, just show form
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

    public function resolve($id)
    {
        //user must logged-in
        requireLogin();

        //allow only post request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/user/dashboard');
        }

        // Try to mark the report as resolved in database
        if ($this->itemModel->markResolved($id, $_SESSION['user_id'])) {

            // Success message
            $_SESSION['flash_success'] = 'Report marked as resolved.';
        } else {

            // Error message
            $_SESSION['flash_error'] = 'Unable to mark report as resolved.';
        }

        //redirect back to dashboard
        redirect('/user/dashboard');
    }
}
