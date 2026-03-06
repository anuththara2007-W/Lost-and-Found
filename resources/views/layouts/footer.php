<?php
/**
 * resources/views/layouts/footer.php
 * Include at the bottom of every view:
 *   require_once ROOT . '/resources/views/layouts/footer.php';
 */
?>

  <?php if (!empty($pageJs)): foreach ($pageJs as $js): ?>
  <script src="/Lost%20&%20Found/Lost-and-Found/public/assets/js/<?= htmlspecialchars($js) ?>"></script>
  <?php endforeach; endif; ?>

  <style>
  /* ── FOOTER ────────────────────────────────── */
  .site-footer {
    background: var(--midnight);
    color: var(--warm-mid);
    margin-top: auto;
  }

  /* Upper footer */
  .footer-upper {
    max-width: 1240px;
    margin: 0 auto;
    padding: 64px 36px 48px;
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr;
    gap: 48px;
  }

  /* Brand column */
  .footer-brand {}

  .footer-logo {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 300;
    color: var(--white);
    text-decoration: none;
    letter-spacing: .3px;
    display: inline-block;
    margin-bottom: 14px;
  }
  .footer-logo em { font-style: italic; color: var(--terracotta); }
  .logo-amp-f { color: var(--warm-mid); margin: 0 3px; font-size: 1.2rem; }

  .footer-tagline {
    font-size: 13.5px;
    line-height: 1.7;
    color: var(--clay);
    max-width: 240px;
    margin-bottom: 24px;
  }

  /* Social icons */
  .footer-socials { display: flex; gap: 10px; }
  .social-btn {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; justify-content: center;
    color: var(--clay);
    text-decoration: none;
    transition: background var(--transition), color var(--transition), border-color var(--transition);
  }
  .social-btn:hover { background: rgba(201,100,66,.15); color: var(--terracotta); border-color: rgba(201,100,66,.3); }

  /* Link columns */
  .footer-col-title {
    font-size: 10px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: var(--white);
    font-weight: 500;
    margin-bottom: 18px;
  }

  .footer-links {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .footer-links a {
    color: var(--clay);
    text-decoration: none;
    font-size: 13.5px;
    transition: color var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .footer-links a:hover { color: var(--white); }
  .footer-links a:hover .link-arrow { transform: translateX(3px); }
  .link-arrow { transition: transform var(--transition); }

  /* Trust badges row */
  .footer-trust {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 36px 36px;
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
  }

  .trust-badge {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 10px 16px;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 10px;
    font-size: 12px;
    color: var(--clay);
  }
  .trust-badge svg { color: var(--sage); flex-shrink: 0; }
  .trust-badge strong { color: var(--warm-mid); }

  /* Divider */
  .footer-divider {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 36px;
  }
  .footer-divider hr {
    border: none;
    border-top: 1px solid rgba(255,255,255,.07);
    margin-bottom: 28px;
  }

  /* Lower footer */
  .footer-lower {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 36px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
  }

  .footer-copy {
    font-size: 12.5px;
    color: var(--clay);
  }

  .footer-legal-links {
    display: flex;
    gap: 20px;
  }

  .footer-legal-links a {
    font-size: 12.5px;
    color: var(--clay);
    text-decoration: none;
    transition: color var(--transition);
  }
  .footer-legal-links a:hover { color: var(--white); }

  /* Responsive */
  @media (max-width: 900px) {
    .footer-upper { grid-template-columns: 1fr 1fr; gap: 36px; padding: 48px 24px 36px; }
    .footer-brand { grid-column: 1 / -1; }
  }

  @media (max-width: 560px) {
    .footer-upper { grid-template-columns: 1fr; }
    .footer-lower { flex-direction: column; align-items: flex-start; padding: 0 24px 36px; }
    .footer-trust { padding: 0 24px 28px; }
    .footer-divider { padding: 0 24px; }
    .footer-legal-links { flex-wrap: wrap; gap: 12px; }
  }
  </style>

</main><!-- /.page-main opened in header -->

<footer class="site-footer" role="contentinfo">

  <div class="footer-upper">

    <!-- Brand -->
    <div class="footer-brand">
      <a href="/Lost%20&%20Found/Lost-and-Found/public/" class="footer-logo">
        Lost <span class="logo-amp-f">&amp;</span> <em>Found</em>
      </a>
      <p class="footer-tagline">
        A trusted community platform helping reunite people with their lost belongings across the city.
      </p>
      <div class="footer-socials">
        <a href="#" class="social-btn" aria-label="Facebook">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="#" class="social-btn" aria-label="Twitter / X">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="#" class="social-btn" aria-label="Instagram">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
        </a>
        <a href="#" class="social-btn" aria-label="Telegram">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
        </a>
      </div>
    </div>

    <!-- Platform -->
    <div>
      <p class="footer-col-title">Platform</p>
      <ul class="footer-links">
        <li><a href="/Lost%20&%20Found/Lost-and-Found/public/">Browse All Items <svg class="link-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/public/items/lost">Lost Items</a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/public/items/found">Found Items</a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/public/map">Item Map</a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/public/search">Search</a></li>
      </ul>
    </div>

    <!-- Company -->
    <div>
      <p class="footer-col-title">Company</p>
      <ul class="footer-links">
        <li><a href="/Lost%20&%20Found/Lost-and-Found/resources/views/pages/about.php">About Us</a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/resources/views/pages/contact.php">Contact</a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/resources/views/pages/faq.php">FAQ</a></li>
      </ul>
    </div>

    <!-- Legal -->
    <div>
      <p class="footer-col-title">Legal</p>
      <ul class="footer-links">
        <li><a href="/Lost%20&%20Found/Lost-and-Found/legal/privacy.php">Privacy Policy</a></li>
        <li><a href="/Lost%20&%20Found/Lost-and-Found/legal/terms.php">Terms of Service</a></li>
      </ul>
    </div>

  </div>

  <!-- Trust badges -->
  <div class="footer-trust">
    <div class="trust-badge">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <span><strong>SSL Secured</strong> &nbsp;All data encrypted</span>
    </div>
    <div class="trust-badge">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      <span><strong>4,200+</strong> &nbsp;Items reunited</span>
    </div>
    <div class="trust-badge">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <span><strong>12,000+</strong> &nbsp;Active users</span>
    </div>
    <div class="trust-badge">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <span><strong>24 hr</strong> &nbsp;Average response</span>
    </div>
  </div>

  <div class="footer-divider"><hr></div>

  <div class="footer-lower">
    <p class="footer-copy">
      &copy; <?= date('Y') ?> Lost &amp; Found. All rights reserved.
    </p>
    <div class="footer-legal-links">
      <a href="/Lost%20&%20Found/Lost-and-Found/legal/privacy.php">Privacy</a>
      <a href="/Lost%20&%20Found/Lost-and-Found/legal/terms.php">Terms</a>
      <a href="/Lost%20&%20Found/Lost-and-Found/resources/views/pages/contact.php">Contact</a>
    </div>
  </div>

</footer>

</body>
</html>