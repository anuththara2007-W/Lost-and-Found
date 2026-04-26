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
        //data package (container) that holds everything needed for a page.
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
    public function show($id)//$id is retrived by the item url like show/6 so id=6
    {
        $item = $this->itemModel->getReportById($id);

        if (!$item) {
            $_SESSION['flash_error'] = 'Item not found.';
            redirect('/item/index');
        }

        // Fetch comments
        $messageModel = $this->model('Message');
        $comments = $messageModel->getCommentsByReport($id);

       // Get database connection instance (:: used to access static singleton method)
$db = \App\Core\Database::getInstance()->getConnection();

// Prepare SQL to fetch all images for this report ID
$imgStmt = $db->prepare("SELECT image_path FROM report_images WHERE report_id = :id ORDER BY created_at ASC");

// Execute query with report ID parameter (prevents SQL injection)
$imgStmt->execute(['id' => $id]);

// Fetch all image paths as a simple array (FETCH_COLUMN = only one column values)
$images = $imgStmt->fetchAll(\PDO::FETCH_COLUMN);

// If no multiple images exist AND main report has an image, use it as fallback
if (empty($images) && !empty($item['image_path'])) {
    $images = [$item['image_path']]; // Wrap single image into array
}

// Decide opposite type (lost → found OR found → lost) for matching suggestions
$oppositeType = $item['type'] === 'lost' ? 'found' : 'lost';

// Get database connection again (:: used for static class access)
$db = \App\Core\Database::getInstance()->getConnection();

// Prepare query to find similar reports (same category + opposite type)
$stmt = $db->prepare("
    SELECT * FROM reports 
    WHERE type = :type 
    AND category_id = :cat 
    AND status = 'open' 
    AND report_id != :id 
    ORDER BY date_posted DESC 
    LIMIT 3
");

// Execute query with safe bound parameters
$stmt->execute([
    'type' => $oppositeType, // match opposite type (lost/found)
    'cat' => $item['category_id'], // same category (e.g., wallet, phone)
    'id' => $id // exclude current item
]);

// Fetch all matching reports as array of results
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
            if (isset($_FILES['images']) && isset($_FILES['images']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'jfif', 'avif', 'heic', 'heif', 'tif', 'tiff'];
               // Define the full path to the uploads directory inside the project
$uploadDir = ROOT . '/public/uploads/';

                // Check if the uploads directory does NOT exist
                    if (!is_dir($uploadDir)) {

                  // Create the uploads directory with full permissions r=4,w=2,x=1
              // 'true' allows creation of nested folders if needed
              mkdir($uploadDir, 0777, true);
}                   
                    //gets the names of uploaded files from a form input called images
                $fileNames = $_FILES['images']['name'];
                $fileTmps = $_FILES['images']['tmp_name'];
                $fileErrors = $_FILES['images']['error'];

                if (!is_array($fileNames)) {
                    $fileNames = [$fileNames];
                    $fileTmps = [$fileTmps];
                    $fileErrors = [$fileErrors];
                }

               // Loop through each uploaded file name with its index
foreach ($fileNames as $key => $fileName) {

    // Check if this file has no error OR error index is missing
    if (!isset($fileErrors[$key]) || $fileErrors[$key] !== 0) {

        // Skip this file and move to the next one in the loop
        continue;
    }
}
              // Get temporary uploaded file path from PHP server
$fileTmp = $fileTmps[$key];

// Extract file extension and convert it to lowercase (e.g. JPG → jpg)
$fileExt = strtolower(pathinfo((string)$fileName, PATHINFO_EXTENSION));

// Check if file extension is NOT allowed (security check)
if (!in_array($fileExt, $allowed, true)) {

    // Skip this file if extension is not in allowed list
    continue;
}

// Generate a unique file name to avoid overwriting existing files
$newFileName = uniqid('img_', true) . '_' . $key . '.' . $fileExt;

// Move file from temporary folder to permanent upload directory
if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {

    // Store uploaded file name in array for database saving
    $uploadedImages[] = $newFileName;

    // Set first uploaded image as primary image if not already set
    if ($imagePath === null) {
        $imagePath = $newFileName;
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

    public function resolve($id)
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/user/dashboard');
        }

        if ($this->itemModel->markResolved($id, $_SESSION['user_id'])) {
            $_SESSION['flash_success'] = 'Report marked as resolved.';
        } else {
            $_SESSION['flash_error'] = 'Unable to mark report as resolved.';
        }

        redirect('/user/dashboard');
    }
}
