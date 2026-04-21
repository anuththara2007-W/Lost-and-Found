<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">

<div class="home-container">
//hello
  <section
    class="hero-section"
    style="
      background-image:      url('<?= BASE_URL ?>/uploads/lostfoundimg.jpg');
      background-repeat:     no-repeat;
      background-size:       cover;
      background-position:   center;
    "
  >
    <div class="hero-overlay"></div>
    <h1 class="hero-title">Lost &amp; <em>Found</em></h1>
    <p class="hero-subtitle">
      A premium community platform for reporting lost belongings, mapping geographic displacements,
      and finding rightful owners through secure, frictionless verification.
    </p>
    <div class="hero-actions">
      <a href="<?= BASE_URL ?>/item/create?type=lost"  class="btn btn-primary">I Lost Something</a>
      <a href="<?= BASE_URL ?>/item/create?type=found" class="btn btn-found">I Found Something</a>
    </div>
  </section>

  <!-- RECENT ITEMS -->
  <section class="recent-section">
    <h2 class="section-header">Recently Reported Items</h2>

    <?php if (empty($recentItems)): ?>
      <div class="empty-state">
        No items have been reported yet. Be the first to start the community!
      </div>
    <?php else: ?>
      <div class="items-grid">
        <?php foreach ($recentItems as $item): ?>
          <div class="preview-card" onclick="window.location.href='<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>'">

            <?php if ($item['type'] === 'lost'): ?>
              <span class="card-badge badge-lost"><span class="badge-dot"></span> Lost</span>
            <?php else: ?>
              <span class="card-badge badge-found"><span class="badge-dot"></span> Found</span>
            <?php endif; ?>

            <div
              class="card-img"
              <?php if (!empty($item['image_path'])): ?>
                style="background: url('<?= BASE_URL ?>/uploads/<?= htmlspecialchars($item['image_path']) ?>') center/cover no-repeat;"
              <?php endif; ?>
            >
              <?php if (empty($item['image_path'])): ?> [ NO PHOTO ] <?php endif; ?>
            </div>

            <h3 class="card-title"><?= escape($item['title']) ?></h3>
            <div class="card-date"><?= formatDate($item['date_posted']) ?></div>
            <div class="card-location">📍 <?= escape($item['location']) ?></div>

            <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-secondary w-full">View Details</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="browse-more">
      <a href="<?= BASE_URL ?>/item/index" class="btn btn-secondary">Browse All Items</a>
    </div>
  </section>
