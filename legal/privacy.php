
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy Policy — Lost &amp; Found</title>
  <link rel="stylesheet" href="/public/assets/css/login.css">
  <style>

@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=DM+Mono:wght@400;500&display=swap');

:root {
  --white:           #FAFAF8;
  --off-white:       #F2F0EB;
  --parchment:       #E8E3D8;
  --warm-mid:        #C9C0AE;
  --clay:            #9C8C78;
  --ink:             #3A3830;
  --midnight:        #1E2027;

  --terracotta:      #C96442;
  --terracotta-dk:   #A84F32;
  --terracotta-lt:   #EDD5C8;

  --sage:            #5C7A65;
  --sage-lt:         #D1DFCF;

  --amber:           #D4940A;
  --amber-lt:        #F5E9C8;

  --font-display:    'Cormorant Garamond', Georgia, serif;
  --font-body:       'DM Sans', sans-serif;
  --font-mono:       'DM Mono', monospace;

  --r-xs:   4px;
  --r-sm:   8px;
  --r-md:   12px;
  --r-lg:   16px;
  --r-full: 9999px;

  --t-fast: 150ms ease;
  --t-base: 220ms ease;
}

*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html {
  scroll-behavior: smooth;
}

body {
  background: var(--white);
  color: var(--ink);
  font-family: var(--font-body);
  font-size: 15px;
  line-height: 1.65;
  -webkit-font-smoothing: antialiased;
}


.legal-nav {
  background: var(--white);
  border-bottom: 1px solid var(--parchment);
  padding: 0 48px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
}

.legal-nav-logo {
  font-family: var(--font-display);
  font-size: 1.25rem;
  font-weight: 300;
  color: var(--midnight);
  text-decoration: none;
  letter-spacing: 0.2px;
  line-height: 1;
}

.legal-nav-logo em {
  font-style: italic;
  color: var(--terracotta);
}

.legal-nav-links {
  display: flex;
  align-items: center;
  gap: 2px;
}

.legal-nav-links a {
  font-family: var(--font-body);
  font-size: 12px;
  font-weight: 500;
  color: var(--clay);
  text-decoration: none;
  padding: 6px 14px;
  border-radius: var(--r-sm);
  letter-spacing: 0.2px;
  transition: color var(--t-fast), background var(--t-fast);
}

.legal-nav-links a:hover {
  color: var(--midnight);
  background: var(--off-white);
}

.legal-nav-links a.active {
  color: var(--midnight);
  background: var(--parchment);
}

.legal-hero {
  background: var(--white);
  border-bottom: 1px solid var(--parchment);
  padding: 64px 48px 56px;
  position: relative;
  overflow: hidden;
}

/* Decorative right-side circle — CSS only, zero markup */
.legal-hero::after {
  content: '';
  position: absolute;
  right: 56px;
  top: 50%;
  transform: translateY(-50%);
  width: 108px;
  height: 108px;
  border: 1px solid var(--parchment);
  border-radius: 50%;
  pointer-events: none;
}

.legal-hero::before {
  content: '';
  position: absolute;
  right: 78px;
  top: 50%;
  transform: translateY(-50%);
  width: 64px;
  height: 64px;
  border: 1px solid var(--parchment);
  border-radius: 50%;
  pointer-events: none;
}

.legal-hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-family: var(--font-body);
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--clay);
  margin-bottom: 20px;
}

.legal-hero-eyebrow::before {
  content: '';
  display: block;
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
  margin-bottom: 20px;
  max-width: 500px;
}

.legal-hero-title em {
  font-style: italic;
  color: var(--terracotta);
}

.legal-hero-meta {
  font-family: var(--font-body);
  font-size: 12px;
  color: var(--clay);
  letter-spacing: 0.3px;
}

/* ════════════════════════════════════════════════════════════
   OUTER CONTAINER  ← the key "inside a container" requirement
════════════════════════════════════════════════════════════ */
.legal-layout {
  display: grid;
  grid-template-columns: 200px 1fr;

  /* The container frame */
  max-width: 1040px;
  margin: 40px auto 80px;
  border: 1px solid var(--parchment);
  border-radius: var(--r-lg);
  overflow: hidden;
  background: var(--white);
}

/* ════════════════════════════════════════════════════════════
   SIDEBAR — TABLE OF CONTENTS
════════════════════════════════════════════════════════════ */
.legal-toc {
  padding: 40px 0;
  border-right: 1px solid var(--parchment);
  position: sticky;
  top: 60px;               /* clears the sticky nav */
  height: fit-content;
  align-self: start;
  background: var(--white);
}

.toc-label {
  font-family: var(--font-body);
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
  display: flex;
  flex-direction: column;
  gap: 1px;
  padding: 0;
  margin: 0;
}

.toc-list a {
  display: block;
  font-family: var(--font-body);
  font-size: 12px;
  font-weight: 400;
  color: var(--clay);
  text-decoration: none;
  padding: 7px 20px;
  border-left: 2px solid transparent;
  line-height: 1.45;
  transition: color var(--t-fast), border-color var(--t-fast), background var(--t-fast);
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

/* ════════════════════════════════════════════════════════════
   MAIN ARTICLE CONTENT
════════════════════════════════════════════════════════════ */
.legal-content {
  padding: 48px 52px 72px;
  counter-reset: section;
}

/* ── Section headings ─────────────────────────────────────── */
.legal-content h2 {
  font-family: var(--font-display);
  font-size: 1.65rem;
  font-weight: 400;
  color: var(--midnight);
  margin: 52px 0 6px;
  letter-spacing: -0.2px;
  line-height: 1.2;
  scroll-margin-top: 80px;
}

.legal-content h2:first-of-type {
  margin-top: 0;
}

/* Mono counter label auto-generated above every h2 */
.legal-content h2::before {
  counter-increment: section;
  content: counter(section, decimal-leading-zero) " —";
  display: block;
  font-family: var(--font-mono);
  font-size: 10px;
  font-weight: 400;
  color: var(--warm-mid);
  letter-spacing: 1.5px;
  margin-bottom: 4px;
}

/* ── Divider below headings ───────────────────────────────── */
.legal-divider {
  height: 1px;
  background: var(--parchment);
  margin: 10px 0 18px;
  max-width: 640px;
}

/* ── Body copy ────────────────────────────────────────────── */
.legal-content p {
  font-family: var(--font-body);
  font-size: 14px;
  line-height: 1.85;
  color: var(--ink);
  margin-bottom: 16px;
  max-width: 640px;
}

/* ── Lists ────────────────────────────────────────────────── */
.legal-content ul {
  list-style: none;
  margin: 0 0 18px;
  padding: 0;
}

.legal-content ul li {
  font-family: var(--font-body);
  font-size: 14px;
  line-height: 1.8;
  color: var(--ink);
  margin-bottom: 6px;
  padding-left: 20px;
  position: relative;
  max-width: 640px;
}

/* Em-dash bullet — editorial, premium */
.legal-content ul li::before {
  content: '—';
  position: absolute;
  left: 0;
  color: var(--warm-mid);
  font-size: 12px;
  line-height: 1.95;
}

.legal-content ul li strong {
  color: var(--midnight);
  font-weight: 500;
}

/* ── Inline links ─────────────────────────────────────────── */
.legal-content a {
  color: var(--terracotta);
  text-decoration: underline;
  text-decoration-thickness: 1px;
  text-underline-offset: 3px;
  transition: color var(--t-fast);
}

.legal-content a:hover {
  color: var(--terracotta-dk);
}

/* ── Highlight / Callout box ──────────────────────────────── */
.legal-highlight-box {
  background: var(--off-white);
  border: 1px solid var(--parchment);
  border-left: 2px solid var(--terracotta);
  border-radius: 0 var(--r-sm) var(--r-sm) 0;
  padding: 16px 20px;
  margin-bottom: 40px;
  max-width: 640px;
}

.legal-highlight-box p {
  margin: 0;
  font-family: var(--font-body);
  font-size: 13px;
  line-height: 1.7;
  color: var(--clay);
  max-width: none;       /* override global p max-width inside box */
}

.legal-highlight-box p strong {
  color: var(--ink);
  font-weight: 500;
}

/* ════════════════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════════════════ */
@media (max-width: 900px) {
  .legal-nav     { padding: 0 28px; }
  .legal-hero    { padding: 48px 28px 40px; }
  .legal-hero::before,
  .legal-hero::after { display: none; }

  .legal-layout  {
    grid-template-columns: 1fr;
    margin: 24px 16px 60px;
  }

  .legal-toc     { display: none; }

  .legal-content { padding: 32px 28px 52px; }
}

@media (max-width: 500px) {
  .legal-hero-title { font-size: 2rem; }
  .legal-nav        { padding: 0 20px; }
  .legal-layout     { margin: 16px 12px 48px; }
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
  <p class="legal-hero-meta">Last updated: <?= date('F j, Y') ?> &nbsp;·&nbsp; Effective: January 1, 2025</p>
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