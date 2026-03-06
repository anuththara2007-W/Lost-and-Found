<?php
/**
 * C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\auth\register.php
 */
require_once __DIR__ . '/../../../config/config.php';

if (!empty($_SESSION['user'])) { header('Location: ' . BASE_URL . '/'); exit; }

$pageTitle = 'Create Account — Lost & Found';
$errors    = $_SESSION['flash_errors'] ?? [];
$old       = $_SESSION['flash_old']    ?? [];
unset($_SESSION['flash_errors'], $_SESSION['flash_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <?php require __DIR__ . '/../layouts/header.php'; ?>

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/register.css">
</head>
<body class="page-auth" style="background:var(--off-white);">



<div class="auth-page">

  <!-- Left panel -->
  <aside class="auth-panel">
    <a href="<?= BASE_URL ?>/" class="auth-panel-logo">Lost &amp; <em>Found</em></a>
    <div class="auth-panel-content">
      <h1 class="auth-panel-headline">
        Join the<br>community<br><em>making a difference.</em>
      </h1>
      <p class="auth-panel-body">
        Help reunite people with their lost belongings. Free to join, easy to use, trusted by thousands.
      </p>
      <ul class="auth-benefits">
        <li class="benefit-row"><span class="benefit-icon">📢</span><span class="benefit-text">Post lost or found items instantly</span></li>
        <li class="benefit-row"><span class="benefit-icon">🔔</span><span class="benefit-text">Get notified when matches are found</span></li>
        <li class="benefit-row"><span class="benefit-icon">💬</span><span class="benefit-text">Message other users securely</span></li>
        <li class="benefit-row"><span class="benefit-icon">🏅</span><span class="benefit-text">Earn trust badges for helping others</span></li>
      </ul>
    </div>
  </aside>

  <!-- Right form panel -->
  <main class="auth-form-panel" style="align-items:flex-start;padding-top:40px;">
    <div class="auth-form-wrap" style="max-width:480px;">

      <a href="<?= BASE_URL ?>/" class="auth-back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to home
      </a>

      <h2 class="auth-form-title">Create your account</h2>
      <p class="auth-form-subtitle">Already have one? <a href="<?= BASE_URL ?>/login">Sign in</a></p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <ul class="error-list">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="<?= BASE_URL ?>/register" method="POST" enctype="multipart/form-data" class="js-loading" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <!-- Avatar -->
        <div class="avatar-upload-row">
          <div class="avatar-preview">
            <img id="avatar-preview-img" src="" alt="" style="display:none;">
            <span class="avatar-placeholder">👤</span>
          </div>
          <div>
            <label for="profile_image" class="btn-upload-avatar">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
              Upload photo
            </label>
            <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/webp" style="display:none;">
            <p class="avatar-hint">JPG, PNG, WebP · max 2 MB</p>
          </div>
        </div>

        <!-- Full name + Username -->
        <div class="form-row-2">
          <div class="form-group">
            <label for="full_name" class="form-label">Full Name</label>
            <div class="input-wrap">
              <input type="text" id="full_name" name="full_name" class="form-input has-icon"
                placeholder="Jane Smith"
                value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                autocomplete="name" required>
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div class="input-wrap">
              <input type="text" id="username" name="username" class="form-input has-icon"
                placeholder="jane_smith"
                value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                autocomplete="username" required minlength="3" maxlength="50">
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <span id="username-msg" class="field-msg" aria-live="polite"></span>
          </div>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email" class="form-label">Email Address</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" class="form-input has-icon"
              placeholder="jane@example.com"
              value="<?= htmlspecialchars($old['email'] ?? '') ?>"
              autocomplete="email" required>
            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <span id="email-msg" class="field-msg" aria-live="polite"></span>
        </div>

        <!-- Phone (optional) -->
        <div class="form-group">
          <label for="phone" class="form-label">Phone Number <span class="optional">(optional)</span></label>
          <div class="input-wrap">
            <input type="tel" id="phone" name="phone" class="form-input has-icon"
              placeholder="+65 9123 4567"
              value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
              autocomplete="tel">
            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13 19.79 19.79 0 0 1 1.61 4.38 2 2 0 0 1 3.59 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l1.07-1.07a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" class="form-input has-icon"
              placeholder="At least 8 characters"
              autocomplete="new-password" required minlength="8">
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

        <!-- Confirm password -->
        <div class="form-group">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <div class="input-wrap">
            <input type="password" id="confirm_password" name="confirm_password" class="form-input has-icon"
              placeholder="Repeat your password"
              autocomplete="new-password" required>
            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <button type="button" class="btn-eye" data-target="confirm_password" aria-label="Show password">
              <svg class="icon-show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg class="icon-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          <span id="confirm-msg" class="field-msg" aria-live="polite"></span>
        </div>

        <!-- Terms -->
        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" name="terms" id="terms" required>
            I agree to the
            <a href="<?= BASE_URL ?>/legal/terms" target="_blank" style="color:var(--terracotta);">Terms of Service</a>
            and
            <a href="<?= BASE_URL ?>/legal/privacy" target="_blank" style="color:var(--terracotta);">Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="btn-submit">
          <span class="btn-text">Create Account</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>

        <p class="auth-switch">
          Already have an account? <a href="<?= BASE_URL ?>/login">Sign in</a>
        </p>

      </form>
    </div>
  </main>
</div>

<script src="<?= BASE_URL ?>/Lost%20&%20Found/Lost-and-Found/public/assets/js/main.js"></script>

    <?php require __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>