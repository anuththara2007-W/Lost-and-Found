<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin-dashboard.css">

<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><h2>Admin Panel</h2></div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li class="active"><a href="<?= BASE_URL ?>/admin/items"><i class="fas fa-box"></i> Manage Items</a></li>
            <li><a href="<?= BASE_URL ?>/admin/contact_requests"><i class="fas fa-envelope"></i> Contact Requests</a></li>
            <li><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup & Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
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
                                    <td>#<?= (int)$report['report_id'] ?></td>
                                    <td><a href="<?= BASE_URL ?>/item/show/<?= (int)$report['report_id'] ?>"><?= escape($report['title']) ?></a></td>
                                    <td><?= ucfirst(escape($report['status'])) ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/delete_report/<?= (int)$report['report_id'] ?>" method="POST" onsubmit="return confirm('Delete this item?');">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No items found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
