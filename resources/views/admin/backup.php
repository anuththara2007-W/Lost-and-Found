<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin_dashboard.css">
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><h2>Admin Panel</h2></div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li><a href="<?= BASE_URL ?>/admin/items"><i class="fas fa-box"></i> Manage Items</a></li>
            <li><a href="<?= BASE_URL ?>/admin/contact_requests"><i class="fas fa-envelope"></i> Contact Requests</a></li>
            <li><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li class="active"><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup & Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>
    <main class="admin-main">
        <header class="admin-topbar"><h1>Backup & Restore</h1></header>
        <section class="admin-content">
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:14px;">
                <h3>Download Backup</h3>
                <p>Create a full SQL export of core system data.</p>
                <a href="<?= BASE_URL ?>/admin/backup_download" class="btn btn-primary">Download SQL Backup</a>
            </div>
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:16px;">
                <h3>Restore Backup</h3>
                <p>Upload a `.sql` file to restore data. This can overwrite existing data.</p>
                <form method="POST" action="<?= BASE_URL ?>/admin/restore_backup" enctype="multipart/form-data">
                    <input type="file" name="backup_sql" accept=".sql,text/plain" required>
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Restore backup now? This may overwrite current data.');">Restore</button>
                </form>
            </div>
        </section>
    </main>
</div>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
