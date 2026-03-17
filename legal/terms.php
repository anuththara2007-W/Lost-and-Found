<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terms of Service — Lost &amp; Found</title>
  <link rel="stylesheet" href="/public/assets/css/login.css">
  <style>

    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&family=DM+Mono:wght@400&display=swap');

    :root {
      --white:         #FAFAF8;
      --off-white:     #F2F0EB;
      --parchment:     #E8E3D8;
      --warm-mid:      #C9C0AE;
      --clay:          #9C8C78;
      --ink:           #3A3830;
      --midnight:      #1E2027;
      --terracotta:    #C96442;
      --terracotta-dk: #A84F32;
      --terracotta-lt: #EDD5C8;
      --sage:          #5C7A65;
      --sage-lt:       #D1DFCF;
      --amber:         #D4940A;
      --amber-lt:      #F5E9C8;
      --danger:        #B94040;
      --font-display:  'Cormorant Garamond', Georgia, serif;
      --font-body:     'DM Sans', sans-serif;
      --font-mono:     'DM Mono', monospace;
      --r-sm:  8px;
      --r-lg:  16px;
      --t:     150ms ease;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      background: var(--white);
      color: var(--ink);
      font-family: var(--font-body);
      font-size: 15px;
      line-height: 1.65;
      -webkit-font-smoothing: antialiased;
    }

    /* ── Hero ───────────────────────────────────── */

    .legal-hero {
      border-bottom: 1px solid var(--parchment);
      padding: 60px 48px 52px;
      position: relative;
      overflow: hidden;
    }

    .legal-hero::before {
      content: '';
      position: absolute;
      right: 56px;
      top: 50%;
      transform: translateY(-50%);
      width: 108px;
      height: 108px;
      border: 1px solid var(--parchment);
      border-radius: 50%;
    }

    .legal-hero::after {
      content: '';
      position: absolute;
      right: 78px;
      top: 50%;
      transform: translateY(-50%);
      width: 64px;
      height: 64px;
      border: 1px solid var(--parchment);
      border-radius: 50%;
    }

    .legal-hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--clay);
      margin-bottom: 18px;
    }

    .legal-hero-eyebrow::before {
      content: '';
      width: 28px;
      height: 1px;
      background: var(--warm-mid);
      flex-shrink: 0;
    }

    .legal-hero-title {
      font-family: var(--font-display);
      font-size: clamp(2.2rem, 4vw, 3.2rem);
      font-weight: 300;
      color: var(--midnight);
      line-height: 1.1;
      letter-spacing: -0.4px;
      margin-bottom: 18px;
      max-width: 500px;
    }

    .legal-hero-title em {
      font-style: italic;
      color: var(--terracotta);
    }

    .legal-hero-meta {
      font-size: 12px;
      color: var(--clay);
    }

    /* ── Container ──────────────────────────────── */

    .legal-layout {
      display: grid;
      grid-template-columns: 200px 1fr;
      max-width: 1040px;
      margin: 36px auto 0;
      border: 1px solid var(--parchment);
      border-radius: var(--r-lg);
      overflow: hidden;
    }

    /* ── Sidebar ────────────────────────────────── */

    .legal-toc {
      padding: 40px 0;
      border-right: 1px solid var(--parchment);
      position: sticky;
      top: 20px;
      height: fit-content;
      align-self: start;
    }

    .toc-label {
      font-size: 9px;
      font-weight: 600;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--warm-mid);
      padding: 0 20px;
      margin-bottom: 14px;
    }

    .toc-list {
      list-style: none;
    }

    .toc-list a {
      display: block;
      font-size: 12px;
      color: var(--clay);
      text-decoration: none;
      padding: 7px 20px;
      border-left: 2px solid transparent;
      line-height: 1.45;
      transition: color var(--t), border-color var(--t), background var(--t);
    }

    .toc-list a:hover {
      color: var(--ink);
      background: var(--off-white);
    }

    .toc-list a.active {
      color: var(--terracotta);
      border-left-color: var(--terracotta);
      background: var(--terracotta-lt);
      font-weight: 500;
    }

    /* ── Content ────────────────────────────────── */

    .legal-content {
      padding: 48px 52px 64px;
      counter-reset: section;
    }

    .legal-content h2 {
      font-family: var(--font-display);
      font-size: 1.65rem;
      font-weight: 400;
      color: var(--midnight);
      margin: 48px 0 6px;
      line-height: 1.2;
      scroll-margin-top: 24px;
    }

    .legal-content h2:first-of-type { margin-top: 0; }

    .legal-content h2::before {
      counter-increment: section;
      content: counter(section, decimal-leading-zero) " —";
      display: block;
      font-family: var(--font-mono);
      font-size: 10px;
      color: var(--warm-mid);
      letter-spacing: 1.5px;
      margin-bottom: 4px;
    }

    .legal-divider {
      height: 1px;
      background: var(--parchment);
      margin: 10px 0 18px;
      max-width: 640px;
    }

    .legal-content p {
      font-size: 14px;
      line-height: 1.85;
      color: var(--ink);
      margin-bottom: 16px;
      max-width: 640px;
    }

    .legal-content ul {
      list-style: none;
      margin: 0 0 18px;
    }

    .legal-content li {
      font-size: 14px;
      line-height: 1.8;
      color: var(--ink);
      margin-bottom: 6px;
      padding-left: 20px;
      position: relative;
      max-width: 640px;
    }

    .legal-content li::before {
      content: '—';
      position: absolute;
      left: 0;
      color: var(--warm-mid);
      font-size: 12px;
      line-height: 1.95;
    }

    .legal-content li strong { color: var(--midnight); font-weight: 500; }

    .legal-content a {
      color: var(--terracotta);
      text-decoration: underline;
      text-decoration-thickness: 1px;
      text-underline-offset: 3px;
      transition: color var(--t);
    }

    .legal-content a:hover { color: var(--terracotta-dk); }

    /* ── Callout boxes ──────────────────────────── */

    .legal-highlight-box {
      background: var(--amber-lt);
      border: 1px solid #E0CC90;
      border-left: 2px solid var(--amber);
      border-radius: 0 var(--r-sm) var(--r-sm) 0;
      padding: 16px 20px;
      margin-bottom: 40px;
      max-width: 640px;
    }

    .legal-highlight-box p {
      margin: 0;
      font-size: 13px;
      color: #7A5800;
      max-width: none;
    }

    .legal-highlight-box strong { color: #5A4000; font-weight: 500; }

    .legal-safety-box {
      background: var(--sage-lt);
      border: 1px solid #B0C8B4;
      border-left: 2px solid var(--sage);
      border-radius: 0 var(--r-sm) var(--r-sm) 0;
      padding: 14px 18px;
      margin: 14px 0 18px;
      max-width: 640px;
    }

    .legal-safety-box p {
      margin: 0;
      font-size: 13px;
      color: #3A5C42;
      max-width: none;
    }

    /* ── Prohibited list ────────────────────────── */

    .forbidden-list li::before {
      content: '✕';
      color: var(--danger);
      font-size: 10px;
      line-height: 2.2;
    }

    /* ── Footer ─────────────────────────────────── */

    .legal-footer {
      max-width: 1040px;
      margin: 0 auto 60px;
      background: var(--off-white);
      border: 1px solid var(--parchment);
      border-top: none;
      border-radius: 0 0 var(--r-lg) var(--r-lg);
      padding: 20px 52px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
    }

    .legal-footer-copy {
      font-size: 11px;
      color: var(--clay);
    }

    .legal-footer-links {
      display: flex;
      gap: 20px;
      list-style: none;
    }

    .legal-footer-links a {
      font-size: 11px;
      color: var(--clay);
      text-decoration: none;
      transition: color var(--t);
    }

    .legal-footer-links a:hover { color: var(--terracotta); }

    /* ── Responsive ─────────────────────────────── */

    @media (max-width: 900px) {
      .legal-hero          { padding: 40px 24px 36px; }
      .legal-hero::before,
      .legal-hero::after   { display: none; }
      .legal-layout        { grid-template-columns: 1fr; margin: 20px 16px 0; }
      .legal-toc           { display: none; }
      .legal-content       { padding: 32px 24px 48px; }
      .legal-footer        { margin: 0 16px 48px; padding: 18px 24px; }
    }

    @media (max-width: 500px) {
      .legal-hero-title { font-size: 2rem; }
    }

  </style>
</head>
<body>

<!-- Hero -->
<div class="legal-hero">
  <p class="legal-hero-eyebrow">Legal</p>
  <h1 class="legal-hero-title">Terms of <em>Service</em></h1>
  <p class="legal-hero-meta">Last updated: <?= date('F j, Y') ?> &nbsp;·&nbsp; Effective: January 1, 2024</p>
</div>

<!-- Container -->
<div class="legal-layout">

  <!-- Sidebar -->
  <aside class="legal-toc">
    <p class="toc-label">Contents</p>
    <ul class="toc-list">
      <li><a href="#acceptance" class="active">Acceptance</a></li>
      <li><a href="#eligibility">Eligibility</a></li>
      <li><a href="#account">Your Account</a></li>
      <li><a href="#use">Acceptable Use</a></li>
      <li><a href="#prohibited">Prohibited Conduct</a></li>
      <li><a href="#content">User Content</a></li>
      <li><a href="#items">Item Reports</a></li>
      <li><a href="#liability">Limitation of Liability</a></li>
      <li><a href="#termination">Termination</a></li>
      <li><a href="#disputes">Disputes</a></li>
      <li><a href="#changes">Changes</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </aside>

  <!-- Content -->
  <article class="legal-content">

    <div class="legal-highlight-box">
      <p><strong>Please read carefully.</strong> By creating an account or using our services, you agree to be bound by these Terms of Service.</p>
    </div>

    <h2 id="acceptance">Acceptance of Terms</h2>
    <div class="legal-divider"></div>
    <p>These Terms of Service ("Terms") govern your access to and use of Lost &amp; Found ("we", "us", "our"). By registering for an account, posting content, or otherwise using our platform, you agree to these Terms. If you do not agree, do not use our services.</p>

    <h2 id="eligibility">Eligibility</h2>
    <div class="legal-divider"></div>
    <p>You must be at least 13 years of age to create an account. By using our services, you represent that:</p>
    <ul>
      <li>You are at least 13 years old.</li>
      <li>You have the legal capacity to enter into a binding agreement.</li>
      <li>You are not prohibited from using our services under applicable law.</li>
    </ul>

    <h2 id="account">Your Account</h2>
    <div class="legal-divider"></div>
    <p>You are responsible for:</p>
    <ul>
      <li>Maintaining the confidentiality of your login credentials.</li>
      <li>All activity that occurs under your account.</li>
      <li>Providing accurate, current, and complete registration information.</li>
      <li>Promptly notifying us if you suspect unauthorised access to your account.</li>
    </ul>
    <p>You may not create more than one account, transfer your account to another person, or use another person's account without their permission.</p>

    <h2 id="use">Acceptable Use</h2>
    <div class="legal-divider"></div>
    <p>You agree to use Lost &amp; Found only for its intended purpose: reporting and helping to recover genuinely lost or found items. You agree to:</p>
    <ul>
      <li>Post only accurate and truthful information.</li>
      <li>Communicate with other users respectfully and in good faith.</li>
      <li>Keep your contact details up to date so you can be reached about reports.</li>
      <li>Mark reports as resolved once the item is reunited with its owner.</li>
    </ul>

    <h2 id="prohibited">Prohibited Conduct</h2>
    <div class="legal-divider"></div>
    <p>You must not:</p>
    <ul class="forbidden-list">
      <li>Post false, misleading, or fraudulent item reports.</li>
      <li>Attempt to claim items you do not own.</li>
      <li>Use the platform for any commercial purpose or advertising.</li>
      <li>Harass, threaten, or abuse other users.</li>
      <li>Post spam, unsolicited messages, or phishing content.</li>
      <li>Attempt to access another user's account or data without permission.</li>
      <li>Reverse engineer, scrape, or extract data from our platform in bulk.</li>
      <li>Use automated bots or scripts to interact with the service.</li>
      <li>Post content that infringes intellectual property rights.</li>
      <li>Use the platform for any activity that is illegal under applicable law.</li>
    </ul>
    <p>Violations may result in account suspension or permanent termination at our sole discretion.</p>

    <h2 id="content">User Content</h2>
    <div class="legal-divider"></div>
    <p>You retain ownership of content you post (item descriptions, photos, messages). By posting, you grant us a non-exclusive, worldwide, royalty-free licence to store, display, and transmit that content solely for the purpose of operating the platform.</p>
    <p>You are solely responsible for the content you post. We do not pre-screen user content but reserve the right to remove any content that violates these Terms or is otherwise objectionable.</p>

    <h2 id="items">Item Reports &amp; Reunification</h2>
    <div class="legal-divider"></div>
    <p>Lost &amp; Found is a communication platform only. We do not verify the identity of users, the ownership of items, or the accuracy of reports. We are not responsible for:</p>
    <ul>
      <li>The return, loss, or damage of any item.</li>
      <li>Any transactions, arrangements, or disputes between users.</li>
      <li>Any harm arising from meetings or exchanges arranged through the platform.</li>
    </ul>
    <div class="legal-safety-box">
      <p>We strongly recommend exercising caution when meeting strangers. Always meet in a public place and bring a friend if possible.</p>
    </div>

    <h2 id="liability">Limitation of Liability</h2>
    <div class="legal-divider"></div>
    <p>To the maximum extent permitted by applicable law, Lost &amp; Found and its operators shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the platform, including loss of data, items, or property.</p>
    <p>Our total liability to you for any claim arising from these Terms or your use of the platform shall not exceed LKR 10,000 or the amount you paid to us in the 12 months preceding the claim, whichever is greater.</p>

    <h2 id="termination">Account Termination</h2>
    <div class="legal-divider"></div>
    <p>You may delete your account at any time from your account settings. We may suspend or terminate your account at any time if you violate these Terms, engage in fraudulent activity, or if we decide to discontinue the service.</p>
    <p>Upon termination, your right to use the platform ceases immediately. Provisions that by their nature should survive termination (including Sections 6, 8, and 10) will remain in effect.</p>

    <h2 id="disputes">Governing Law &amp; Disputes</h2>
    <div class="legal-divider"></div>
    <p>These Terms are governed by and construed in accordance with the laws of Sri Lanka. Any dispute arising from these Terms shall be submitted to the exclusive jurisdiction of the courts of Sri Lanka.</p>

    <h2 id="changes">Changes to These Terms</h2>
    <div class="legal-divider"></div>
    <p>We may revise these Terms from time to time. We will notify registered users by email and post a notice on the site at least 14 days before major changes take effect. Continued use of our services after the effective date constitutes acceptance of the revised Terms.</p>

    <h2 id="contact">Contact Us</h2>
    <div class="legal-divider"></div>
    <p>If you have questions about these Terms, please contact us:</p>
    <ul>
      <li>Email: <a href="mailto:lostandfound@gmail.com">lostandfound@gmail.com</a></li>
      <li>Contact form: <a href="/contact">0123456789</a></li>
    </ul>

  </article>
</div>

<!-- Footer -->
<footer class="legal-footer">
  <span class="legal-footer-copy">&copy; <?= date('Y') ?> Lost &amp; Found &middot; Sri Lanka</span>
  <ul class="legal-footer-links">
    <li><a href="/legal/privacy">Privacy Policy</a></li>
    <li><a href="/legal/terms">Terms of Service</a></li>
    <li><a href="/contact">Contact</a></li>
  </ul>
</footer>

<script>
(function () {
  var sections = document.querySelectorAll('.legal-content h2[id]');
  var links    = document.querySelectorAll('.toc-list a');
  if (!sections.length) return;

  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        links.forEach(function (a) { a.classList.remove('active'); });
        var match = document.querySelector('.toc-list a[href="#' + e.target.id + '"]');
        if (match) match.classList.add('active');
      }
    });
  }, { rootMargin: '-10% 0px -80% 0px' });

  sections.forEach(function (s) { obs.observe(s); });
})();
</script>

</body>
</html>