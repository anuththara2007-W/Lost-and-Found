<?php
if (!defined('ROOT')) {
    require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
}
require_once ROOT . '/resources/views/layouts/header.php';
?>

<style>
/* ── Lost & Found Design Tokens ── */
:root {
    --white:         #FAFAF8;
    --off-white:     #F2F0EB;
    --parchment:     #E8E3D8;
    --warm-mid:      #C9C0AE;
    --clay:          #9C8C78;
    --ink:           #3A3830;
    --midnight:      #1E2027;
    --terracotta:    #C96442;
    --terracotta-lt: #EDD5C8;
    --sage:          #5C7A65;
    --sage-lt:       #D1DFCF;
    --amber:         #D4940A;
    --amber-lt:      #F5E9C8;
    --shadow-card:   0 2px 14px rgba(30,32,39,0.07), 0 0 0 1px var(--parchment);
    --shadow-hover:  0 8px 32px rgba(30,32,39,0.12), 0 0 0 1px var(--warm-mid);
}

/* ── Layout ── */
.ann-page {
    display: flex;
    min-height: 100vh;
    background: var(--white);
    font-family: 'DM Sans', sans-serif;
}

/* ── Sidebar ── */
.ann-sidebar {
    width: 240px;
    min-height: 100vh;
    background: var(--midnight);
    padding: 0;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
}
.ann-sidebar-header {
    padding: 32px 24px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.ann-sidebar-header h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.3rem;
    font-weight: 400;
    color: var(--white);
    letter-spacing: .2px;
    margin: 0;
}
.ann-sidebar-header h2 em {
    color: var(--terracotta);
    font-style: italic;
}
.ann-sidebar-header p {
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--clay);
    margin: 6px 0 0;
}
.ann-sidebar-menu {
    list-style: none;
    padding: 16px 0;
    margin: 0;
    flex: 1;
}
.ann-sidebar-menu li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 24px;
    color: var(--warm-mid);
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    transition: color .15s, background .15s;
}
.ann-sidebar-menu li a:hover {
    color: var(--white);
    background: rgba(255,255,255,0.05);
}
.ann-sidebar-menu li.active a {
    color: var(--white);
    background: rgba(201,100,66,0.15);
    border-right: 3px solid var(--terracotta);
    font-weight: 500;
}
.ann-sidebar-menu li a i {
    width: 16px;
    text-align: center;
    font-size: 12px;
    opacity: .7;
}
.ann-sidebar-menu li.active a i { opacity: 1; }
.ann-sidebar-logout {
    padding: 16px 0 24px;
    border-top: 1px solid rgba(255,255,255,0.07);
}

/* ── Main Content ── */
.ann-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* ── Topbar ── */
.ann-topbar {
    background: var(--white);
    border-bottom: 1px solid var(--parchment);
    padding: 20px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ann-topbar-left h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 400;
    color: var(--midnight);
    margin: 0 0 2px;
}
.ann-topbar-left p {
    font-size: 12px;
    color: var(--clay);
    margin: 0;
    letter-spacing: .3px;
}
.ann-topbar-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--terracotta-lt);
    color: var(--terracotta);
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .8px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 20px;
}
.ann-topbar-badge .dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--terracotta);
}

/* ── Body Grid ── */
.ann-body {
    flex: 1;
    padding: 32px 36px;
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 28px;
    align-items: start;
}
@media (max-width: 900px) { .ann-body { grid-template-columns: 1fr; } }

/* ── Cards ── */
.ann-card {
    background: var(--off-white);
    border-radius: 16px;
    box-shadow: var(--shadow-card);
    overflow: hidden;
    transition: box-shadow .2s;
}
.ann-card:hover { box-shadow: var(--shadow-hover); }

.ann-card-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid var(--parchment);
    display: flex;
    align-items: center;
    gap: 10px;
}
.ann-card-header-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
}
.icon-terracotta { background: var(--terracotta-lt); color: var(--terracotta); }
.icon-midnight   { background: rgba(30,32,39,0.08); color: var(--midnight); }

.ann-card-header h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    font-weight: 400;
    color: var(--midnight);
    margin: 0;
}
.ann-card-header p {
    font-size: 11px;
    color: var(--clay);
    margin: 2px 0 0;
}

.ann-card-body { padding: 24px 26px; }

/* ── Form Elements ── */
.form-group { margin-bottom: 18px; }
.form-group label {
    display: block;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--clay);
    margin-bottom: 7px;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    background: var(--parchment);
    border: 1px solid var(--warm-mid);
    border-radius: 9px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    color: var(--ink);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--terracotta);
    box-shadow: 0 0 0 3px var(--terracotta-lt);
    background: var(--white);
}
.form-group input::placeholder,
.form-group textarea::placeholder { color: var(--warm-mid); }
.form-group textarea { resize: vertical; min-height: 90px; }

/* Publish toggle row */
.publish-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: var(--sage-lt);
    border: 1px solid #b6ccb4;
    border-radius: 9px;
    margin-bottom: 20px;
}
.publish-row input[type="checkbox"] {
    width: 16px; height: 16px;
    accent-color: var(--sage);
    cursor: pointer;
    flex-shrink: 0;
}
.publish-row label {
    font-size: 13px;
    font-weight: 500;
    color: var(--sage);
    margin: 0;
    cursor: pointer;
}

/* ── Buttons ── */
.btn-primary-full {
    width: 100%;
    padding: 12px;
    background: var(--terracotta);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: .3px;
    cursor: pointer;
    transition: background .18s, transform .18s, box-shadow .18s;
}
.btn-primary-full:hover {
    background: #b5572f;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,100,66,.35);
}
.btn-primary-full i { margin-right: 6px; }

/* ── Table Card ── */
.table-wrap { overflow-x: auto; }

.ann-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.ann-table thead th {
    padding: 12px 16px;
    text-align: left;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--clay);
    background: var(--parchment);
    border-bottom: 1px solid var(--warm-mid);
}
.ann-table thead th:first-child { border-radius: 0; }
.ann-table tbody tr {
    border-bottom: 1px solid var(--parchment);
    transition: background .12s;
}
.ann-table tbody tr:last-child { border-bottom: none; }
.ann-table tbody tr:hover { background: rgba(232,227,216,0.4); }
.ann-table td { padding: 14px 16px; color: var(--ink); vertical-align: middle; }
.ann-table td strong { color: var(--midnight); font-weight: 500; }
.ann-table td small { font-size: 11px; color: var(--clay); display: block; margin-top: 2px; }

/* ── Type Badges ── */
.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: .8px;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 20px;
}
.type-badge .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.badge-info    { background: #dbeafe; color: #2563eb; }
.badge-warning { background: var(--amber-lt); color: var(--amber); }
.badge-success { background: var(--sage-lt);  color: var(--sage); }
.badge-danger  { background: var(--terracotta-lt); color: var(--terracotta); }

/* ── Status Toggle ── */
.toggle-btn {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .5px;
    padding: 5px 12px;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    transition: opacity .15s, transform .15s;
    font-family: 'DM Sans', sans-serif;
}
.toggle-btn:hover { opacity: .85; transform: translateY(-1px); }
.toggle-on  { background: var(--sage-lt); color: var(--sage); }
.toggle-off { background: var(--parchment); color: var(--clay); }

/* ── Delete Button ── */
.btn-delete-sm {
    background: none;
    border: 1px solid var(--terracotta-lt);
    color: var(--terracotta);
    border-radius: 7px;
    padding: 5px 10px;
    font-size: 12px;
    cursor: pointer;
    transition: background .15s, transform .15s;
    font-family: 'DM Sans', sans-serif;
}
.btn-delete-sm:hover {
    background: var(--terracotta-lt);
    transform: translateY(-1px);
}

/* ── Empty State ── */
.empty-state {
    padding: 48px 24px;
    text-align: center;
    color: var(--clay);
}
.empty-state i {
    font-size: 2.5rem;
    color: var(--warm-mid);
    margin-bottom: 12px;
    display: block;
}
.empty-state p { font-size: 13px; margin: 0; }
</style>

<div class="ann-page">

    <!-- ── Sidebar ── -->
    <aside class="ann-sidebar">
        <div class="ann-sidebar-header">
            <h2>Lost &amp; <em>Found</em></h2>
            <p>Admin Panel</p>
        </div>
        <ul class="ann-sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/admin/reports"><i class="fas fa-file-alt"></i> Manage Reports</a></li>
            <li class="active"><a href="<?= BASE_URL ?>/admin/announcements"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?= BASE_URL ?>/admin/settings"><i class="fas fa-cog"></i> System Settings</a></li>
        </ul>
        <div class="ann-sidebar-logout">
            <ul class="ann-sidebar-menu" style="padding:0;">
                <li><a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </aside>

    <!-- ── Main ── -->
    <main class="ann-main">

        <!-- Topbar -->
        <header class="ann-topbar">
            <div class="ann-topbar-left">
                <h1>Global Announcements</h1>
                <p>Broadcast messages visible to all users across the platform</p>
            </div>
            <div class="ann-topbar-badge">
                <span class="dot"></span>
                <?php echo count($data['announcements'] ?? []); ?> Total
            </div>
        </header>

        <!-- Body Grid -->
        <div class="ann-body">

            <!-- ── Post Announcement Form ── -->
            <div class="ann-card">
                <div class="ann-card-header">
                    <div class="ann-card-header-icon icon-terracotta">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h2>New Announcement</h2>
                        <p>Broadcast a message to all users</p>
                    </div>
                </div>
                <div class="ann-card-body">
                    <form action="<?= BASE_URL ?>/admin/add_announcement" method="POST">

                        <div class="form-group">
                            <label for="ann-title">Title</label>
                            <input type="text" id="ann-title" name="title" required
                                   placeholder="e.g. Scheduled Maintenance on Apr 5">
                        </div>

                        <div class="form-group">
                            <label for="ann-type">Announcement Type</label>
                            <select id="ann-type" name="type">
                                <option value="info">ℹ️ Info — General update</option>
                                <option value="warning">⚠️ Warning — Requires attention</option>
                                <option value="success">✅ Success — Positive news</option>
                                <option value="danger">🚨 Danger — Critical alert</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ann-content">Message Content</label>
                            <textarea id="ann-content" name="content" rows="4" required
                                      placeholder="Type your announcement here…"></textarea>
                        </div>

                        <div class="publish-row">
                            <input type="checkbox" id="ann-active" name="is_active" value="1" checked>
                            <label for="ann-active"><i class="fas fa-broadcast-tower"></i> &nbsp;Publish immediately</label>
                        </div>

                        <button type="submit" class="btn-primary-full">
                            <i class="fas fa-paper-plane"></i> Post Announcement
                        </button>

                    </form>
                </div>
            </div><!-- /form card -->

            <!-- ── Announcements Table ── -->
            <div class="ann-card">
                <div class="ann-card-header">
                    <div class="ann-card-header-icon icon-midnight">
                        <i class="fas fa-list"></i>
                    </div>
                    <div>
                        <h2>Recent Announcements</h2>
                        <p>Manage visibility and remove outdated broadcasts</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="ann-table">
                        <thead>
                            <tr>
                                <th>Title &amp; Content</th>
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
                                <td>
                                    <strong><?php echo htmlspecialchars($ann['title']); ?></strong>
                                    <small><?php echo htmlspecialchars(mb_strimwidth($ann['content'], 0, 60, '…')); ?></small>
                                </td>
                                <td>
                                    <span class="type-badge badge-<?php echo htmlspecialchars($ann['type'] ?? 'info'); ?>">
                                        <span class="dot"></span>
                                        <?php echo ucfirst(htmlspecialchars($ann['type'] ?? 'info')); ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="<?= BASE_URL ?>/admin/toggle_announcement/<?php echo $ann['announcement_id']; ?>" method="POST">
                                        <input type="hidden" name="is_active" value="<?php echo $ann['is_active'] ? '0' : '1'; ?>">
                                        <button type="submit" class="toggle-btn <?php echo $ann['is_active'] ? 'toggle-on' : 'toggle-off'; ?>">
                                            <?php echo $ann['is_active'] ? '● Active' : '○ Hidden'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td style="color:var(--clay); font-size:12px; white-space:nowrap;">
                                    <?php echo date('M d, Y', strtotime($ann['date_posted'])); ?>
                                </td>
                                <td>
                                    <form action="<?= BASE_URL ?>/admin/delete_announcement/<?php echo $ann['announcement_id']; ?>" method="POST"
                                          onsubmit="return confirm('Delete this announcement? This cannot be undone.');">
                                        <button type="submit" class="btn-delete-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-bullhorn"></i>
                                        <p>No announcements yet. Post one using the form on the left.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div><!-- /table-wrap -->
            </div><!-- /table card -->

        </div><!-- /ann-body -->
    </main>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
