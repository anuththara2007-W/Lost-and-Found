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
            <li class="active"><a href="<?= BASE_URL ?>/admin/monitor"><i class="fas fa-chart-line"></i> Real-time Monitor</a></li>
            <li><a href="<?= BASE_URL ?>/admin/backup"><i class="fas fa-database"></i> Backup & Restore</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>
    <main class="admin-main">
        <header class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1 style="margin:0;">Real-time System Monitor</h1>
                <p style="color:#64748b; margin:6px 0 0;">Live insights. Auto-refresh every 5s.</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary" style="border-radius:999px;">&larr; Back</a>
        </header>
        <section class="admin-content">
            <div id="monitor-grid" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px;">
                <div style="background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Active users (5m)</div>
                    <div id="m-active" style="font-size:2rem; font-weight:700;">--</div>
                </div>
                <div style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Open reports</div>
                    <div id="m-open" style="font-size:2rem; font-weight:700;">--</div>
                </div>
                <div style="background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Resolved reports</div>
                    <div id="m-resolved" style="font-size:2rem; font-weight:700;">--</div>
                </div>
                <div style="background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">Messages today</div>
                    <div id="m-msg" style="font-size:2rem; font-weight:700;">--</div>
                </div>
                <div style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border-radius:16px; padding:18px;">
                    <div style="opacity:.9;">New contact requests</div>
                    <div id="m-contact" style="font-size:2rem; font-weight:700;">--</div>
                </div>
            </div>
        </section>
    </main>
</div>
<script>
async function refreshMonitor(){
  try{
    const res = await fetch('<?= BASE_URL ?>/admin/monitor_stats', { credentials: 'same-origin' });
    const d = await res.json();
    document.getElementById('m-active').textContent = d.active_users_5m ?? 0;
    document.getElementById('m-open').textContent = d.open_reports ?? 0;
    document.getElementById('m-resolved').textContent = d.resolved_reports ?? 0;
    document.getElementById('m-msg').textContent = d.messages_today ?? 0;
    document.getElementById('m-contact').textContent = d.contact_new ?? 0;
  }catch(e){ console.error(e); }
}
refreshMonitor();
setInterval(refreshMonitor, 5000);
</script>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
