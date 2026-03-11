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

}