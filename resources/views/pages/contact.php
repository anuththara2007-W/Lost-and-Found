<?php
/**
 * C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\pages\contact.php
 */
require_once __DIR__ . '/../../../config/config.php';

$pageTitle  = 'Contact Us — Lost & Found';
$pageDesc   = 'Get in touch with the Lost & Found team. We\'re here to help.';
$activePage = 'contact';
$bodyClass  = '';
$extraCss   = [];
$extraJs    = [];

$errors  = $_SESSION['flash_errors']  ?? [];
$success = $_SESSION['flash_contact_sent'] ?? false;
$old     = $_SESSION['flash_old']     ?? [];
unset($_SESSION['flash_errors'], $_SESSION['flash_contact_sent'], $_SESSION['flash_old']);

$currentUser = $_SESSION['user'] ?? null;

include ROOT . '/resources/views/layouts/header.php';
?>

<!-- Hero -->
<div class="page-hero">
  <div class="page-hero-inner">
    <p class="page-hero-eyebrow">Get in touch</p>
    <h1 class="page-hero-title">We're here to <em>help</em></h1>
    <p style="color:var(--clay);font-size:14.5px;line-height:1.7;max-width:480px;margin:0 auto;position:relative;z-index:1;">
      Have a question or need support with a report? Reach out and we'll respond within 24 hours.
    </p>
  </div>
</div>

<!-- Contact Grid -->
<div class="container">
  <div class="contact-grid">

    <!-- Info card -->
    <div class="contact-info-card">
      <div class="contact-card-logo">Lost &amp; <em>Found</em></div>
      <h2 class="contact-card-title">Friendly support for our community</h2>
      <p class="contact-card-body">
        Our team monitors messages every day. We typically respond within a few hours during business hours.
      </p>

      <div class="contact-info-items">
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div class="contact-info-text">
            <span class="contact-info-label">Email</span>
            <span class="contact-info-value">lost&found@gmail.com</span>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div class="contact-info-text">
            <span class="contact-info-label">Response time</span>
            <span class="contact-info-value">Within 24 hours</span>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div class="contact-info-text">
            <span class="contact-info-label">Based in</span>
            <span class="contact-info-value">Sri Lanka</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Form card -->
    <div class="contact-form-card">

      <?php if ($success): ?>
        <div class="contact-success">
          <div class="contact-success-icon">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <h3 class="contact-success-title">Message sent!</h3>
          <p class="contact-success-body">Thanks for reaching out. We'll get back to you within 24 hours.</p>
        </div>

      <?php else: ?>

        <h3 class="contact-form-title">Send us a message</h3>
        <p class="contact-form-sub">Fill in the form and we'll be in touch shortly.</p>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <ul class="error-list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/contact" method="POST" class="js-loading" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

          <div class="form-row-2">
            <div class="form-group">
              <label for="contact_name" class="form-label">Your Name</label>
              <div class="input-wrap">
                <input type="text" id="contact_name" name="name" class="form-input has-icon"
                  placeholder="Jane Smith"
                  value="<?= htmlspecialchars($old['name'] ?? ($currentUser['full_name'] ?? '')) ?>" required>
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
            </div>
            <div class="form-group">
              <label for="contact_email" class="form-label">Email Address</label>
              <div class="input-wrap">
                <input type="email" id="contact_email" name="email" class="form-input has-icon"
                  placeholder="jane@example.com"
                  value="<?= htmlspecialchars($old['email'] ?? ($currentUser['email'] ?? '')) ?>" required>
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="contact_subject" class="form-label">Subject</label>
            <select id="contact_subject" name="subject" class="form-select" required>
              <option value="" disabled <?= empty($old['subject']) ? 'selected' : '' ?>>Select a topic…</option>
              <option value="lost_item"  <?= ($old['subject'] ?? '') === 'lost_item'  ? 'selected' : '' ?>>Lost item help</option>
              <option value="found_item" <?= ($old['subject'] ?? '') === 'found_item' ? 'selected' : '' ?>>Found item help</option>
              <option value="account"    <?= ($old['subject'] ?? '') === 'account'    ? 'selected' : '' ?>>Account issue</option>
              <option value="report"     <?= ($old['subject'] ?? '') === 'report'     ? 'selected' : '' ?>>Report a problem</option>
              <option value="other"      <?= ($old['subject'] ?? '') === 'other'      ? 'selected' : '' ?>>Other</option>
            </select>
          </div>

          <div class="form-group">
            <label for="contact_message" class="form-label">Message</label>
            <textarea id="contact_message" name="message" class="form-textarea"
              placeholder="Describe what you need help with…"
              maxlength="1000" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
            <p style="text-align:right;font-size:11px;color:var(--warm-mid);margin-top:4px;">
              <span data-char-count="contact_message">0</span> / 1000
            </p>
          </div>

          <button type="submit" class="btn-submit">
            <span class="btn-text">Send Message</span>
            <span class="btn-spinner" aria-hidden="true"></span>
          </button>

        </form>
      <?php endif; ?>
    </div>

  </div><!-- /.contact-grid -->
</div><!-- /.container -->

<?php include ROOT . '/resources/views/layouts/footer.php'; ?>