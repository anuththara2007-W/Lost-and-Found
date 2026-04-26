<!DOCTYPE html>
<html lang="<?= escape(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? escape($data['title']) : 'Lost & Found' ?></title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/google-sans">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <!-- Page specific CSS -->
    <?php if(isset($data['css'])): ?>
        <?php foreach($data['css'] as $cssFile): ?>
            <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= $cssFile ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- JS -->
    <script src="<?= BASE_URL ?>/assets/js/fluid-effect.js" defer></script>

    <!-- =======================
         GLOBAL HEADER STYLES
    ======================== -->
    <style>

        .nav-logo {
            font-family: "Google Sans", sans-serif;
            font-optical-sizing: auto;
            font-weight: 650;
            font-style: normal;
            font-variation-settings: "GRAD" 0;
        }

        .nav-logo span {
            font-weight: 650;
            font-style: normal;
        }

        /* Fluid background canvas */
        #fluid-glass-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 400px;
            z-index: -1;
            pointer-events: none;
        }

        /* Global announcement */
        .global-announcement {
            padding: 10px 20px;
            text-align: center;
            color: white;
        }

        /* Theme switch */
        .theme-switch input:checked + .slider {
            background-color: #34c759;
        }

        .theme-switch input:checked + .slider .slider-circle {
            transform: translateX(24px);
        }

        .theme-switch-wrapper {
            margin-left: 20px;
            display: flex;
            align-items: center;
        }

        .theme-switch {
            display: inline-block;
            height: 26px;
            position: relative;
            width: 50px;
        }

        .theme-switch input {
            display: none;
        }

        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
            border-radius: 26px;
        }

        .slider-circle {
            background-color: #fff;
            bottom: 3px;
            height: 20px;
            left: 3px;
            position: absolute;
            transition: .4s;
            width: 20px;
            border-radius: 50%;
            z-index: 2;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .icon-sun {
            position: absolute;
            left: 6px;
            top: 4px;
            font-size: 12px;
            color: #fff;
            z-index: 1;
        }

        .icon-moon {
            position: absolute;
            right: 6px;
            top: 4px;
            font-size: 12px;
            color: #fff;
            z-index: 1;
        }

        .nav-btn-logout {
            font-size: 0.85rem !important;
            padding: 6px 12px !important;
        }

    </style>

</head>

<body>

<!-- Canvas Background -->
<canvas id="fluid-glass-canvas"></canvas>

<?php
require_once dirname(__DIR__, 3) . '/app/Models/Announcement.php';
$announcementModel = new \App\Models\Announcement();
$activeAnnouncements = $announcementModel->getActive();

if (!empty($activeAnnouncements)):
    foreach ($activeAnnouncements as $ann):
?>

<div class="global-announcement alert-<?= htmlspecialchars($ann['type']); ?>"
     style="background:
        <?= $ann['type'] == 'info' ? '#3b82f6' :
           ($ann['type'] == 'warning' ? '#f59e0b' :
           ($ann['type'] == 'danger' ? '#ef4444' : '#10b981')); ?>">

    <strong><?= htmlspecialchars($ann['title']); ?>:</strong>
    <?= htmlspecialchars($ann['content']); ?>

</div>

<?php endforeach; endif; ?>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">

        <a href="<?= BASE_URL ?>/" class="nav-logo">Dakkada<span>.LK</span></a>

        <div class="nav-links">

            <a href="<?= BASE_URL ?>/item/index" class="nav-link"><?= escape(t('browse')) ?></a>
            <a href="<?= BASE_URL ?>/home/success_stories" class="nav-link"><?= escape(t('success_stories')) ?></a>
            <a href="<?= BASE_URL ?>/map/index" class="nav-link"><?= escape(t('map_view')) ?></a>

            <?php if(isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/item/create?type=lost" class="nav-link"><?= escape(t('report_lost')) ?></a>
                <a href="<?= BASE_URL ?>/item/create?type=found" class="nav-link"><?= escape(t('report_found')) ?></a>

                <div class="nav-divider"></div>

                <?php if($_SESSION['is_admin'] ?? false): ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link"><?= escape(t('admin_dashboard')) ?></a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/user/dashboard" class="nav-link"><?= escape(t('dashboard')) ?></a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/user/profile" class="nav-link"><?= escape(t('profile')) ?></a>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-secondary nav-btn-logout"><?= escape(t('logout')) ?></a>

            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="nav-link"><?= escape(t('login')) ?></a>
                <a href="<?= BASE_URL ?>/auth/register" class="btn btn-primary"><?= escape(t('signup')) ?></a>
            <?php endif; ?>

            <!-- Language -->
            <select onchange="window.location.href='<?= BASE_URL ?>/page/set_language/' + this.value">
                <option value="en" <?= current_lang() === 'en' ? 'selected' : '' ?>>EN</option>
                <option value="si" <?= current_lang() === 'si' ? 'selected' : '' ?>>SI</option>
                <option value="ta" <?= current_lang() === 'ta' ? 'selected' : '' ?>>TA</option>
            </select>

            <!-- Theme Toggle -->
            <div class="theme-switch-wrapper">

                <label class="theme-switch">
                    <input type="checkbox" id="checkbox" />

                    <div class="slider round">
                        <span class="icon-sun"><i class="fa-solid fa-sun"></i></span>
                        <span class="icon-moon"><i class="fa-solid fa-moon"></i></span>
                        <div class="slider-circle"></div>
                    </div>

                </label>

            </div>

        </div>
    </div>
</nav>

<script>
const toggleSwitch = document.querySelector('#checkbox');
const currentTheme = localStorage.getItem('theme') || 'light';

if (currentTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
    if (toggleSwitch) toggleSwitch.checked = true;
}

if (toggleSwitch) {
    toggleSwitch.addEventListener('change', function(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
        }
    });
}
</script>

<main>
    <?php displayFlashMessages(); ?>
