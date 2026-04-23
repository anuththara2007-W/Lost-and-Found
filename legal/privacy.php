<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy Policy — Lost &amp; Found</title>
  <link rel="stylesheet" href="/public/assets/css/login.css">
  <style>
    body { background: var(--white); }

    .legal-nav {
      background: var(--midnight);
      padding: 14px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .legal-nav-logo {
      font-family: var(--font-display);
      font-size: 1.3rem;
      font-weight: 300;
      color: var(--white);
      text-decoration: none;
    }

    .legal-nav-logo em { font-style: italic; color: var(--terracotta); }

    .legal-nav-links { display: flex; gap: 20px; }

    .legal-nav-links a {
      color: var(--warm-mid);
      font-size: 13px;
      text-decoration: none;
      transition: color .2s;
    }

    .legal-nav-links a:hover,
    .legal-nav-links a.active { color: var(--white); }

    /* Hero */
    .legal-hero {
      background: var(--midnight);
      padding: 56px 40px 60px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .legal-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 60% 80% at 50% 130%, rgba(201,100,66,.18) 0%, transparent 60%);
      pointer-events: none;
    }

    .legal-hero-eyebrow {
      font-size: 10px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--terracotta);
      font-weight: 500;
      margin-bottom: 14px;
      position: relative;
      z-index: 1;
    }

    .legal-hero-title {
      font-family: var(--font-display);
      font-size: 3rem;
      font-weight: 300;
      color: var(--white);
      margin-bottom: 12px;
      position: relative;
      z-index: 1;
    }

    .legal-hero-title em { font-style: italic; color: var(--terracotta); }

    .legal-hero-meta {
      color: var(--clay);
      font-size: 13px;
      position: relative;
      z-index: 1;
    }

    /* Layout */
    .legal-layout {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 48px;
      max-width: 1020px;
      margin: 0 auto;
      padding: 56px 32px 100px;
    }

    /* Sidebar */
    .legal-toc {
      position: sticky;
      top: 80px;
      height: fit-content;
    }

    .toc-label {
      font-size: 10px;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      color: var(--clay);
      margin-bottom: 14px;
      font-weight: 500;
    }

    .toc-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .toc-list a {
      display: block;
      font-size: 13px;
      color: var(--clay);
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 7px;
      border-left: 2px solid transparent;
      transition: all .15s ease;
    }

    .toc-list a:hover {
      color: var(--ink);
      background: var(--parchment);
    }

    .toc-list a.active {
      color: var(--terracotta);
      border-left-color: var(--terracotta);
      background: var(--terracotta-lt);
    }

    /* Content */
    .legal-content h2 {
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-weight: 400;
      color: var(--midnight);
      margin: 48px 0 14px;
      scroll-margin-top: 90px;
    }

    .legal-content h2:first-child { margin-top: 0; }

    .legal-content p {
      color: var(--ink);
      font-size: 14.5px;
      line-height: 1.8;
      margin-bottom: 16px;
    }

    .legal-content ul {
      margin: 0 0 16px 0;
      padding-left: 20px;
    }

    .legal-content ul li {
      color: var(--ink);
      font-size: 14.5px;
      line-height: 1.8;
      margin-bottom: 6px;
    }

    .legal-content a {
      color: var(--terracotta);
      text-decoration: underline;
      text-decoration-thickness: 1px;
      text-underline-offset: 2px;
    }

    .legal-divider {
      height: 1px;
      background: var(--parchment);
      margin: 8px 0 16px;
    }

    .legal-highlight-box {
      background: var(--terracotta-lt);
      border-left: 3px solid var(--terracotta);
      border-radius: 0 10px 10px 0;
      padding: 16px 20px;
      margin-bottom: 20px;
    }

    .legal-highlight-box p {
      margin: 0;
      color: var(--terracotta);
      font-size: 13.5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .legal-layout { grid-template-columns: 1fr; }
      .legal-toc { display: none; }
    }
  </style>
</head>
<body>

<!-- Nav -->
<nav class="legal-nav">
  <a href="/" class="legal-nav-logo">Lost &amp; <em>Found</em></a>
  <div class="legal-nav-links">
    <a href="/legal/privacy" class="active">Privacy</a>
    <a href="/legal/terms">Terms</a>
    <a href="/contact">Contact</a>
  </div>
</nav>

<!-- Hero -->
<div class="legal-hero">
  <p class="legal-hero-eyebrow">Legal</p>
  <h1 class="legal-hero-title"><em>Privacy</em> Policy</h1>
  <p class="legal-hero-meta">Last updated: <?= date('F j, Y') ?> &nbsp;·&nbsp; Effective: January 1, 2024</p>
</div>

<!-- Layout -->
<div class="legal-layout">

  <!-- TOC -->
  <aside class="legal-toc">
    <p class="toc-label">Contents</p>
    <ul class="toc-list">
      <li><a href="#overview">Overview</a></li>
      <li><a href="#data-collected">Data We Collect</a></li>
      <li><a href="#how-used">How We Use Data</a></li>
      <li><a href="#sharing">Data Sharing</a></li>
      <li><a href="#security">Security</a></li>
      <li><a href="#cookies">Cookies</a></li>
      <li><a href="#your-rights">Your Rights</a></li>
      <li><a href="#retention">Retention</a></li>
      <li><a href="#children">Children</a></li>
      <li><a href="#changes">Policy Changes</a></li>
      <li><a href="#contact">Contact Us</a></li>
    </ul>
  </aside>

  <!-- Content -->
  <article class="legal-content">

    <div class="legal-highlight-box">
      <p>
        <strong>Summary:</strong> We collect only the data needed to operate Lost &amp; Found. We do not sell your personal information, and you can delete your account at any time.
      </p>
    </div>

    <h2 id="overview">1. Overview</h2>
    <div class="legal-divider"></div>
    <p>
      Lost &amp; Found ("we", "our", or "us") is a community platform that helps people report and recover lost items. This Privacy Policy explains how we collect, use, store, and protect your personal information when you use our website and services.
    </p>
    <p>
      By creating an account or using our services, you agree to the collection and use of information as described in this policy. If you do not agree, please do not use our services.
    </p>

    <h2 id="data-collected">2. Data We Collect</h2>
    <div class="legal-divider"></div>
    <p>We collect information you provide directly to us:</p>
    <ul>
      <li><strong>Account information:</strong> Username, full name, email address, phone number (optional), and profile photo (optional).</li>
      <li><strong>Item reports:</strong> Descriptions, photos, locations, and dates of lost or found items you post.</li>
      <li><strong>Messages:</strong> Communications you send to other users through our platform.</li>
      <li><strong>Contact enquiries:</strong> Any messages you submit via the contact form.</li>
    </ul>
    <p>We also collect information automatically:</p>
    <ul>
      <li><strong>Usage data:</strong> Pages visited, features used, time spent, and browser/device type.</li>
      <li><strong>Log data:</strong> IP address, access times, referring URLs, and error reports.</li>
      <li><strong>Cookies:</strong> Session and preference data (see <a href="#cookies">Cookies</a> section).</li>
    </ul>

    <h2 id="how-used">3. How We Use Your Data</h2>
    <div class="legal-divider"></div>
    <p>We use the information we collect to:</p>
    <ul>
      <li>Operate, maintain, and improve the platform.</li>
      <li>Create and manage your account.</li>
      <li>Display and match lost and found reports.</li>
      <li>Facilitate communication between users.</li>
      <li>Send service-related notifications (e.g. potential matches, security alerts).</li>
      <li>Respond to your support requests.</li>
      <li>Monitor for and prevent fraud or abuse.</li>
      <li>Comply with legal obligations.</li>
    </ul>
    <p>
      We will not use your data for advertising, and we will never sell your personal information to third parties.
    </p>

    <h2 id="sharing">4. Data Sharing</h2>
    <div class="legal-divider"></div>
    <p>We do not sell, rent, or trade your personal information. We may share limited data in the following circumstances:</p>
    <ul>
      <li><strong>With other users:</strong> Your username, profile photo, and item reports are visible to other registered users. Your email and phone number are never publicly displayed.</li>
      <li><strong>Service providers:</strong> We use third-party services (e.g. email delivery, hosting, analytics) that process data on our behalf under strict confidentiality agreements.</li>
      <li><strong>Legal requirements:</strong> We may disclose data if required by law or to protect the rights and safety of our users.</li>
      <li><strong>Business transfers:</strong> If we are involved in a merger or acquisition, user data may be transferred as part of that transaction. We will notify you in advance.</li>
    </ul>

    <h2 id="security">5. Security</h2>
    <div class="legal-divider"></div>
    <p>
      We take data security seriously. Measures we implement include:
    </p>
    <ul>
      <li>HTTPS encryption for all data in transit.</li>
      <li>Bcrypt hashing (cost factor 12) for all passwords — we never store plain-text passwords.</li>
      <li>Prepared statements and parameterised queries to prevent SQL injection.</li>
      <li>CSRF tokens on all state-changing forms.</li>
      <li>Rate limiting on authentication endpoints.</li>
      <li>Regular security reviews of our codebase.</li>
    </ul>
    <p>
      No method of transmission over the internet is 100% secure. If you believe your account has been compromised, please contact us immediately at <a href="mailto:security@lostandfound.sg">security@lostandfound.sg</a>.
    </p>

    <h2 id="cookies">6. Cookies</h2>
    <div class="legal-divider"></div>
    <p>We use the following cookies:</p>
    <ul>
      <li><strong>Session cookie:</strong> Required to keep you logged in during your visit. Deleted when you close your browser.</li>
      <li><strong>Remember me cookie:</strong> Set when you check "Remember me". Persists for 30 days. You can clear it by logging out.</li>
      <li><strong>CSRF token:</strong> A security token attached to your session to prevent cross-site request forgery.</li>
    </ul>
    <p>We do not use third-party advertising cookies or tracking pixels.</p>

    <h2 id="your-rights">7. Your Rights</h2>
    <div class="legal-divider"></div>
    <p>You have the right to:</p>
    <ul>
      <li><strong>Access:</strong> Request a copy of the personal data we hold about you.</li>
      <li><strong>Rectification:</strong> Update your information at any time from your profile settings.</li>
      <li><strong>Erasure:</strong> Delete your account and associated data permanently from your account settings.</li>
      <li><strong>Portability:</strong> Request an export of your data in a machine-readable format.</li>
      <li><strong>Objection:</strong> Object to certain types of processing (e.g. analytics).</li>
    </ul>
    <p>
      To exercise any of these rights, contact us at <a href="mailto:privacy@lostandfound.sg">privacy@lostandfound.sg</a>.
    </p>

    <h2 id="retention">8. Data Retention</h2>
    <div class="legal-divider"></div>
    <p>
      We retain your account data for as long as your account is active. Item reports are retained for 12 months after they are marked resolved, then automatically deleted. If you delete your account, all personal data is removed within 30 days, except where retention is required by law.
    </p>

    <h2 id="children">9. Children's Privacy</h2>
    <div class="legal-divider"></div>
    <p>
      Our services are intended for users aged 13 and over. We do not knowingly collect personal data from children under 13. If you believe a child has provided us with personal information, please contact us and we will delete it promptly.
    </p>

    <h2 id="changes">10. Changes to This Policy</h2>
    <div class="legal-divider"></div>
    <p>
      We may update this Privacy Policy from time to time. We will notify registered users by email and post a notice on the site at least 14 days before major changes take effect. Continued use of our services after the effective date constitutes acceptance of the updated policy.
    </p>

    <h2 id="contact">11. Contact Us</h2>
    <div class="legal-divider"></div>
    <p>
      If you have any questions about this Privacy Policy or how we handle your data, please reach out:
    </p>
    <ul>
      <li>Email: <a href="mailto:privacy@lostandfound.sg">privacy@lostandfound.sg</a></li>
      <li>Contact form: <a href="/contact">lostandfound.sg/contact</a></li>
    </ul>

  </article>

</div>

<script>
  // Active TOC link on scroll
  const sections = document.querySelectorAll('.legal-content h2[id]');
  const tocLinks = document.querySelectorAll('.toc-list a');

  const obs = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        tocLinks.forEach(a => a.classList.remove('active'));
        const active = document.querySelector(`.toc-list a[href="#${entry.target.id}"]`);
        if (active) active.classList.add('active');
      }
    });
  }, { rootMargin: '-10% 0px -80% 0px' });

  sections.forEach(s => obs.observe(s));
</script>

</body>
</html>