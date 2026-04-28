<?php //in
namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    private $userModel;
    private $itemModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->itemModel = $this->model('Item');
    }

    // Auth Middleware for Admins
    private function requireAdminLogin()
    {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            $_SESSION['flash_error'] = 'Admin access required.';
            redirect('/auth/login');
        }
    }

    public function dashboard()
    {
        $this->requireAdminLogin();

        $users = $this->userModel->getAllUsers();
        $reports = $this->itemModel->getAllReports(); // Assumes this method exists

        $resolvedCount = 0;
        $activeCount = 0;
        foreach ($reports as $r ) {
            if ($r['status'] === 'resolved') $resolvedCount++;
            else $activeCount++;
        }
//  $data = [] = key-value container.
        $data = [
            'title' => 'Admin Dashboard - Lost and Found',
            //Put the content of $users inside the key named "users"
            'users' => $users,
            'reports' => $reports,
            'resolvedCount' => $resolvedCount,
            'activeCount' => $activeCount
        ];

        $this->view('admin/dashboard', $data);
    }

    public function users()
    {
        $this->requireAdminLogin();
        $users = $this->userModel->getAllUsers();
        $data = ['title' => 'Manage Users - Admin', 'users' => $users];
        $this->view('admin/users', $data);
    }

    public function reports()
    {
        $this->requireAdminLogin();
        $filters = [
            // q = search query  /admin/reports?q=wallet
            'q' => trim($_GET['q'] ?? ''),
            'type' => trim($_GET['type'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'user_id' => trim($_GET['user_id'] ?? '')
        ];
        $reports = $this->itemModel->getAdminReports($filters);
        $data = ['title' => 'Manage Reports - Admin', 'reports' => $reports, 'filters' => $filters];
        $this->view('admin/reports', $data);
    }

    public function items()
    {
        $this->requireAdminLogin();
        $reports = $this->itemModel->getAllReports();
        $data = ['title' => 'Manage Items - Admin', 'reports' => $reports];
        $this->view('admin/items', $data);
    }

    public function delete_report($id)
    {
        $this->requireAdminLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->itemModel->deleteReport($id)) {
                $_SESSION['flash_success'] = 'Report deleted successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete report.';
            }
        }
        redirect('/admin/reports');
    }

    public function assign_badge($user_id)
    {
        $this->requireAdminLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $badge = trim($_POST['badge_status']);
            $allowed = ['none', 'bronze', 'silver', 'gold', 'verified'];
            
            if(in_array($badge, $allowed)) {
                if ($this->userModel->updateBadge($user_id, $badge)) {
                    $_SESSION['flash_success'] = 'User badge updated successfully.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to update badge.';
                }
            } else {
                $_SESSION['flash_error'] = 'Invalid badge selected.';
            }
        }
        redirect('/admin/users');
    }

    public function toggle_ban($user_id)
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $is_banned = (int)$_POST['is_banned'];
            $this->userModel->toggleBan($user_id, $is_banned);
            $_SESSION['flash_success'] = 'User ban status updated.';
        }
        redirect('/admin/users');
    }

    /* --- System Settings & Configuration --- */
    
    public function settings()
    {
        $this->requireAdminLogin();
        
        $configModel = $this->model('SystemConfig');
        $categories = $this->itemModel->getCategories();
        $config = $configModel->getAllConfigs();

        $data = [
            'title' => 'System Settings - Admin',
            'categories' => $categories,
            'config' => $config
        ];
        $this->view('admin/settings', $data);
    }

    public function edit_report($id)
    {
        $this->requireAdminLogin();
        $report = $this->itemModel->getReportById($id);
        if (!$report) {
            $_SESSION['flash_error'] = 'Report not found.';
            redirect('/admin/reports');
        }
        $data = [
            'title' => 'Edit Report - Admin',
            'report' => $report,
            'categories' => $this->itemModel->getCategories()
        ];
        $this->view('admin/edit_report', $data);
    }

    public function update_report($id)
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/reports');
        }

        $status = trim($_POST['status'] ?? 'open');
        $type = trim($_POST['type'] ?? 'lost');
        $allowedStatus = ['open', 'resolved', 'removed'];
        $allowedType = ['lost', 'found'];
        if (!in_array($status, $allowedStatus, true) || !in_array($type, $allowedType, true)) {
            $_SESSION['flash_error'] = 'Invalid report state.';
            redirect('/admin/edit_report/' . (int)$id);
        }

        $ok = $this->itemModel->updateReportAdmin($id, [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'status' => $status,
            'type' => $type,
            'category_id' => trim($_POST['category_id'] ?? '')
        ]);

        $_SESSION['flash_' . ($ok ? 'success' : 'error')] = $ok ? 'Report updated.' : 'Failed to update report.';
        redirect('/admin/reports');
    }

    public function add_category()
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['category_name'])) {
            $name = trim($_POST['category_name']);
            // A simple query here, but usually needs a Category model approach
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
            if($stmt->execute(['name' => $name])) {
                $_SESSION['flash_success'] = 'Category added successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to add category.';
            }
        }
        redirect('/admin/settings');
    }

    public function delete_category($id)
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM categories WHERE category_id = :id");
            if($stmt->execute(['id' => $id])) {
                $_SESSION['flash_success'] = 'Category deleted successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete category.';
            }
        }
        redirect('/admin/settings');
    }

    public function update_config()
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $configModel = $this->model('SystemConfig');
            
            $site_name = trim($_POST['site_name'] ?? '');
            $admin_email = trim($_POST['admin_email'] ?? '');
            $maintenance = trim($_POST['maintenance_mode'] ?? '0');

            $configModel->updateConfig('site_name', $site_name);
            $configModel->updateConfig('admin_email', $admin_email);
            $configModel->updateConfig('maintenance_mode', $maintenance);
            $configModel->updateConfig('notify_new_report', isset($_POST['notify_new_report']) ? '1' : '0');
            $configModel->updateConfig('notify_new_message', isset($_POST['notify_new_message']) ? '1' : '0');
            $configModel->updateConfig('notify_contact_request', isset($_POST['notify_contact_request']) ? '1' : '0');

            $_SESSION['flash_success'] = 'System settings updated successfully.';
        }
        redirect('/admin/settings');
    }

    public function monitor()
    {
        $this->requireAdminLogin();
        $data = ['title' => 'Real-time Monitor - Admin'];
        $this->view('admin/monitor', $data);
    }

    public function monitor_stats()
    {
        $this->requireAdminLogin();
        header('Content-Type: application/json');
        $db = \App\Core\Database::getInstance()->getConnection();

        $stats = [
            'active_users_5m' => (int)$db->query("SELECT COUNT(*) FROM users WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)")->fetchColumn(),
            'open_reports' => (int)$db->query("SELECT COUNT(*) FROM reports WHERE status = 'open'")->fetchColumn(),
            'resolved_reports' => (int)$db->query("SELECT COUNT(*) FROM reports WHERE status = 'resolved'")->fetchColumn(),
            'messages_today' => (int)$db->query("SELECT COUNT(*) FROM comments WHERE DATE(created_at) = CURDATE()")->fetchColumn(),
            'contact_new' => 0
        ];

        try {
            $stats['contact_new'] = (int)$db->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'new'")->fetchColumn();
        } catch (\Throwable $e) {
            $stats['contact_new'] = 0;
        }

        echo json_encode($stats);
        exit;
    }

    public function contact_requests()
    {
        $this->requireAdminLogin();
        $model = $this->model('ContactRequest');
        $data = [
            'title' => 'Contact Requests - Admin',
            'requests' => $model->getAll()
        ];
        $this->view('admin/contact_requests', $data);
    }

    public function resolve_contact($id)
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/contact_requests');
        }
        $status = trim($_POST['status'] ?? 'in_progress');
        $allowed = ['new', 'in_progress', 'resolved'];
        if (!in_array($status, $allowed, true)) {
            $_SESSION['flash_error'] = 'Invalid status.';
            redirect('/admin/contact_requests');
        }
        $model = $this->model('ContactRequest');
        $ok = $model->updateStatusAndResponse($id, $status, trim($_POST['admin_response'] ?? ''));
        $_SESSION['flash_' . ($ok ? 'success' : 'error')] = $ok ? 'Contact request updated.' : 'Failed to update contact request.';
        redirect('/admin/contact_requests');
    }

    public function backup()
    {
        $this->requireAdminLogin();
        $data = ['title' => 'Backup & Restore - Admin'];
        $this->view('admin/backup', $data);
    }

    public function backup_download()
    {
        $this->requireAdminLogin();
        $db = \App\Core\Database::getInstance()->getConnection();
        $tables = ['users', 'reports', 'comments', 'categories', 'system_config', 'announcements', 'contact_requests'];

        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="laf_backup_' . date('Ymd_His') . '.sql"');

        foreach ($tables as $table) {
            try {
                $create = $db->query("SHOW CREATE TABLE {$table}")->fetch();
                if (!empty($create['Create Table'])) {
                    echo "\nDROP TABLE IF EXISTS `{$table}`;\n";
                    echo $create['Create Table'] . ";\n\n";
                }
                $rows = $db->query("SELECT * FROM {$table}")->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $cols = array_map(fn($c) => "`{$c}`", array_keys($row));
                    $vals = array_map(fn($v) => $v === null ? 'NULL' : $db->quote((string)$v), array_values($row));
                    echo "INSERT INTO `{$table}` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ");\n";
                }
            } catch (\Throwable $e) {
                // Skip optional tables.
            }
        }
        exit;
    }

    public function restore_backup()
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['backup_sql']['tmp_name'])) {
            $_SESSION['flash_error'] = 'Please upload a backup SQL file.';
            redirect('/admin/backup');
        }

        $sql = file_get_contents($_FILES['backup_sql']['tmp_name']);
        if ($sql === false || trim($sql) === '') {
            $_SESSION['flash_error'] = 'Invalid SQL file.';
            redirect('/admin/backup');
        }

        $db = \App\Core\Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($statements as $stmt) {
                if ($stmt !== '') {
                    $db->exec($stmt);
                }
            }
            $db->commit();
            $_SESSION['flash_success'] = 'Backup restored successfully.';
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $_SESSION['flash_error'] = 'Restore failed: ' . $e->getMessage();
        }
        redirect('/admin/backup');
    }

    /* --- Global Announcements --- */

    public function announcements()
    {
        $this->requireAdminLogin();
        $announcementModel = $this->model('Announcement');
        $announcements = $announcementModel->getAll();

        $data = [
            'title' => 'Manage Announcements - Admin',
            'announcements' => $announcements
        ];
        $this->view('admin/announcements', $data);
    }

    public function add_announcement()
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $announcementModel = $this->model('Announcement');
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'type' => trim($_POST['type']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if ($announcementModel->create($data)) {
                $_SESSION['flash_success'] = 'Announcement posted successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to post announcement.';
            }
        }
        redirect('/admin/announcements');
    }

    public function delete_announcement($id)
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $announcementModel = $this->model('Announcement');
            if ($announcementModel->delete($id)) {
                $_SESSION['flash_success'] = 'Announcement deleted.';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete announcement.';
            }
        }
        redirect('/admin/announcements');
    }

    public function toggle_announcement($id)
    {
        $this->requireAdminLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = (int)$_POST['is_active'];
            $announcementModel = $this->model('Announcement');
            $announcementModel->toggleActive($id, $status);
            $_SESSION['flash_success'] = 'Announcement status updated.';
        }
        redirect('/admin/announcements');
    }

    /* --- Export Data & Utilities --- */
    public function export_data()
    {
        $this->requireAdminLogin();
        $reports = $this->itemModel->getAllReports();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="report_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        // CSV Headers
        fputcsv($output, ['Report ID', 'Title', 'Type', 'Category', 'Status', 'User Name', 'User Email', 'Contact Info', 'Location', 'Date Posted']);

        foreach ($reports as $report) {
            fputcsv($output, [
                $report['report_id'],
                $report['title'],
                $report['type'],
                $report['category_name'],
                $report['status'],
                $report['username'],
                $report['email'],
                $report['contact_info'],
                $report['location'],
                $report['date_posted']
            ]);
        }
        fclose($output);
        exit;
    }
}
