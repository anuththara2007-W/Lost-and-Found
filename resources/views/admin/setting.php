<?php
require_once __DIR__ . '/../layouts/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin-dashboard.css">
<style>
.settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem; }
.settings-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
.settings-card h2 { margin-top: 0; margin-bottom: 1.5rem; font-size: 1.25rem; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.5rem; color: #475569; font-weight: 500; font-size: 0.875rem; }
.form-group input, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.875rem; }
.btn-primary { background: #3b82f6; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; transition: background 0.3s; font-weight: 600; }
.btn-primary:hover { background: #2563eb; }
.cat-list { list-style: none; padding:0; margin:0; }
.cat-list li { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0; align-items: center; }
.btn-danger { background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; }
</style>

<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><h2>Admin Panel</h2></div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li class="active"><a href="<?= BASE_URL ?>/admin/settings"><i class="fas fa-cog"></i> System Settings</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <h1>System Settings</h1>
        </header>

        <section class="admin-content settings-grid">
            <!-- Categories Management -->
            <div class="settings-card">
                <h2>Manage Categories</h2>
                <form action="<?= BASE_URL ?>/admin/add_category" method="POST" style="display:flex; gap: 10px; margin-bottom: 1.5rem;">
                    <input type="text" name="category_name" placeholder="New Category Name" required style="flex:1; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <button type="submit" class="btn-primary" style="padding: 0.5rem 1rem;">Add</button>
                </form>
                <ul class="cat-list">
                    <?php if(!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                            <li>
                                <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                <form action="<?= BASE_URL ?>/admin/delete_category/<?php echo $cat['category_id']; ?>" method="POST" onsubmit="return confirm('Delete this category?');">
                                    <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No categories configured.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Global Site Configurations -->
            <div class="settings-card">
                <h2>Global Configuration</h2>
                <form action="<?= BASE_URL ?>/admin/update_config" method="POST">
                    <div class="form-group">
                        <label>Site Name</label>
                        <input type="text" name="site_name" value="<?php echo htmlspecialchars($data['config']['site_name'] ?? 'Lost & Found Express'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Admin Notification Email</label>
                        <input type="email" name="admin_email" value="<?php echo htmlspecialchars($data['config']['admin_email'] ?? 'admin@gmail.com'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Maintenance Mode</label>
                        <select name="maintenance_mode">
                            <option value="0" <?php echo ($data['config']['maintenance_mode'] ?? '0') == '0' ? 'selected' : ''; ?>>Disabled - Site is Live</option>
                            <option value="1" <?php echo ($data['config']['maintenance_mode'] ?? '0') == '1' ? 'selected' : ''; ?>>Enabled - Under Construction</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="notify_new_report" value="1" <?php echo ($data['config']['notify_new_report'] ?? '1') === '1' ? 'checked' : ''; ?> style="width:auto; margin-right:6px;"> Notify on new reports</label>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="notify_new_message" value="1" <?php echo ($data['config']['notify_new_message'] ?? '1') === '1' ? 'checked' : ''; ?> style="width:auto; margin-right:6px;"> Notify on new messages</label>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="notify_contact_request" value="1" <?php echo ($data['config']['notify_contact_request'] ?? '1') === '1' ? 'checked' : ''; ?> style="width:auto; margin-right:6px;"> Notify on contact requests</label>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%;">Save Configuration</button>
                </form>
            </div>
        </section>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
