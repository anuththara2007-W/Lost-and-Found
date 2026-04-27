<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<!-- Link the premium auth CSS — place auth-premium.css in your public/css/ folder -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600;700&display=swap">
<style>
  main {
    max-width: none;
    width: 100%;
    padding: 0;
  }
</style>

<!-- Ambient background glows -->


<main class="auth-shell">
  <div class="auth-page">

    <!-- ── LEFT PANEL ── -->
    <div class="auth-panel">
      <div class="panel-logo">
        <div class="panel-logo-mark">L</div>
        <span class="panel-logo-text">Lost &amp; <em>Found</em></span>
      </div>

      <div class="panel-content">
        <h1 class="panel-headline">
          Reuniting<br>people with <em>things<br>that matter.</em>
        </h1>
        <p class="panel-body">
          A community-powered platform where lost items find their way home.
          Post, browse, and connect — simply and privately.
        </p>

        <div class="panel-badges">
          <span class="badge"><span class="badge-dot"></span> 24 items matched today</span>
          <span class="badge"><span class="badge-dot moss"></span> 98% privacy protected</span>
        </div>

        <div class="panel-stats">
          <div class="stat">
            <span class="stat-n">12.4k</span>
            <span class="stat-l">Items reunited</span>
          </div>
          <div class="stat">
            <span class="stat-n">840</span>
            <span class="stat-l">Active listings</span>
          </div>
          <div class="stat">
            <span class="stat-n">96%</span>
            <span class="stat-l">Success rate</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ── RIGHT PANEL (FORM) ── -->
    <div class="auth-form-panel">
      <div class="auth-form-wrap">

        <a href="<?= BASE_URL ?>" class="back-link">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          Back to home
        </a>

        <h2 class="form-title">Welcome back</h2>
        <p class="form-sub">
          Don't have an account? <a href="<?= BASE_URL ?>/auth/register">Create one free</a>
        </p>

        <!-- Error alert -->
        <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-e">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <ul class="error-list">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Success alert -->
        <?php if (isset($success)): ?>
        <div class="alert alert-s">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/login" method="POST">

          <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <div class="input-wrap">
              <svg class="i-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              <input
                type="email"
                id="email"
                name="email"
                class="form-input icon-l"
                placeholder="you@example.com"
                autocomplete="email"
                required
                autofocus
                value="<?= htmlspecialchars(old('email') ?? '') ?>"
              >
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrap">
              <svg class="i-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input
                type="password"
                id="password"
                name="password"
                class="form-input icon-l icon-r"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
              >
              <button class="btn-eye" type="button" id="eyeBtn" aria-label="Toggle password visibility">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <div class="auth-extras">
            <label class="check-label">
              <input type="checkbox" name="remember" value="1"> Keep me signed in
            </label>
            <span class="forgot">Forgot password?</span>
          </div>

          <button type="submit" class="btn-submit" id="submitBtn">
            <span class="btn-label">Sign in</span>
            <div class="spinner"></div>
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>

        </form>

        <p class="auth-switch">
          New here? <a href="<?= BASE_URL ?>/auth/register">Create a free account →</a>
        </p>

      </div>
    </div>

  </div>
</main>

<script>
// Password eye toggle
document.getElementById('eyeBtn').addEventListener('click', function () {
  const inp = document.getElementById('password');
  const show = inp.type === 'password';
  inp.type = show ? 'text' : 'password';
  this.innerHTML = show
    ? `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
    : `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>`;
});

// Loading state on submit
document.getElementById('submitBtn').closest('form').addEventListener('submit', function () {
  const btn = document.getElementById('submitBtn');
  btn.classList.add('loading');
  btn.querySelector('.btn-label').textContent = 'Signing in…';
  btn.querySelector('svg:last-child').style.display = 'none';
});
</script>

<?php
clearOld();// // Remove previous data before adding new
require_once ROOT . '/resources/views/layouts/footer.php';
?>
