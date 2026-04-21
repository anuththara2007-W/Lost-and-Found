<?php
/**
 * resources/views/layouts/header.php
 *
 * Usage at top of every view:
 *   $pageTitle = 'Privacy Policy — Lost & Found';
 *   $pageCss   = ['login.css'];   // optional extra CSS in /public/assets/css/
 *   require __DIR__ . '/header.php';
 *
 * IMPORTANT:
 *   BASE_URL should be like:
 *   http://localhost/Lost%20%26%20Found/Lost-and-Found/public
 */

if (session_status() === PHP_SESSION_NONE) session_start();

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
$navUser     = $_SESSION['user'] ?? null;

$base = rtrim(BASE_URL, '/');

function activeContains(string $needle, string $current): string {
  return (strpos($current, $needle) !== false) ? 'active' : '';
}
function activeExact(string $path, string $current): string {
  return (rtrim($current, '/') === rtrim($path, '/')) ? 'active' : '';
}

// Best guess for "home" active
$basePath   = parse_url($base, PHP_URL_PATH) ?: '';
$homeActive = activeExact($basePath, $currentPath) ?: activeExact($basePath.'/', $currentPath);

// Precompute active classes for reuse (desktop + mobile)
$lostActive    = activeContains('items/lost',  $currentPath);
$foundActive   = activeContains('items/found', $currentPath);
$mapActive     = activeContains('/map',        $currentPath);
$contactActive = activeContains('/contact',    $currentPath);
$profileActive = activeContains('/profile',    $currentPath);
$dashActive    = activeContains('/dashboard',  $currentPath);
$msgActive     = activeContains('/messages',   $currentPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Lost & Found') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'A trusted community platform for reporting and recovering lost and found items.') ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Global CSS -->
  <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">

  <!-- Per page CSS -->
  <?php if (!empty($pageCss)): foreach ($pageCss as $css): ?>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/<?= htmlspecialchars($css) ?>">
  <?php endforeach; endif; ?>

  <style>
    :root{
      --white:#FAFAF8; --off-white:#F2F0EB; --parchment:#E8E3D8; --warm-mid:#C9C0AE;
      --clay:#9C8C78; --ink:#3A3830; --midnight:#1E2027;
      --terracotta:#C96442; --terracotta-lt:#EDD5C8;
      --sage:#5C7A65; --sage-lt:#D1DFCF;
      --shadow-1: 0 1px 0 rgba(255,255,255,.65) inset;
      --shadow-2: 0 0 0 1px rgba(232,227,216,.92);
      --shadow-3: 0 18px 55px rgba(30,32,39,.10);
      --radius: 14px;
      --ease: cubic-bezier(.2,.8,.2,1);
      --fast: 160ms;
      --med: 240ms;
      --font-display:'Cormorant Garamond',serif;
      --font-body:'DM Sans',sans-serif;
    }

    *{ box-sizing:border-box; }
    body{ margin:0; font-family:var(--font-body); color:var(--ink); background:var(--white); }

    /* Header shell */
    .site-header{
      position: sticky;
      top:0;
      z-index: 1000;
      background: rgba(250,250,248,.88);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--parchment);
      box-shadow: 0 12px 36px rgba(30,32,39,.06);
    }

    .header-inner{
      max-width: 1160px;
      margin: 0 auto;
      padding: 14px 18px;
      display:flex;
      align-items:center;
      gap: 14px;
    }

    /* Logo */
    .header-logo{
      font-family: var(--font-display);
      font-size: 22px;
      font-weight: 400;
      letter-spacing: .2px;
      text-decoration:none;
      color: var(--midnight);
      display:flex;
      align-items: baseline;
      gap: 6px;
      white-space: nowrap;
    }
    .header-logo em{ color: var(--terracotta); font-style: italic; }

    /* Desktop nav */
    .header-nav{
      display:flex;
      align-items:center;
      gap: 6px;
      margin-left: 10px;
      flex: 1;
      min-width: 0;
    }

    .nav-link{
      color: rgba(58,56,48,.72);
      font-size: 13px;
      text-decoration:none;
      padding: 8px 10px;
      border-radius: 12px;
      transition: background var(--fast) var(--ease), color var(--fast) var(--ease), transform var(--fast) var(--ease);
      white-space: nowrap;
      outline: none;
    }
    .nav-link:hover{
      background: rgba(232,227,216,.70);
      color: var(--ink);
      transform: translateY(-1px);
    }
    .nav-link.active{
      background: rgba(237,213,200,.70);
      color: var(--midnight);
      box-shadow: var(--shadow-1), var(--shadow-2);
      font-weight: 600;
    }

    /* Actions */
    .header-actions{
      display:flex;
      align-items:center;
      gap: 10px;
      margin-left:auto;
    }

    .btn-ghost{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding: 8px 12px;
      border-radius: 12px;
      border: 1px solid rgba(232,227,216,.92);
      background: rgba(242,240,235,.55);
      color: var(--ink);
      text-decoration:none;
      box-shadow: 0 10px 28px rgba(30,32,39,.06);
      transition: transform var(--fast) var(--ease), box-shadow var(--fast) var(--ease), background var(--fast) var(--ease);
      font-size: 13px;
      white-space: nowrap;
    }
    .btn-ghost:hover{
      transform: translateY(-1px);
      background: rgba(232,227,216,.85);
      box-shadow: 0 16px 44px rgba(30,32,39,.10);
    }

    .btn-primary-nav{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap: 8px;
      padding: 9px 14px;
      border-radius: 12px;
      border: none;
      color: #fff;
      font-weight: 600;
      font-size: 13px;
      text-decoration:none;
      cursor:pointer;
      background: linear-gradient(180deg, #D26C47, #B85732);
      box-shadow: 0 20px 60px rgba(201,100,66,.22);
      transition: transform var(--fast) var(--ease), box-shadow var(--fast) var(--ease), filter var(--fast) var(--ease);
      white-space: nowrap;
    }
    .btn-primary-nav:hover{
      transform: translateY(-1px);
      box-shadow: 0 30px 80px rgba(201,100,66,.26);
      filter: saturate(1.03);
    }

    /* User pill + dropdown */
    .user-pill{
      position: relative;
      display:flex;
      align-items:center;
      gap: 8px;
      padding: 6px 10px 6px 6px;
      border-radius: 999px;
      border: 1px solid rgba(232,227,216,.92);
      background: rgba(242,240,235,.55);
      box-shadow: 0 10px 28px rgba(30,32,39,.06);
      cursor:pointer;
      transition: background var(--fast) var(--ease), transform var(--fast) var(--ease);
    }
    .user-pill:hover{ background: rgba(232,227,216,.85); transform: translateY(-1px); }

    .user-avatar{
      width: 28px; height: 28px;
      border-radius: 50%;
      background: var(--terracotta);
      color:#fff;
      display:flex; align-items:center; justify-content:center;
      font-weight: 700;
      font-size: 12px;
      overflow:hidden;
    }
    .user-avatar img{ width:100%; height:100%; object-fit:cover; }
    .user-name{ font-size: 13px; color: var(--midnight); font-weight: 600; }
    .pill-caret{ color: rgba(156,140,120,.9); }

    .user-dropdown{
      position:absolute;
      top: calc(100% + 10px);
      right: 0;
      min-width: 210px;
      padding: 8px;
      border-radius: 16px;
      border: 1px solid rgba(232,227,216,.92);
      background: rgba(250,250,248,.96);
      box-shadow: 0 26px 70px rgba(30,32,39,.18);
      opacity:0;
      pointer-events:none;
      transform: translateY(-8px);
      transition: opacity var(--med) var(--ease), transform var(--med) var(--ease);
    }
    .user-pill:hover .user-dropdown,
    .user-pill:focus-within .user-dropdown{
      opacity:1;
      pointer-events:auto;
      transform: translateY(0);
    }

    .dd-header{
      padding: 10px 12px 8px;
      border-bottom: 1px solid rgba(232,227,216,.85);
      margin-bottom: 6px;
    }
    .dd-name{ font-weight: 700; color: var(--midnight); font-size: 13px; }
    .dd-email{ color: rgba(156,140,120,.95); font-size: 11px; margin-top: 2px; }

    .dropdown-item{
      display:flex; align-items:center; gap: 10px;
      padding: 10px 12px;
      border-radius: 12px;
      text-decoration:none;
      color: rgba(58,56,48,.86);
      font-size: 13px;
      transition: background var(--fast) var(--ease), transform var(--fast) var(--ease);
    }
    .dropdown-item:hover{
      background: rgba(232,227,216,.75);
      transform: translateY(-1px);
    }
    .dropdown-divider{
      height: 1px;
      background: rgba(232,227,216,.85);
      margin: 6px 0;
    }
    .dropdown-item.danger:hover{
      background: rgba(201,100,66,.12);
      color: var(--terracotta);
    }

    /* Hamburger */
    .hamburger{
      display:none;
      border: 1px solid rgba(232,227,216,.92);
      background: rgba(242,240,235,.55);
      width: 42px;
      height: 40px;
      border-radius: 12px;
      cursor:pointer;
      box-shadow: 0 10px 28px rgba(30,32,39,.06);
      align-items:center;
      justify-content:center;
      gap: 5px;
      padding: 10px;
    }
    .hamburger span{
      display:block;
      width: 18px;
      height: 2px;
      border-radius: 2px;
      background: rgba(58,56,48,.75);
      transition: transform var(--med) var(--ease), opacity var(--med) var(--ease);
    }
    .hamburger.open span:nth-child(1){ transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2){ opacity:0; }
    .hamburger.open span:nth-child(3){ transform: translateY(-7px) rotate(-45deg); }

    /* Mobile nav (animated) */
    .mobile-nav{
      max-width: 1160px;
      margin: 0 auto;
      padding: 0 18px;

      overflow:hidden;
      max-height: 0;
      opacity: 0;
      transform: translateY(-6px);
      transition: max-height var(--med) var(--ease), opacity var(--med) var(--ease), transform var(--med) var(--ease);
    }
    .mobile-nav.open{
      max-height: 680px;
      opacity: 1;
      transform: translateY(0);
      padding-bottom: 14px;
    }

    .mobile-nav-inner{
      background: rgba(250,250,248,.96);
      border: 1px solid rgba(232,227,216,.92);
      border-radius: 16px;
      box-shadow: 0 26px 70px rgba(30,32,39,.10);
      padding: 10px;
      margin-bottom: 12px;
    }

    .mobile-nav .nav-link{
      display:block;
      padding: 12px 12px;
      border-radius: 12px;
      font-size: 14px;
    }

    .mobile-actions{
      display:flex;
      gap: 10px;
      padding: 10px 2px 2px;
      border-top: 1px solid rgba(232,227,216,.85);
      margin-top: 10px;
    }
    .mobile-actions a{ flex:1; }

    /* ====== Responsive rules (THIS is the real fix) ====== */

    /* Tablet + Mobile */
    @media (max-width: 1024px){
      .header-nav,
      .header-actions{ display:none !important; }

      .hamburger{ display:flex !important; margin-left:auto; }

      .header-inner{ padding: 14px 14px; }

      /* keep mobile nav available (collapsed unless .open) */
      .mobile-nav{ display:block !important; }
    }

    /* Desktop */
    @media (min-width: 1025px){
      .header-nav{ display:flex !important; }
      .header-actions{ display:flex !important; }

      .hamburger{ display:none !important; }

      /* mobile nav doesn't exist on desktop */
      .mobile-nav{ display:none !important; }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce){
      .nav-link, .btn-ghost, .btn-primary-nav, .user-pill, .user-dropdown,
      .mobile-nav, .hamburger span{
        transition: none !important;
      }
    }
  </style>
</head>

<body>

<header class="site-header" role="banner">
  <div class="header-inner">
    <a href="<?= $base ?>/" class="header-logo">Lost &amp; <em>Found</em></a>

    <nav class="header-nav" aria-label="Main navigation">
      <a href="<?= $base ?>/" class="nav-link <?= $homeActive ?>">Browse</a>
      <a href="<?= $base ?>/items/lost" class="nav-link <?= $lostActive ?>">Lost Items</a>
      <a href="<?= $base ?>/items/found" class="nav-link <?= $foundActive ?>">Found Items</a>
      <a href="<?= $base ?>/map" class="nav-link <?= $mapActive ?>">Map</a>
      <a href="<?= $base ?>/contact" class="nav-link <?= $contactActive ?>">Contact</a>
    </nav>

    <div class="header-actions">
      <?php if ($navUser): ?>
        <div class="user-pill" tabindex="0" aria-label="User menu">
          <div class="user-avatar">
            <?php if (!empty($navUser['profile_image'])): ?>
              <img src="<?= htmlspecialchars($navUser['profile_image']) ?>" alt="">
            <?php else: ?>
              <?= strtoupper(substr($navUser['username'] ?? 'U', 0, 1)) ?>
            <?php endif; ?>
          </div>
          <span class="user-name"><?= htmlspecialchars($navUser['username'] ?? 'User') ?></span>
          <svg class="pill-caret" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>

          <div class="user-dropdown" role="menu" aria-label="User dropdown">
            <div class="dd-header">
              <div class="dd-name"><?= htmlspecialchars($navUser['full_name'] ?? '') ?></div>
              <div class="dd-email"><?= htmlspecialchars($navUser['email'] ?? '') ?></div>
            </div>

            <a href="<?= $base ?>/profile" class="dropdown-item" role="menuitem">My Profile</a>
            <a href="<?= $base ?>/dashboard" class="dropdown-item" role="menuitem">Dashboard</a>
            <a href="<?= $base ?>/messages" class="dropdown-item" role="menuitem">Messages</a>

            <div class="dropdown-divider"></div>

            <a href="<?= $base ?>/logout" class="dropdown-item danger" role="menuitem">Sign Out</a>
          </div>
        </div>

        <a href="<?= $base ?>/items/create" class="btn-primary-nav">+ Post Item</a>

      <?php else: ?>
        <a href="<?= $base ?>/login" class="btn-ghost">Sign In</a>
        <a href="<?= $base ?>/register" class="btn-primary-nav">+ Get Started</a>
      <?php endif; ?>
    </div>

    <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false" type="button">
      <span></span><span></span><span></span>
    </button>
  </div>

  <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile navigation">
    <div class="mobile-nav-inner">
      <a href="<?= $base ?>/" class="nav-link <?= $homeActive ?>">Browse</a>
      <a href="<?= $base ?>/items/lost" class="nav-link <?= $lostActive ?>">Lost Items</a>
      <a href="<?= $base ?>/items/found" class="nav-link <?= $foundActive ?>">Found Items</a>
      <a href="<?= $base ?>/map" class="nav-link <?= $mapActive ?>">Map</a>
      <a href="<?= $base ?>/contact" class="nav-link <?= $contactActive ?>">Contact</a>

      <?php if ($navUser): ?>
        <div style="height:10px"></div>
        <a href="<?= $base ?>/profile" class="nav-link <?= $profileActive ?>">My Profile</a>
        <a href="<?= $base ?>/dashboard" class="nav-link <?= $dashActive ?>">Dashboard</a>
        <a href="<?= $base ?>/messages" class="nav-link <?= $msgActive ?>">Messages</a>
        <a href="<?= $base ?>/logout" class="nav-link" style="color: var(--terracotta);">Sign Out</a>
      <?php endif; ?>

      <div class="mobile-actions">
        <?php if ($navUser): ?>
          <a href="<?= $base ?>/items/create" class="btn-primary-nav">+ Post</a>
          <a href="<?= $base ?>/profile" class="btn-ghost">Account</a>
        <?php else: ?>
          <a href="<?= $base ?>/login" class="btn-ghost">Sign In</a>
          <a href="<?= $base ?>/register" class="btn-primary-nav">Get Started</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>

<script>
(function () {
  var BREAKPOINT = 1024;

  var ham = document.getElementById('hamburger');
  var nav = document.getElementById('mobile-nav');
  if (!ham || !nav) return;

  function setOpen(open){
    nav.classList.toggle('open', open);
    ham.classList.toggle('open', open);
    ham.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  ham.addEventListener('click', function (e) {
    e.preventDefault();
    setOpen(!nav.classList.contains('open'));
  });

  // close when clicking a link
  nav.querySelectorAll('a').forEach(function(a){
    a.addEventListener('click', function(){ setOpen(false); });
  });

  // close on outside click
  document.addEventListener('click', function(e){
    if (!nav.classList.contains('open')) return;
    if (nav.contains(e.target) || ham.contains(e.target)) return;
    setOpen(false);
  });

  // close on ESC
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') setOpen(false);
  });

  // close if resized to desktop
  window.addEventListener('resize', function(){
    if (window.innerWidth > BREAKPOINT) setOpen(false);
  });
})();
</script>