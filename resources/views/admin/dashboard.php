<?php
if (!defined('ROOT')) {
    require_once dirname(__DIR__, 3) . '/config/config.php';
}
$pageCss = ['admin/admin_dashboard.css'];
require_once ROOT . '/resources/views/layouts/header.php';
if (empty($data)) {
    $data = [
        'users' => [1, 2],
        'activeCount' => 12,
        'resolvedCount' => 150,
        'reports' => [
            ['report_id' => '101', 'title' => 'Lost Wallet', 'type' => 'lost', 'username' => 'johndoe', 'date_posted' => '2026-03-20', 'status' => 'active'],
            ['report_id' => '102', 'title' => 'Found Keys', 'type' => 'found', 'username' => 'janedoe', 'date_posted' => '2026-03-22', 'status' => 'resolved']
        ]
    ];
}
?>

<div class="admin-wrapper">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li><a href="<?= BASE_URL ?>/admin/items"><i class="fas fa-box"></i> Manage Items</a></li>
            <li><a href="<?= BASE_URL ?>/admin/announcements"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-topbar">
            <h1>Dashboard Overview</h1>
            <div class="admin-profile" style="display: flex; gap: 15px; align-items: center;">
                <a href="<?= BASE_URL ?>/admin/export_data" class="btn btn-secondary" style="font-size: 13px;"><i class="fas fa-download"></i> Export CSV</a>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
            </div>
        </header>

        <section class="admin-metrics">
            <div class="metric-card">
                <i class="fas fa-users metric-icon blue"></i>
                <div class="metric-details">
                    <h3>Total Users</h3>
                    <p><?php echo count($data['users'] ?? []); ?></p>
                </div>
            </div>
            <div class="metric-card">
                <i class="fas fa-file-alt metric-icon orange"></i>
                <div class="metric-details">
                    <h3>Active Reports</h3>
                    <p><?php echo $data['activeCount'] ?? count($data['reports'] ?? []); ?></p>
                </div>
            </div>
            <div class="metric-card">
                <i class="fas fa-check-circle metric-icon green"></i>
                <div class="metric-details">
                    <h3>Resolved Items</h3>
                    <p><?php echo $data['resolvedCount'] ?? '--'; ?></p>
                </div>
            </div>
        </section>

        <section class="admin-recent-activity">
            <h2>Recent Reports</h2>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['reports'])): ?>
                            <?php foreach (array_slice($data['reports'], 0, 5) as $report): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($report['report_id']); ?></td>
                                    <td><?php echo htmlspecialchars($report['title']); ?></td>
                                    <td><span class="badge <?php echo $report['type']; ?>"><?php echo ucfirst(htmlspecialchars($report['type'])); ?></span></td>
                                    <td><?php echo htmlspecialchars($report['username'] ?? 'Unknown'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($report['date_posted'])); ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/item/show/<?php echo $report['report_id']; ?>" class="btn-action view" target="_blank">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No recent reports found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
