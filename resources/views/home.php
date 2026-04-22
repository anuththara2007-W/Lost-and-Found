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

   <!-- CATEGORIES-->
  <section class="categories-section bg-light">
    <h2 class="section-header">Asset Classification Taxonomy</h2>
    <p class="section-description">
      To ensure optimal searchability and rapid database querying, items are organized into strict
      taxonomic categories. Accurate categorization is the highest predictor of a successful reunion.
    </p>

    <div class="categories-grid">
      <div class="category-card">
        <h4>Biometric &amp; Identifications</h4>
        <p>Passports, national ID cards, driver's licenses, and employee access badges. These items require expedited handling due to identity theft vectors.</p>
      </div>
      <div class="category-card">
        <h4>Consumer Electronics</h4>
        <p>Laptops, smartphones, smartwatches, and headphones. High financial value items that often contain sensitive, unencrypted personal data.</p>
      </div>
      <div class="category-card">
        <h4>Financial Instruments</h4>
        <p>Physical wallets, credit cards, checkbooks, and significant sums of cash. Requires immediate reporting to preempt fraudulent transactions.</p>
      </div>
      <div class="category-card">
        <h4>Keys &amp; Access Tokens</h4>
        <p>Vehicle fobs, residential keys, and physical security tokens. High inconvenience factor items that disrupt daily logistical operations.</p>
      </div>
      <div class="category-card">
        <h4>Sentimental &amp; Biological</h4>
        <p>Heirlooms, specialized medical equipment, prescription eyewear, and companion animals. Items possessing immense subjective value to the original owner.</p>
      </div>
      <div class="category-card">
        <h4>Transit &amp; Luggage</h4>
        <p>Backpacks, checked baggage, briefcases, and gym bags. Often displaced during high-friction transitional states like commuting or traveling.</p>
      </div>
    </div>
  </section>

  <!-- ANALYTICS -->
  <section class="analytics-section">
    <h2 class="section-header">Geospatial Intelligence &amp; Predictive Analytics</h2>
    <p class="section-description">
      We are moving beyond a simple reactive bulletin board to become an infrastructural diagnostic
      tool. By aggregating thousands of data points, we can map the topography of human error.
    </p>

    <div class="analytics-content">
      <p class="pillar-text">
        As our dataset grows, the predictive capabilities of the platform expand exponentially.
        Currently, we operate on a reactive model—a user loses an item, a user posts an item.
        However, aggressively clustering geographic data over long timelines allows us to identify
        and map 'loss hotspots.'
      </p>
      <p class="pillar-text">
        Imagine an administrative dashboard that clearly visualizes that 40% of all lost biometric
        identification cards within a specific university campus occur within a precise 50-meter
        radius of a specific dining hall turnstile. Facility managers can use this anonymized,
        aggregated geospatial data to alter physical traffic flows, add targeted warning signage,
        or reposition physical security desks.
      </p>
    </div>
  </section>

  <!--PSYCHOLOGY -->
  <section class="psychology-section bg-light"> //section
    <div class="psychology-grid">

      <div>
        <h3 class="pillar-title title-terracotta">The Psychology of Loss</h3>
        <p class="pillar-text">
          Losing an item of value—whether that value is deeply sentimental or purely
          financial—triggers a psychological response akin to grief. The sudden disruption of our
          spatial memory, the immediate surge of cortisol, and the ensuing frantic search are
          universal human experiences.
        </p>
        <p class="pillar-text">
          Our platform was engineered to disrupt this panic spiral. By creating an immediate,
          digital manifestation of physical topography, we provide a centralized mechanism for
          regaining that control. Knowing that a system exists to catch what falls through the
          cracks of daily life fundamentally alters how we navigate public spaces.
        </p>
        <p class="pillar-text">
          When you file a report, you aren't just screaming into the void. You are establishing a
          verifiable anchor point on an interactive ledger, significantly shifting the statistical
          probability of recovery from near-zero to highly probable within the first 48 hours.
        </p>
      </div>

      <div>
        <h3 class="pillar-title title-sage">The Anatomy of Recovery</h3>
        <p class="pillar-text">
          What happens when a 'Found' report intersects with a 'Lost' inquiry? The anatomy of a
          successful recovery relies heavily on precision and friction reduction. In traditional
          legacy systems, the gap between finding an item and locating its owner is obstructed by
          bureaucratic inefficiencies and a lack of standardized communication channels.
        </p>
        <p class="pillar-text">
          We utilize high-fidelity categorization algorithms combined with precise Leaflet-powered
          geographic clustering. When you drop a pin indicating where you left your laptop, and
          someone else drops a pin indicating where they secured a laptop, the spatial narrowing
          becomes the key catalyst.
        </p>
        <p class="pillar-text">
          Furthermore, our integration of deep-linked communication pipelines ensures that
          correspondence remains fluid, direct, and instantaneous. When time is critical—especially
          concerning identifying documents or high-value electronics—removing intermediary layers
          proves to be the definitive factor in successful reunification.
        </p>
      </div>

    </div>
  </section>

  <!-- FRAMEWORK -->
  <section class="framework-section">
    <div class="framework-container">
      <h2 class="framework-header">Community Trust Frameworks &amp; Gamification Protocols</h2>

      <div class="framework-card">
        <h4 class="framework-card-title">Reputational Dynamics</h4>
        <p class="framework-card-text">
          A decentralized recovery network is entirely dependent on the integrity of its nodes—the
          users. To foster an environment of high trust, we have implemented a structured
          reputational dynamic centered around authenticated 'Finder Badges.'
        </p>
        <p class="framework-card-text">
          When a user consistently demonstrates altruistic behavior by reporting found high-value
          items and successfully walking through the return protocol, their profile metrics reflect
          this reliability. System administrators manually verify these return chains and issue
          Bronze, Silver, or Gold tier badges.
        </p>
        <p class="framework-card-text">
          If you are arranging to meet someone to recover a $2,000 piece of equipment, seeing a
          Gold Finder Badge next to their username significantly reduces anxiety. This creates a
          positive feedback loop where good behavior is structurally incentivized.
        </p>
      </div>

      <div class="framework-card">
        <h4 class="framework-card-title">Data Privacy &amp; Information Masking</h4>
        <p class="framework-card-text">
          Navigating the intersection of open-source community help and stringent personal data
          privacy requires a delicate architectural balance. We require users to provide enough
          detail to make a recovery possible, but we purposefully obfuscate sensitive vectors until
          a mutual connection is established.
        </p>
        <p class="framework-card-text">
          Our internal messaging system acts as a secure airlock. Users can exchange photographs,
          discuss specific identifying serial numbers, and negotiate meeting places without ever
          exposing their personal email addresses or phone numbers to the public ledger.
        </p>
        <p class="framework-card-text">
          Optional external integrations utilize intent-based routing. A user must deliberately
          consent to expose alternative contact methods. This opt-in methodology ensures that
          power-users can achieve frictionless communication while casual users are never pressured
          to leave the secure sandbox.
        </p>
      </div>
    </div>
  </section>

  <!-- SAFETY -->
  <section class="safety-section">
    <h2 class="section-header">Physical Hand-off Operational Guidelines</h2>
    <p class="section-description">
      While digital coordination occurs within a secure environment, the final step of the recovery
      lifecycle occurs in the physical world. We mandate adherence to the following operational
      guidelines to ensure the absolute safety of all parties.
    </p>
    <div class="safety-grid">
      <ul class="safety-list">
        <li>
          <strong>Public Intersections:</strong>
          All physical exchanges must occur in highly trafficked, well-lit public spaces. Coffee
          shops, library atriums, or designated municipal safe exchange zones (such as police
          station lobbies) are heavily recommended.
        </li>
        <li>
          <strong>Verification Before Meeting:</strong>
          The "Finder" should withhold one distinct, identifying feature of the item from their
          public listing. The "Loser" must accurately describe this withheld feature via secure
          message prior to finalizing a meeting location.
        </li>
        <li>
          <strong>Daylight Operations:</strong>
          Whenever possible, schedule the asset recovery exchange during daylight hours. If an
          evening exchange is unavoidable, restrict locations to indoor commercial establishments
          with active security personnel.
        </li>
        <li>
          <strong>Third-Party Presence:</strong>
          Users are encouraged to bring a companion to the exchange. If attending alone, inform a
          trusted external contact of the meeting location, time, and the username of the
          individual you are meeting.
        </li>
      </ul>
    </div>
  </section>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
