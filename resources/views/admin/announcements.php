<?php
/**
 * Admin View: Global Announcements
 *
 * This view allows administrators to create, publish, and delete
 * site-wide announcements. It displays a form for creating new announcements
 * and a table listing all existing announcements with toggle and delete actions.
 *
 * Expected $data keys:
 *   - announcements (array): List of announcement records from the database.
 */
require_once __DIR__ . '/../layouts/header.php';
?>
<!-- Load admin dashboard base styles and announcement-specific styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin-dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/announcements.css">

<div class="admin-wrapper">
    <!-- Admin Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <!-- "Announcements" is the currently active page -->
            <li class="active"><a href="<?= BASE_URL ?>/admin/announcements"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?= BASE_URL ?>/admin/settings"><i class="fas fa-cog"></i> System Settings</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <!-- Page header -->
        <header class="admin-topbar">
            <h1>Global Announcements</h1>
        </header>

        <!-- Two-column grid: create form on the left, announcements table on the right -->
        <section class="admin-content layout-grid">
            <!-- New Announcement Form -->
            <div class="card">
                <h2>Post Announcement</h2>
                <!-- POST to add_announcement route; all fields are required -->
                <form action="<?= BASE_URL ?>/admin/add_announcement" method="POST">
                    <!-- Announcement title -->
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" required placeholder="Maintenance Update">
                    </div>

                    <!-- Announcement type controls the badge colour shown to users -->
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type">
                            <option value="info">Info (Blue)</option>
                            <option value="warning">Warning (Yellow)</option>
                            <option value="success">Success (Green)</option>
                            <option value="danger">Danger (Red)</option>
                        </select>
                    </div>

                    <!-- Announcement body text -->
                    <div class="form-group">
                        <label>Message Content</label>
                        <textarea name="content" rows="4" required placeholder="Type your announcement here..."></textarea>
                    </div>

                    <!-- Checkbox: if checked the announcement is published immediately (is_active = 1) -->
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
                                    <!-- Escape title to prevent XSS -->
                                    <td><strong><?php echo htmlspecialchars($ann['title']); ?></strong></td>

                                    <!-- Show colour-coded type badge (info / warning / success / danger) -->
                                    <td><span class="badge badge-<?php echo $ann['type']; ?>"><?php echo ucfirst($ann['type']); ?></span></td>

                                    <!-- Inline form to toggle is_active between 1 (Active) and 0 (Hidden) -->
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/toggle_announcement/<?php echo $ann['announcement_id']; ?>" method="POST">
                                            <!-- Send the opposite of the current value so the controller can flip it -->
                                            <input type="hidden" name="is_active" value="<?php echo $ann['is_active'] ? '0' : '1'; ?>">
                                            <button type="submit" class="toggle-btn <?php echo $ann['is_active'] ? 'toggle-on' : 'toggle-off'; ?>">
                                                <?php echo $ann['is_active'] ? 'Active' : 'Hidden'; ?>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Display formatted creation date -->
                                    <td><?php echo date('M d', strtotime($ann['created_at'])); ?></td>

                                    <!-- Delete action with confirmation dialog -->
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/delete_announcement/<?php echo $ann['announcement_id']; ?>" method="POST" onsubmit="return confirm('Delete this announcement?');" style="display:inline;">
                                            <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Empty state row when no announcements exist -->
                            <tr><td colspan="5" class="text-center">No announcements created yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>