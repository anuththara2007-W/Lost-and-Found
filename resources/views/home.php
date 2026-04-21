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

  <!-- LIFECYCLE -->
  <section class="lifecycle-section">
    <h2 class="section-header">The Recovery Lifecycle</h2>
    <p class="section-description">
      Our platform operates on a streamlined, three-phase operational matrix designed to minimize
      the time between an item's displacement and its successful recovery.
    </p>

    <div class="lifecycle-grid">
      <div class="lifecycle-step">
        <div class="step-number">01</div>
        <h4 class="step-title">Geospatial Logging</h4>
        <p class="step-text">
          Whether reporting a loss or a discovery, the first step involves generating a standardized
          geographic record. Users input the item's specifications, temporal data (when it was
          lost/found), and drop a highly precise coordinate pin on our integrated map. This
          initializes the matching algorithm.
        </p>
      </div>
      <div class="lifecycle-step">
        <div class="step-number">02</div>
        <h4 class="step-title">Algorithmic Synthesis</h4>
        <p class="step-text">
          Our backend systems actively scan new database entries against existing records. We utilize
          fuzzy logic to cross-reference item descriptions, while calculating the radial distance
          between 'Lost' and 'Found' pins. If a high-probability match occurs within a specified
          geo-fence, both parties are instantly notified.
        </p>
      </div>
      <div class="lifecycle-step">
        <div class="step-number">03</div>
        <h4 class="step-title">Secure Verification</h4>
        <p class="step-text">
          Once a potential match is established, the platform facilitates a blind communication
          protocol. The alleged owner must answer specific, undisclosed verification questions set
          by the finder (e.g., "What is the lock screen wallpaper?"). Only upon successful
          verification is the physical hand-off coordinated.
        </p>
      </div>
    </div>
  </section>
