<?php

require_once __DIR__ . '/../../../config/config.php';

if (!empty($_SESSION['user'])) { header('Location: ' . BASE_URL . '/'); exit; }

$errors  = $_SESSION['flash_errors']  ?? [];
$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_errors'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — Lost &amp; Found</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Lost%20&%20Found/Lost-and-Found/public/assets/css/main.css">
</head>
<body style="background:var(--off-white);">

<header class="site-header" id="site-header">
  <div class="header-inner">
    <a href="<?= BASE_URL ?>/" class="header-logo">Lost &amp; <em>Found</em></a>
    <div style="flex:1;"></div>
    <div class="header-actions">
      <a href="<?= BASE_URL ?>/login" class="btn-header-ghost">Back to Sign In</a>
    </div>
  </div>
</header>

<div class="auth-page">
  <!-- Left panel -->
  <aside class="auth-panel">
    <a href="<?= BASE_URL ?>/" class="auth-panel-logo">Lost &amp; <em>Found</em></a>
    <div class="auth-panel-content">
      <h1 class="auth-panel-headline">
        Let's get<br>you back<br><em>in safely.</em>
      </h1>
      <p class="auth-panel-body">
        Enter the email address linked to your account and we'll send a secure password reset link within minutes.
      </p>
    </div>
    <div class="auth-panel-stats">
      <div class="auth-stat"><span class="auth-stat-num">~2 min</span><span class="auth-stat-label">Reset time</span></div>
      <div class="auth-stat"><span class="auth-stat-num">1 hr</span><span class="auth-stat-label">Link valid for</span></div>
    </div>
  </aside>

  <!-- Right -->
  <main class="auth-form-panel">
    <div class="auth-form-wrap">

      <a href="<?= BASE_URL ?>/login" class="auth-back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to login
      </a>

      <?php if ($success): ?>
        <!-- Sent state -->
        <div style="text-align:center;padding:32px 0 24px;">
          <div style="width:72px;height:72px;background:var(--sage-lt);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 22px;color:var(--sage);">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <h2 class="auth-form-title">Check your inbox</h2>
          <p style="color:var(--clay);font-size:14px;line-height:1.75;margin-bottom:28px;max-width:340px;margin-left:auto;margin-right:auto;">
            <?= htmlspecialchars($success) ?><br><br>
            The link expires in <strong>1 hour</strong>. Check your spam folder if you don't see it.
          </p>
          <a href="<?= BASE_URL ?>/login" class="btn btn-secondary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
            Return to login
          </a>
        </div>

      <?php else: ?>
        <h2 class="auth-form-title">Reset your password</h2>
        <p class="auth-form-subtitle" style="margin-bottom:28px;">We'll email you a secure reset link.</p>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <ul class="error-list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/forgot-password" method="POST" class="js-loading" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

          <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-wrap">
              <input type="email" id="email" name="email" class="form-input has-icon"
                placeholder="you@example.com" autocomplete="email" required autofocus>
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
          </div>

          <button type="submit" class="btn-submit">
            <span class="btn-text">Send Reset Link</span>
            <span class="btn-spinner" aria-hidden="true"></span>
          </button>

          <p class="auth-switch">Remembered it? <a href="<?= BASE_URL ?>/login">Sign in</a></p>
        </form>
      <?php endif; ?>

    </div>
  </main>
</div>

<script src="<?= BASE_URL ?>/Lost%20&%20Found/Lost-and-Found/public/assets/js/main.js"></script>
</body>
</html>