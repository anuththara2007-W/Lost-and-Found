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

}