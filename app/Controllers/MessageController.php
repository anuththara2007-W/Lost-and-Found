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
}