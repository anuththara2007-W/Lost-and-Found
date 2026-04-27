<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * MessageController
 * ------------------
 * Handles all messaging and chat-related features in the system.
 * - Viewing conversations
 * - Opening chat for a report
 * - Sending messages
 * - Real-time chat 
 */
class MessageController extends Controller
{
    private $messageModel;
    private $itemModel;

    /**
     * Constructor
     * Loads required models
     */
    public function __construct()
    {
        $this->messageModel = $this->model('Message'); // Handles message DB operations
        $this->itemModel = $this->model('Item');       // Handles report/item data
    }

    /**
     * Show all conversations of the logged-in user
     */
    public function index()
    {
        requireLogin(); // Ensure user is authenticated

        // Get all conversations for the current user
        $conversations = $this->messageModel->getConversationsForUser($_SESSION['user_id']);
        
        // Pass data to the view
        $data = [
            'title' => 'My Messages - Lost and Found',
            'conversations' => $conversations
        ];

        $this->view('messages/index', $data);
    }

    /**
     * Open chat for a specific report
     */
    public function chat($report_id = null)
    {
        requireLogin();

        // If no report ID provided, redirect
        if (!$report_id) {
            redirect('/message/index');
        }
        
        // Fetch report details
        $item = $this->itemModel->getReportById($report_id);

        // If report not found, redirect with error
        if (!$item) {
            $_SESSION['flash_error'] = 'Chat not found.';
            redirect('/message/index');
        }

        // Get all messages and conversations
        $comments = $this->messageModel->getCommentsByReport($report_id);
        $conversations = $this->messageModel->getConversationsForUser($_SESSION['user_id']);
        
        // Send data to view
        $data = [
            'title' => 'Direct Chat', 
            'item' => $item, 
            'comments' => $comments,
            'conversations' => $conversations
        ];

        $this->view('messages/chat', $data);
    }

    /**
     * Store a new message (normal form submission)
     */
    public function store()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get form inputs safely
            $report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);
            $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
            $comment_text = trim($_POST['comment_text'] ?? '');
            $attachmentPath = null;
            $user_id = $_SESSION['user_id'];

            // Validate report existence
            $item = $this->itemModel->getReportById($report_id);
            if (!$item) {
                $_SESSION['flash_error'] = 'Invalid report.';
                redirect('/home/index');
            }
            
            // Ensure message is not empty
            if (!empty($comment_text) || !empty($attachmentPath)) {

                // Save message
                if ($this->messageModel->addCommentWithAttachment($report_id, $user_id, $comment_text, $attachmentPath, $parent_id)) {
                    $_SESSION['flash_success'] = 'Message sent.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to send message.';
                }

            } else {
                $_SESSION['flash_error'] = 'Cannot send an empty message.';
            }

            // Redirect based on context
            if(isset($_POST['redirect_context']) && $_POST['redirect_context'] === 'item_show') {
                redirect('/item/show/' . $report_id);
            } else {
                redirect('/message/chat/' . $report_id);
            }
        }
    }

    /**
     * API: Get messages (used for real-time chat via AJAX)
     */
    public function apiGetMessages($report_id = null)
    {
        header('Content-Type: application/json');
        
        // Check login
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Please log in']);
            exit;
        }
        
        try {
            $my_id = $_SESSION['user_id'];

            // Update user activity (for online status)
            $this->messageModel->updateUserActivity($my_id);

            if (!$report_id) {
                echo json_encode(['error' => 'Missing report ID']);
                exit;
            }
            
            // Validate report
            $item = $this->itemModel->getReportById($report_id);
            if (!$item) {
                echo json_encode(['error' => 'Report not found']);
                exit;
            }
            
            // Fetch messages
            $comments = $this->messageModel->getCommentsByReport($report_id);
            
            // Format timestamps
            foreach($comments as &$c) {
                $c['formatted_date'] = date('M j, Y, g:i a', strtotime($c['created_at']));
            }
            
            // Typing status
            $typingStatus = $this->messageModel->getTypingStatus($report_id, $my_id);
            
            // Check if other user is online
            $otherUserId = ($item['user_id'] == $my_id) ? null : $item['user_id'];
            $isOnline = $otherUserId ? $this->messageModel->isUserOnline($otherUserId) : false;
            
            echo json_encode([
                'messages' => $comments,
                'typing' => $typingStatus,
                'partner_online' => $isOnline
            ]);
            exit;

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
            exit;
        }
    }

    /**
     * API: Update typing status
     */
    public function apiSetTyping()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not logged in']);
            exit;
        }
        
        $report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);
        $is_typing = isset($_POST['is_typing']) ? (int)$_POST['is_typing'] : 0;

        try {
            $this->messageModel->setTyping($report_id, $_SESSION['user_id'], $is_typing);
            echo json_encode(['success' => true]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Typing failed']);
        }

        exit;
    }

    /**
     * API: Send message 
     */
    public function apiSendMessage()
    {
        header('Content-Type: application/json');
        //check login
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Not logged in']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);
                $comment_text = trim($_POST['comment_text'] ?? '');
                $user_id = $_SESSION['user_id'];

                // Validation
                if (!$report_id) {
                    echo json_encode(['error' => 'Missing report ID']);
                    exit;
                }

                if (empty($comment_text)) {
                    echo json_encode(['error' => 'Empty message']);
                    exit;
                }

                // Save message
                if ($this->messageModel->addCommentWithAttachment($report_id, $user_id, $comment_text, null, 0)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Database error']);
                }

                exit;

            } catch (\Throwable $e) {
                echo json_encode(['error' => 'Message failed']);
                exit;
            }
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;   
    }
}