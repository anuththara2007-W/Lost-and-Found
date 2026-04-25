<?php
require_once __DIR__ . '/../layouts/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin_dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/announcements.css">

<div class="admin-wrapper">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li class="active"><a href="<?= BASE_URL ?>/admin/announcements"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?= BASE_URL ?>/admin/settings"><i class="fas fa-cog"></i> System Settings</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <h1>Global Announcements</h1>
        </header>

        <section class="admin-content layout-grid">
            <!-- New Announcement Form -->
            <div class="card">
                <h2>Post Announcement</h2>
                <form action="<?= BASE_URL ?>/admin/add_announcement" method="POST">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" required placeholder="Maintenance Update">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type">
                            <option value="info">Info (Blue)</option>
                            <option value="warning">Warning (Yellow)</option>
                            <option value="success">Success (Green)</option>
                            <option value="danger">Danger (Red)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message Content</label>
                        <textarea name="content" rows="4" required placeholder="Type your announcement here..."></textarea>
                    </div>
                    <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                        <input type="checkbox" name="is_active" value="1" checked style="width:auto;">
                        <label style="margin:0;">Publish Immediately</label>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;">Create Announcement</button>
                </form>
            </div>

             <!-- Existing Announcements Table -->
            <div class="card" style="padding:0; overflow:hidden;">
                <h2 style="padding: 1.5rem 1.5rem 0 1.5rem;">Recent Announcements</h2>
                <table class="admin-table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['announcements'])): ?>
                            <?php foreach ($data['announcements'] as $ann): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($ann['title']); ?></strong></td>
                                    <td><span class="badge badge-<?php echo $ann['type']; ?>"><?php echo ucfirst($ann['type']); ?></span></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/toggle_announcement/<?php echo $ann['announcement_id']; ?>" method="POST">
                                            <input type="hidden" name="is_active" value="<?php echo $ann['is_active'] ? '0' : '1'; ?>">
                                            <button type="submit" class="toggle-btn <?php echo $ann['is_active'] ? 'toggle-on' : 'toggle-off'; ?>">
                                                <?php echo $ann['is_active'] ? 'Active' : 'Hidden'; ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo date('M d', strtotime($ann['created_at'])); ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/delete_announcement/<?php echo $ann['announcement_id']; ?>" method="POST" onsubmit="return confirm('Delete this announcement?');" style="display:inline;">
                                            <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No announcements created yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>