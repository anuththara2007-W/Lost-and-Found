<?php
require_once __DIR__ . '/../layouts/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin-dashboard.css">
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