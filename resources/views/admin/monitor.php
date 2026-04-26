<?php
/**
 * Admin View: Real-time System Monitor
 *
 * Displays live system statistics in a colourful metric grid.
 * Stats are fetched from the monitor_stats JSON endpoint every 5 seconds
 * via a JavaScript polling loop and injected into placeholder elements.
 *
 * Metrics shown:
 *   - Active users in the last 5 minutes
 *   - Open reports
 *   - Resolved reports
 *   - Messages sent today
 *   - New (unresolved) contact requests
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
            <li><a href="<?= BASE_URL ?>/admin/contact_requests"><i class="fas fa-envelope"></i> Contact Requests</a></li>
            <!-- "Real-time Monitor" is the currently active page -->
            <li class="active"><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup &amp; Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <!-- Top bar: page title, subtitle, and back navigation -->
        <header class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1 style="margin:0;">Real-time System Monitor</h1>
                <p style="color:#64748b; margin:6px 0 0;">Live insights. Auto-refresh every 5s.</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary" style="border-radius:999px;">&larr; Back</a>
        </header>

        <section class="admin-content">
            <!-- Responsive metric card grid; each card contains a label and a live value placeholder -->
            <div id="monitor-grid" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px;">

                <!-- Active users in the last 5 minutes (blue gradient) -->
                <div style="background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Active users (5m)</div>
                    <div id="m-active" style="font-size:2rem; font-weight:700;">--</div>
                </div>

                <!-- Open (unresolved) report count (green gradient) -->
                <div style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Open reports</div>
                    <div id="m-open" style="font-size:2rem; font-weight:700;">--</div>
                </div>

                <!-- Resolved report count (indigo gradient) -->
                <div style="background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Resolved reports</div>
                    <div id="m-resolved" style="font-size:2rem; font-weight:700;">--</div>
                </div>

                <!-- Messages sent today (amber gradient) -->
                <div style="background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Messages today</div>
                    <div id="m-msg" style="font-size:2rem; font-weight:700;">--</div>
                </div>

                <!-- New / open contact request count (red gradient) -->
                <div style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">New contact requests</div>
                    <div id="m-contact" style="font-size:2rem; font-weight:700;">--</div>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
/**
 * Fetches the latest monitor stats from the JSON API and updates each
 * metric card. Errors are logged to the console but do not break the UI.
 */
async function refreshMonitor() {
    try {
        // Request stats from the server-side monitor_stats endpoint
        const res = await fetch('<?= BASE_URL ?>/admin/monitor_stats', { credentials: 'same-origin' });
        const d = await res.json();

        // Inject each stat into its corresponding placeholder element
        document.getElementById('m-active').textContent   = d.active_users_5m  ?? 0;
        document.getElementById('m-open').textContent     = d.open_reports      ?? 0;
        document.getElementById('m-resolved').textContent = d.resolved_reports  ?? 0;
        document.getElementById('m-msg').textContent      = d.messages_today    ?? 0;
        document.getElementById('m-contact').textContent  = d.contact_new       ?? 0;
    } catch (e) {
        console.error(e); // Silent fail: the dashboard keeps showing the last known values
    }
}

// Run immediately on page load, then repeat every 5 seconds (5000 ms)
refreshMonitor();
setInterval(refreshMonitor, 5000);
</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
