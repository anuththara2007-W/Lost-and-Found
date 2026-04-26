<?php
/**
 * Admin View: Manage Items
 *
 * Lists all item reports in a table, allowing admins to view and
 * permanently delete individual items. Each row links to the public
 * item detail page and contains a delete form with a confirmation dialog.
 *
 * Expected $data keys:
 *   - reports (array): All report records (report_id, title, status).
 */
require_once ROOT . '/resources/views/layouts/header.php';
?>
<!-- Load admin dashboard base styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin_dashboard.css">

<div class="admin-wrapper">
    <!-- Admin Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="sidebar-header"><h2>Admin Panel</h2></div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <!-- "Manage Items" is the currently active page -->
            <li class="active"><a href="<?= BASE_URL ?>/admin/items"><i class="fas fa-box"></i> Manage Items</a></li>
            <li><a href="<?= BASE_URL ?>/admin/contact_requests"><i class="fas fa-envelope"></i> Contact Requests</a></li>
            <li><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup &amp; Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <!-- Page header -->
        <header class="admin-topbar">
            <h1>Manage Items</h1>
        </header>

        <section class="admin-content">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['reports'])): ?>
                            <?php foreach ($data['reports'] as $report): ?>
                                <tr>
                                    <!-- Report ID cast to int to prevent injection -->
                                    <td>#<?= (int)$report['report_id'] ?></td>

                                    <!-- Title links to the public item detail page -->
                                    <td><a href="<?= BASE_URL ?>/item/show/<?= (int)$report['report_id'] ?>"><?= escape($report['title']) ?></a></td>

                                    <!-- Status capitalised for display (e.g. "Open", "Resolved") -->
                                    <td><?= ucfirst(escape($report['status'])) ?></td>

                                    <!-- Delete form with confirmation dialog to prevent accidental removal -->
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/delete_report/<?= (int)$report['report_id'] ?>" method="POST" onsubmit="return confirm('Delete this item?');">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Empty state row when no item reports exist -->
                            <tr><td colspan="4" class="text-center">No items found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
