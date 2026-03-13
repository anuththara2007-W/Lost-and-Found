<?php
namespace App\Controllers;
use App\Core\Controller;
/*
|--------------------------------------------------------------------------
| MessageController
|--------------------------------------------------------------------------
| This controller handles all messaging features of the system.
| It manages conversation lists, chat pages, message sending,
| attachment uploads, and real-time API endpoints for the chat system.
*/
class MessageController extends Controller
{
    private $messageModel;
    private $itemModel;
    //  Loads the required models when the controller is initialized.
    public function __construct()
    {
        $this->messageModel = $this->model('Message');
        $this->itemModel = $this->model('Item');
    }

    public function index()// Displays the main message inbox page.
    {
        requireLogin();
        $conversations = $this->messageModel->getConversationsForUser($_SESSION['user_id']);

        $data = [
            'title' => 'My Messages - Lost and Found',
            'conversations' => $conversations
        ];
        $this->view('messages/index', $data);
    }

      public function chat($report_id = null)// Displays the chat page for a specific report, showing the conversation and comments.
    {
        requireLogin();
        if (!$report_id) {
            redirect('/message/index');
        }

        $item = $this->itemModel->getReportById($report_id);
        if (!$item) {
            $_SESSION['flash_error'] = 'Chat not found.';
            redirect('/message/index');
        }

        $comments = $this->messageModel->getCommentsByReport($report_id);
        $conversations = $this->messageModel->getConversationsForUser($_SESSION['user_id']);

        $data = [
            'title' => 'Direct Chat',
            'item' => $item,
            'comments' => $comments,
            'conversations' => $conversations
        ];
        $this->view('messages/chat', $data);
    }

     public function store()//Handles sending a message from the normal form submission.
     {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);
            $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
            $comment_text = trim($_POST['comment_text'] ?? '');
            $attachmentPath = null;
            $user_id = $_SESSION['user_id'];

            // Verify item exists
            $item = $this->itemModel->getReportById($report_id);
            if (!$item) {
                $_SESSION['flash_error'] = 'Invalid report.';
                redirect('/home/index');
            }

            // Check for attachment upload
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $fileName = $_FILES['attachment']['name'];
                $fileTmp = $_FILES['attachment']['tmp_name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExt, $allowed)) {
                    $newFileName = uniqid('chat_') . '.' . $fileExt;
                    $uploadDir = ROOT . '/public/uploads/chat/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                        $attachmentPath = $newFileName;
                    }
                }
            }

            if (!empty($comment_text) || !empty($attachmentPath)) {  // Ensure message is not empty
                // Add via model with new attachment logic
                if ($this->messageModel->addCommentWithAttachment($report_id, $user_id, $comment_text, $attachmentPath, $parent_id)) {
                    $_SESSION['flash_success'] = 'Message sent.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to send message.';
                }
            } else {
                $_SESSION['flash_error'] = 'Cannot send an empty message.';
            }

            // Redirect context check
            if (isset($_POST['redirect_context']) && $_POST['redirect_context'] === 'item_show') {
                redirect('/item/show/' . $report_id);
            } else {
                redirect('/message/chat/' . $report_id);
            }
        }
    }
      // --- API Endpoints for Real-time Chat ---
    public function apiGetMessages($report_id = null)
    {
        // Ensure JSON header is always set FIRST
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Please log in to view messages']);
            exit;
        }

        try {
            $my_id = $_SESSION['user_id'];
            $this->messageModel->updateUserActivity($my_id);

            if (!$report_id) {
                echo json_encode(['error' => 'Missing report ID']);
                exit;
            }

            // Verify the report exists
            $item = $this->itemModel->getReportById($report_id);
            if (!$item) {
                echo json_encode(['error' => 'Report not found']);
                exit;
            }

            $comments = $this->messageModel->getCommentsByReport($report_id);

            // Format dates before sending JSON
            foreach ($comments as &$c) {
                $c['formatted_date'] = date('M j, Y, g:i a', strtotime($c['created_at']));
            }

            $typingStatus = $this->messageModel->getTypingStatus($report_id, $my_id);

            $otherUserId = ($item['user_id'] == $my_id) ? null : $item['user_id'];

            $isOnline = false;
            if ($otherUserId) {
                $isOnline = $this->messageModel->isUserOnline($otherUserId);
            }

            echo json_encode(['messages' => $comments, 'typing' => $typingStatus, 'partner_online' => $isOnline]);
            exit;
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function apiSetTyping()//API endpoint that records when a user is typing.
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not logged in']);
            exit;
        }

        $report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);
        $is_typing = isset($_POST['is_typing']) ? (int)$_POST['is_typing'] : 0;
        $this->messageModel->setTyping($report_id, $_SESSION['user_id'], $is_typing);
        echo json_encode(['success' => true]);
        exit;
    }
    public function apiSendMessage()//AJAX API endpoint used to send messages without reloading the page.
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Not logged in']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);
            $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
            $comment_text = trim($_POST['comment_text'] ?? '');
            $user_id = $_SESSION['user_id'];
            $attachmentPath = null;

            if (empty($comment_text) && empty($_FILES['attachment']['name'])) {
                echo json_encode(['error' => 'Empty message']);
                exit;
            }

            // Handle optional attachment
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $fileName = $_FILES['attachment']['name'];
                $fileTmp = $_FILES['attachment']['tmp_name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExt, $allowed)) {
                    $newFileName = uniqid('chat_') . '.' . $fileExt;
                    $uploadDir = ROOT . '/public/uploads/chat/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                        $attachmentPath = $newFileName;
                    }
                }
            }

            if ($this->messageModel->addCommentWithAttachment($report_id, $user_id, $comment_text, $attachmentPath, $parent_id)) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['error' => 'Database error']);
                exit;
            }
        }
    }
}