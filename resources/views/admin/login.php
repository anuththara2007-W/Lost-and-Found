<?php
if (!defined('ROOT')) {
    require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
}
require_once ROOT . '/resources/views/layouts/header.php';
?>

<div class="form-container" style="margin-top: 60px; margin-bottom: 60px;">
    <div class="form-header">
        <h2 style="color: var(--midnight);">Admin Access</h2>
        <p>Restricted area. Please sign in to the moderator dashboard.</p>
    </div>

    <form action="<?= BASE_URL ?>/admin/login" method="POST">
        <div class="input-group">
            <label class="input-label" for="username">Admin Username</label>
            <input type="text" id="username" name="username" class="input-field" placeholder="Admin username" required autofocus>
        </div>

        <div class="input-group mb-20">
            <label class="input-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="input-field" placeholder="Admin password" required>
        </div>

        <button type="submit" class="btn btn-primary w-full" style="padding: 12px; font-size: 15px; background: var(--midnight);">Access Dashboard</button>
    </form>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
