<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terms of Service — Lost &amp; Found</title>
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
    .legal-nav-links a { color: var(--warm-mid); font-size: 13px; text-decoration: none; transition: color .2s; }
    .legal-nav-links a:hover, .legal-nav-links a.active { color: var(--white); }

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

    .legal-hero-eyebrow { font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: var(--terracotta); font-weight: 500; margin-bottom: 14px; position: relative; z-index: 1; }
    .legal-hero-title { font-family: var(--font-display); font-size: 3rem; font-weight: 300; color: var(--white); margin-bottom: 12px; position: relative; z-index: 1; }
    .legal-hero-title em { font-style: italic; color: var(--terracotta); }
    .legal-hero-meta { color: var(--clay); font-size: 13px; position: relative; z-index: 1; }

    .legal-layout {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 48px;
      max-width: 1020px;
      margin: 0 auto;
      padding: 56px 32px 100px;
    }

    .legal-toc { position: sticky; top: 80px; height: fit-content; }

    .toc-label { font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase; color: var(--clay); margin-bottom: 14px; font-weight: 500; }

    .toc-list { list-style: none; display: flex; flex-direction: column; gap: 4px; }

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

    .toc-list a:hover { color: var(--ink); background: var(--parchment); }

    .toc-list a.active {
      color: var(--terracotta);
      border-left-color: var(--terracotta);
      background: var(--terracotta-lt);
    }

    .legal-content h2 {
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-weight: 400;
      color: var(--midnight);
      margin: 48px 0 14px;
      scroll-margin-top: 90px;
    }

    .legal-content h2:first-child { margin-top: 0; }

    .legal-content p { color: var(--ink); font-size: 14.5px; line-height: 1.8; margin-bottom: 16px; }

    .legal-content ul { margin: 0 0 16px 0; padding-left: 20px; }

    .legal-content ul li { color: var(--ink); font-size: 14.5px; line-height: 1.8; margin-bottom: 6px; }

    .legal-content a { color: var(--terracotta); text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 2px; }

    .legal-divider { height: 1px; background: var(--parchment); margin: 8px 0 16px; }

    .legal-highlight-box {
      background: var(--amber-lt);
      border-left: 3px solid var(--amber);
      border-radius: 0 10px 10px 0;
      padding: 16px 20px;
      margin-bottom: 20px;
    }

    .legal-highlight-box p { margin: 0; color: var(--amber); font-size: 13.5px; }

    .forbidden-list li { color: var(--terracotta) !important; }
    .forbidden-list li::marker { color: var(--terracotta); }

    @media (max-width: 768px) {
      .legal-layout { grid-template-columns: 1fr; }
      .legal-toc { display: none; }
    }
  </style>
</head>
<body>

<nav class="legal-nav">
  <a href="/" class="legal-nav-logo">Lost &amp; <em>Found</em></a>
  <div class="legal-nav-links">
    <a href="/legal/privacy">Privacy</a>
    <a href="/legal/terms" class="active">Terms</a>
    <a href="/contact">Contact</a>
  </div>
</nav>

<div class="legal-hero">
  <p class="legal-hero-eyebrow">Legal</p>
  <h1 class="legal-hero-title">Terms of <em>Service</em></h1>
  <p class="legal-hero-meta">Last updated: <?= date('F j, Y') ?> &nbsp;·&nbsp; Effective: January 1, 2024</p>
</div>

<div class="legal-layout">

  <!-- TOC -->
  <aside class="legal-toc">
    <p class="toc-label">Contents</p>
    <ul class="toc-list">
      <li><a href="#acceptance">Acceptance</a></li>
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
      <p>
        <strong>Please read carefully.</strong> By creating an account or using our services, you agree to be bound by these Terms of Service.
      </p>
    </div>

    <h2 id="acceptance">1. Acceptance of Terms</h2>
    <div class="legal-divider"></div>
    <p>
      These Terms of Service ("Terms") govern your access to and use of Lost &amp; Found ("we", "us", "our"). By registering for an account, posting content, or otherwise using our platform, you agree to these Terms. If you do not agree, do not use our services.
    </p>

    <h2 id="eligibility">2. Eligibility</h2>
    <div class="legal-divider"></div>
    <p>You must be at least 13 years of age to create an account. By using our services, you represent that:</p>
    <ul>
      <li>You are at least 13 years old.</li>
      <li>You have the legal capacity to enter into a binding agreement.</li>
      <li>You are not prohibited from using our services under applicable law.</li>
    </ul>

    <h2 id="account">3. Your Account</h2>
    <div class="legal-divider"></div>
    <p>You are responsible for:</p>
    <ul>
      <li>Maintaining the confidentiality of your login credentials.</li>
      <li>All activity that occurs under your account.</li>
      <li>Providing accurate, current, and complete registration information.</li>
      <li>Promptly notifying us if you suspect unauthorised access to your account.</li>
    </ul>
    <p>
      You may not create more than one account, transfer your account to another person, or use another person's account without their permission.
    </p>

    <h2 id="use">4. Acceptable Use</h2>
    <div class="legal-divider"></div>
    <p>You agree to use Lost &amp; Found only for its intended purpose: reporting and helping to recover genuinely lost or found items. You agree to:</p>
    <ul>
      <li>Post only accurate and truthful information.</li>
      <li>Communicate with other users respectfully and in good faith.</li>
      <li>Keep your contact details up to date so you can be reached about reports.</li>
      <li>Mark reports as resolved once the item is reunited with its owner.</li>
    </ul>

    <h2 id="prohibited">5. Prohibited Conduct</h2>
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
    <p>
      Violations may result in account suspension or permanent termination at our sole discretion.
    </p>

    <h2 id="content">6. User Content</h2>
    <div class="legal-divider"></div>
    <p>
      You retain ownership of content you post (item descriptions, photos, messages). By posting, you grant us a non-exclusive, worldwide, royalty-free licence to store, display, and transmit that content solely for the purpose of operating the platform.
    </p>
    <p>
      You are solely responsible for the content you post. We do not pre-screen user content but reserve the right to remove any content that violates these Terms or is otherwise objectionable.
    </p>

    <h2 id="items">7. Item Reports &amp; Reunification</h2>
    <div class="legal-divider"></div>
    <p>
      Lost &amp; Found is a communication platform only. We do not verify the identity of users, the ownership of items, or the accuracy of reports. We are not responsible for:
    </p>
    <ul>
      <li>The return, loss, or damage of any item.</li>
      <li>Any transactions, arrangements, or disputes between users.</li>
      <li>Any harm arising from meetings or exchanges arranged through the platform.</li>
    </ul>
    <p>
      We strongly recommend exercising caution when meeting strangers. Always meet in a public place and bring a friend if possible.
    </p>

    <h2 id="liability">8. Limitation of Liability</h2>
    <div class="legal-divider"></div>
    <p>
      To the maximum extent permitted by applicable law, Lost &amp; Found and its operators shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the platform, including loss of data, items, or property.
    </p>
    <p>
      Our total liability to you for any claim arising from these Terms or your use of the platform shall not exceed SGD $100 or the amount you paid to us in the 12 months preceding the claim, whichever is greater.
    </p>

    <h2 id="termination">9. Account Termination</h2>
    <div class="legal-divider"></div>
    <p>
      You may delete your account at any time from your account settings. We may suspend or terminate your account at any time if you violate these Terms, engage in fraudulent activity, or if we decide to discontinue the service.
    </p>
    <p>
      Upon termination, your right to use the platform ceases immediately. Provisions that by their nature should survive termination (including Sections 6, 8, and 10) will remain in effect.
    </p>

    <h2 id="disputes">10. Governing Law &amp; Disputes</h2>
    <div class="legal-divider"></div>
    <p>
      These Terms are governed by and construed in accordance with the laws of Singapore. Any dispute arising from these Terms shall be submitted to the exclusive jurisdiction of the courts of Singapore.
    </p>

    <h2 id="changes">11. Changes to These Terms</h2>
    <div class="legal-divider"></div>
    <p>
      We may revise these Terms from time to time. We will notify registered users by email and post a notice on the site at least 14 days before major changes take effect. Continued use of our services after the effective date constitutes acceptance of the revised Terms.
    </p>

    <h2 id="contact">12. Contact Us</h2>
    <div class="legal-divider"></div>
    <p>
      If you have questions about these Terms, please contact us:
    </p>
    <ul>
      <li>Email: <a href="mailto:legal@lostandfound.sg">legal@lostandfound.sg</a></li>
      <li>Contact form: <a href="/contact">lostandfound.sg/contact</a></li>
    </ul>

  </article>

</div>

<script>const sections = document.querySelectorAll('.legal-content h2[id]');
const tocLinks = document.querySelectorAll('.toc-list a');

const observerOptions = {
    root: null,
    rootMargin: '-10% 0px -80% 0px',
    threshold: 0
};

function handleIntersection(entries)
{
    entries.forEach(function(entry)
    {
        if (entry.isIntersecting === true)
        {
            tocLinks.forEach(function(link)
            {
                link.classList.remove('active');
            });

            const currentId = entry.target.id;

            const selector = '.toc-list a[href="#' + currentId + '"]';

            const activeLink = document.querySelector(selector);

            if (activeLink !== null)
            {
                activeLink.classList.add('active');
            }
        }
    });
}

const observer = new IntersectionObserver(handleIntersection, observerOptions);

function observeAllSections()
{
    sections.forEach(function(section)
    {
        observer.observe(section);
    });
}

observeAllSections();
</script>

</body>
</html>