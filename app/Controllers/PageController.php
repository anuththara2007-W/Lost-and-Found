<?php
namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    public function about()
    {
        $data = ['title' => 'About Us - Lost and Found'];
        $this->view('pages/about', $data);
    }

    public function faq()
    {
        $data = ['title' => 'FAQ - Lost and Found'];
        $this->view('pages/faq', $data);
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactModel = $this->model('ContactRequest');
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if ($name === '' || $email === '' || $message === '') {
                $_SESSION['flash_error'] = 'Please fill in all fields.';
                redirect('/page/contact');
            }

            if ($contactModel->create([
                'name' => $name,
                'email' => $email,
                'message' => $message
            ])) {
                $_SESSION['flash_success'] = 'Your request has been sent to admin.';
            } else {
                $_SESSION['flash_error'] = 'Failed to send request.';
            }
            redirect('/page/contact');
        }

        $data = ['title' => 'Contact Us - Lost and Found'];
        $this->view('pages/contact', $data);
    }

    public function set_language($lang = 'en')
    {
        $allowed = ['en', 'si', 'ta'];
        $_SESSION['lang'] = in_array($lang, $allowed, true) ? $lang : 'en';
        $back = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $back);
        exit;
    }

    public function terms()
    {
        $data = ['title' => 'Terms of Service - Lost and Found'];
        $this->view('pages/terms', $data);
    }

    public function privacy()
    {
        $data = ['title' => 'Privacy Policy - Lost and Found'];
        $this->view('pages/privacy', $data);
    }
}
