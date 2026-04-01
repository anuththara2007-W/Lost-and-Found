<?php
if (!defined('ROOT')) {
    require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
}
require_once ROOT . '/resources/views/layouts/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin-dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/reports.css">

<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><h2>Admin Panel</h2></div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li class="active"><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li><a href="<?= BASE_URL ?>/admin/announcements"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <h1>Manage Reports</h1>
        </header>

        <section class="admin-content">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['reports'])): ?>
                            <?php foreach ($data['reports'] as $report): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($report['report_id']); ?></td>
                                    <td><?php echo htmlspecialchars($report['title']); ?></td>
                                    <td><span class="badge <?php echo $report['type']; ?>"><?php echo ucfirst(htmlspecialchars($report['type'])); ?></span></td>
                                    <td><span class="badge <?php echo $report['status']; ?>"><?php echo ucfirst(htmlspecialchars($report['status'])); ?></span></td>
                                    <td><?php echo htmlspecialchars($report['username'] ?? 'Unknown'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($report['date_posted'])); ?></td>
                                    <td style="display:flex; gap: 5px;">
                                        <a href="<?= BASE_URL ?>/item/show/<?php echo $report['report_id']; ?>" class="btn-action view" target="_blank">View</a>
                                        <form action="<?= BASE_URL ?>/admin/delete_report/<?php echo $report['report_id']; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this report? This cannot be undone.');">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">No reports found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
