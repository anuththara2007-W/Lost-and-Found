<?php
/**
 * Admin View: Contact Requests
 *
 * Lists all incoming contact/support requests submitted by users.
 * Each request card shows the sender's name, email, message, and
 * current status. Admins can update the status and leave internal
 * response notes via an inline form.
 *
 * Expected $data keys:
 *   - requests (array): List of contact request records from the database.
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
            <li><a href="<?= BASE_URL ?>/admin/items"><i class="fas fa-box"></i> Manage Items</a></li>
            <!-- "Contact Requests" is the currently active page -->
            <li class="active"><a href="<?= BASE_URL ?>/admin/contact_requests"><i class="fas fa-envelope"></i> Contact Requests</a></li>
            <li><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup &amp; Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <!-- Page header -->
        <header class="admin-topbar"><h1>Contact Requests</h1></header>

        <section class="admin-content">
            <?php if (empty($data['requests'])): ?>
                <!-- Empty state: no contact requests have been submitted yet -->
                <div class="table-container"><p>No contact requests yet.</p></div>
            <?php else: ?>
                <?php foreach ($data['requests'] as $r): ?>
                    <!-- Individual contact request card -->
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px; margin-bottom:12px;">

                        <!-- Request header: sender name, email, current status and submission date -->
                        <div style="display:flex; justify-content:space-between; gap:10px;">
                            <strong><?= escape($r['name']) ?> (<?= escape($r['email']) ?>)</strong>
                            <span><?= escape($r['status']) ?> - <?= escape($r['created_at']) ?></span>
                        </div>

                        <!-- Request body: nl2br preserves line breaks from the original message -->
                        <p style="margin:10px 0;"><?= nl2br(escape($r['message'])) ?></p>

                        <!-- Inline form to update the request status and add an internal admin note -->
                        <form method="POST" action="<?= BASE_URL ?>/admin/resolve_contact/<?= (int)$r['request_id'] ?>" style="display:grid; gap:8px;">

                            <!-- Status dropdown: new / in_progress / resolved; pre-selects the current value -->
                            <select name="status" style="padding:8px;">
                                <option value="new" <?= $r['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                <option value="in_progress" <?= $r['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="resolved" <?= $r['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                            </select>

                            <!-- Internal response notes: only visible to admins, not sent to the user -->
                            <textarea name="admin_response" placeholder="Internal response notes..." style="padding:8px; min-height:80px;"><?= escape($r['admin_response'] ?? '') ?></textarea>

                            <div><button type="submit" class="btn btn-primary">Update Request</button></div>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
