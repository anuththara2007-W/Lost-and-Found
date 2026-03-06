<?php
/**
 * C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\auth\reset.php
 */
require_once __DIR__ . '/../../../config/config.php';

$token   = $_GET['token'] ?? '';
$errors  = $_SESSION['flash_errors'] ?? [];
unset($_SESSION['flash_errors']);

if (empty($token)) { header('Location: ' . BASE_URL . '/forgot-password'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — Lost &amp; Found</title>
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
  </div>
</header>

<div class="auth-page">
  <aside class="auth-panel">
    <a href="<?= BASE_URL ?>/" class="auth-panel-logo">Lost &amp; <em>Found</em></a>
    <div class="auth-panel-content">
      <h1 class="auth-panel-headline">
        Create a<br>strong new<br><em>password.</em>
      </h1>
      <p class="auth-panel-body">Choose a password that's difficult to guess and unique to this account.</p>
      <ul class="auth-benefits" style="margin-top:28px;">
        <li class="benefit-row"><span class="benefit-icon">🔠</span><span class="benefit-text">Mix uppercase and lowercase letters</span></li>
        <li class="benefit-row"><span class="benefit-icon">🔢</span><span class="benefit-text">Include at least one number</span></li>
        <li class="benefit-row"><span class="benefit-icon">✨</span><span class="benefit-text">Add a special character (! @ # $ %)</span></li>
        <li class="benefit-row"><span class="benefit-icon">📏</span><span class="benefit-text">Minimum 8 characters</span></li>
      </ul>
    </div>
  </aside>

  <main class="auth-form-panel">
    <div class="auth-form-wrap">

      <a href="<?= BASE_URL ?>/forgot-password" class="auth-back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back
      </a>

      <h2 class="auth-form-title">Set new password</h2>
      <p class="auth-form-subtitle" style="margin-bottom:28px;">Enter and confirm your new password below.</p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <ul class="error-list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
        </div>
      <?php endif; ?>

      <form action="<?= BASE_URL ?>/reset-password" method="POST" class="js-loading" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="form-group">
          <label for="password" class="form-label">New Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" class="form-input has-icon"
              placeholder="At least 8 characters"
              autocomplete="new-password" required minlength="8" autofocus>
            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <button type="button" class="btn-eye" data-target="password" aria-label="Show password">
              <svg class="icon-show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg class="icon-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          <div class="strength-bar-wrap">
            <div class="strength-bar"><div class="strength-fill"></div></div>
            <span class="strength-label" aria-live="polite"></span>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password" class="form-label">Confirm New Password</label>
          <div class="input-wrap">
            <input type="password" id="confirm_password" name="confirm_password" class="form-input has-icon"
              placeholder="Repeat your new password"
              autocomplete="new-password" required>
            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <button type="button" class="btn-eye" data-target="confirm_password" aria-label="Show password">
              <svg class="icon-show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg class="icon-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          <span id="confirm-msg" class="field-msg" aria-live="polite"></span>
        </div>

        <button type="submit" class="btn-submit">
          <span class="btn-text">Update Password</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>

      </form>
    </div>
  </main>
</div>

<script src="<?= BASE_URL ?>/Lost%20&%20Found/Lost-and-Found/public/assets/js/main.js"></script>
</body>
</html>