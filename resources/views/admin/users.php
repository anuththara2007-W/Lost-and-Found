<?php
/**
 * Admin View: Manage Users
 *
 * Displays all registered users in a table. Admins can:
 *   - Assign or update a trust badge (None / Verified / Bronze / Silver / Gold).
 *   - Ban or unban a user account (toggles the is_banned flag).
 *
 * Expected $data keys:
 *   - users (array): All user records (user_id, username, email,
 *                    created_at, badge_status, is_banned).
 */
require_once __DIR__ . '/../layouts/header.php';
?>
<!-- Load admin dashboard base styles and user-management specific styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin_dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/users.css">

<div class="admin-wrapper">
    <!-- Admin Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <!-- "Manage Users" is the currently active page -->
            <li class="active"><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li><a href="<?= BASE_URL ?>/admin/items"><i class="fas fa-box"></i> Manage Items</a></li>
            <li><a href="<?= BASE_URL ?>/admin/contact_requests"><i class="fas fa-envelope"></i> Contact Requests</a></li>
            <li><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup &amp; Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
        <!-- Page header -->
        <header class="admin-topbar">
            <h1>Manage Users</h1>
        </header>

        <section class="admin-content">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Trust Badge</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['users'])): ?>
                            <?php foreach ($data['users'] as $user): ?>
                                <tr>
                                    <!-- User ID prefixed with # for readability -->
                                    <td>#<?php echo htmlspecialchars($user['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>

                                    <!-- Registration date formatted as "Mon DD, YYYY" -->
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>

                                    <!-- Trust Badge + Ban/Unban actions share the same cell -->
                                    <td>
                                        <!-- Badge assignment form: inline so it sits beside the ban button -->
                                        <form action="<?= BASE_URL ?>/admin/assign_badge/<?php echo $user['user_id']; ?>"
                                              method="POST" style="display:inline-flex; gap: 5px;">
                                            <!-- Dropdown pre-selects the user's current badge tier -->
                                            <select name="badge_status" class="badge-select">
                                                <option value="none"     <?php echo ($user['badge_status'] ?? '') == 'none'     ? 'selected' : ''; ?>>None</option>
                                                <option value="verified" <?php echo ($user['badge_status'] ?? '') == 'verified' ? 'selected' : ''; ?>>Verified</option>
                                                <option value="bronze"   <?php echo ($user['badge_status'] ?? '') == 'bronze'   ? 'selected' : ''; ?>>Bronze</option>
                                                <option value="silver"   <?php echo ($user['badge_status'] ?? '') == 'silver'   ? 'selected' : ''; ?>>Silver</option>
                                                <option value="gold"     <?php echo ($user['badge_status'] ?? '') == 'gold'     ? 'selected' : ''; ?>>Gold</option>
                                            </select>
                                            <button type="submit" class="btn-save">Save</button>
                                        </form>

                                        <!-- Ban / Unban toggle form -->
                                        <form action="<?= BASE_URL ?>/admin/toggle_ban/<?php echo $user['user_id']; ?>"
                                              method="POST" style="display:inline-flex; gap: 5px; margin-left: 10px;">
                                            <!-- Hidden field sends the NEW desired state (opposite of current) -->
                                            <input type="hidden" name="is_banned" value="<?php echo !empty($user['is_banned']) ? '0' : '1'; ?>">
                                            <!-- Button label and colour reflect the action that will be taken -->
                                            <button type="submit" class="btn-save"
                                                    style="background: <?php echo !empty($user['is_banned']) ? '#28a745' : '#dc3545'; ?>;">
                                                <?php echo !empty($user['is_banned']) ? 'Unban User' : 'Ban User'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Empty state when no users are registered -->
                            <tr><td colspan="6" class="text-center">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
