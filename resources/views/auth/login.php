<?php
/**
 * resources/views/auth/login.php
 */
require_once __DIR__ . '/../../../config/config.php';

if (!empty($_SESSION['user'])) { header('Location: ' . BASE_URL . '/'); exit; }

$pageTitle = 'Sign In — Lost & Found';

$errors  = $_SESSION['flash_errors']  ?? [];
$success = $_SESSION['flash_success'] ?? null;
$old     = $_SESSION['flash_old']     ?? [];

unset($_SESSION['flash_errors'], $_SESSION['flash_success'], $_SESSION['flash_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <?php require __DIR__ . '/../layouts/header.php'; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- ONLY this CSS. No duplicates. -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/login.css">
</head>

<body class="page-auth">


  <main class="auth-shell">
    <div class="auth-page">

      <!-- Left panel -->
      <aside class="auth-panel">
        <a href="<?= BASE_URL ?>/" class="auth-panel-logo">Lost &amp; <em>Found</em></a>

        <div class="auth-panel-content">
          <h1 class="auth-panel-headline">
            Reuniting<br>people with<br><em>what matters.</em>
          </h1>

          <p class="auth-panel-body">
            A trusted community platform for reporting and recovering lost and found items across the city.
          </p>
        </div>

        <div class="auth-panel-stats">
          <div class="auth-stat">
            <span class="auth-stat-num">100+</span>
            <span class="auth-stat-label">Items reunited</span>
          </div>
          <div class="auth-stat">
            <span class="auth-stat-num">1k</span>
            <span class="auth-stat-label">Active users</span>
          </div>
          <div class="auth-stat">
            <span class="auth-stat-num">92%</span>
            <span class="auth-stat-label">Recovery rate</span>
          </div>
        </div>
      </aside>

      <!-- Right form panel -->
      <section class="auth-form-panel">
        <div class="auth-form-wrap">

          <a href="<?= BASE_URL ?>/" class="auth-back-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <polyline points="15 18 9 12 15 6"/>
            </svg>
            Back to home
          </a>

          <h2 class="auth-form-title">Welcome back</h2>
          <p class="auth-form-subtitle">
            No account yet? <a href="<?= BASE_URL ?>/register">Create one free</a>
          </p>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-error" role="alert">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              <ul class="error-list">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <?= htmlspecialchars($success) ?>
            </div>
          <?php endif; ?>

          <form action="<?= BASE_URL ?>/login" method="POST" class="js-loading" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="form-group">
              <label for="identifier" class="form-label">Email or Username</label>
              <div class="input-wrap">
                <input
                  type="text"
                  id="identifier"
                  name="identifier"
                  class="form-input has-icon"
                  placeholder="you@example.com"
                  value="<?= htmlspecialchars($old['identifier'] ?? '') ?>"
                  autocomplete="username"
                  required
                >
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
            </div>

            <div class="form-group">
              <label for="password" class="form-label">Password</label>
              <div class="input-wrap">
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-input has-icon"
                  placeholder="Your password"
                  autocomplete="current-password"
                  required
                >
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <rect x="3" y="11" width="18" height="11" rx="2"/>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>

                <button type="button" class="btn-eye" data-target="password" aria-label="Show password">
                  <svg class="icon-show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  <svg class="icon-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="auth-extras">
              <label class="checkbox-label">
                <input type="checkbox" name="remember" <?= !empty($old['remember']) ? 'checked' : '' ?>>
                Remember me
              </label>
              <a href="<?= BASE_URL ?>/forgot-password" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit">
              <span class="btn-text">Sign In</span>
              <span class="btn-spinner" aria-hidden="true"></span>
            </button>

            <p class="auth-switch">
              Don't have an account? <a href="<?= BASE_URL ?>/resources/views/auth/register.php">Sign up — it's free</a>
            </p>
          </form>
        </div>
      </section>

    </div>
  </main>

  <?php require __DIR__ . '/../layouts/footer.php'; ?>

  <script src="<?= BASE_URL ?>/public/assets/js/main.js"></script>
</body>
</html>