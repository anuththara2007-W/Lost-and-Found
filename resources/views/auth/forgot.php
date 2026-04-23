<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<div style="max-width: 600px; margin: 60px auto; background: var(--off-white); padding: 40px; border-radius: 16px; box-shadow: var(--shadow-card); text-align: center;">
    <h2 style="color: var(--midnight); font-size: 2rem;">Forgot Password?</h2>
    <p style="color: var(--clay); font-size: 14px; margin-top: 10px; margin-bottom: 30px;">Enter your email to receive a password reset link.</p>
    <form action="" method="POST">
        <div class="input-group">
            <input type="email" name="email" class="input-field" placeholder="Email Address" required>
        </div>
        <button type="submit" class="btn btn-secondary w-full" style="padding: 12px; margin-top: 20px;">Send Reset Link</button>
    </form>
</div>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>