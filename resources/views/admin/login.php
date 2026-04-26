<?php
/**
 * Admin View: Admin Login
 *
 * A restricted-access login form for administrators. Submits credentials
 * to the admin/login POST route for server-side validation.
 * No session check is performed here; that is handled by the controller.
 */
require_once ROOT . '/resources/views/layouts/header.php';
?>

<!-- Centred login card with top/bottom spacing -->
<div class="form-container" style="margin-top: 60px; margin-bottom: 60px;">
    <div class="form-header">
        <h2 style="color: var(--midnight);">Admin Access</h2>
        <p>Restricted area. Please sign in to the moderator dashboard.</p>
    </div>

    <!-- POST credentials to the admin login route -->
    <form action="<?= BASE_URL ?>/admin/login" method="POST">
        <!-- Admin username field: autofocused for quick keyboard entry -->
        <div class="input-group">
            <label class="input-label" for="username">Admin Username</label>
            <input type="text" id="username" name="username" class="input-field" placeholder="Admin username" required autofocus>
        </div>

        <!-- Admin password field -->
        <div class="input-group mb-20">
            <label class="input-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="input-field" placeholder="Admin password" required>
        </div>

        <!-- Submit button; uses the midnight colour token to distinguish it from public-facing forms -->
        <button type="submit" class="btn btn-primary w-full" style="padding: 12px; font-size: 15px; background: var(--midnight);">Access Dashboard</button>
    </form>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
