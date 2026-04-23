<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">
<style>
    main {
        max-width: none;
        padding: 0;
    }
</style>

<div class="home-container">
    <section class="hero" id="top">
        <div class="hero-left">
            <div class="hero-badge"><span></span><?= escape(t('home_community_network')) ?></div>
            <h1><?= t('home_hero_title_html') ?></h1>
            <p class="hero-sub"><?= escape(t('home_hero_sub')) ?></p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/item/create?type=lost" class="btn btn-red btn-lg"><?= escape(t('home_lost_cta')) ?></a>
                <a href="<?= BASE_URL ?>/item/create?type=found" class="btn btn-green btn-lg"><?= escape(t('home_found_cta')) ?></a>
            </div>
            <div class="hero-stats">
                <div><div class="stat-num"><?= count($recentItems ?? []) ?></div><div class="stat-label"><?= escape(t('home_recent_reports')) ?></div></div>
                <div><div class="stat-num">24/7</div><div class="stat-label"><?= escape(t('home_platform_access')) ?></div></div>
                <div><div class="stat-num"><?= escape(t('home_safe')) ?></div><div class="stat-label"><?= escape(t('home_recovery_flow')) ?></div></div>
            </div>
        </div>

         <div class="hero-right">
            <div class="illus-wrap">
                <img src="https://img.freepik.com/free-vector/hand-drawn-no-data-illustration_23-2150696455.jpg?semt=ais_hybrid&w=740&q=80" alt="<?= escape(t('home_illustration_alt')) ?>">
                <div class="float-chip c1"><div class="dot dot-r"></div><?= escape(t('home_lost_active')) ?></div>
                <div class="float-chip c2"><div class="dot dot-g"></div><?= escape(t('home_found_matched')) ?></div>
            </div>
        </div>
    </section>

    <section id="items" class="section bg-white">
        <div class="section-inner">
            <div class="eyebrow"><?= escape(t('home_latest_activity')) ?></div>
            <h2 class="sec-title"><?= t('home_recently_reported_html') ?></h2>
            <p class="sec-sub"><?= escape(t('home_recent_sub')) ?></p>

            <?php if (empty($recentItems)): ?>
                <div class="empty-state"><?= escape(t('home_no_items')) ?></div>
            <?php else: ?>
                <div class="items-grid">
                    <?php foreach ($recentItems as $item): ?>
                        <div class="item-card" onclick="window.location.href='<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>'">
                            <div class="item-img" <?php if (!empty($item['image_path'])): ?>style="background: url('<?= BASE_URL ?>/uploads/<?= htmlspecialchars($item['image_path']) ?>') center/cover;"<?php endif; ?>>
                                <?php if (empty($item['image_path'])): ?>
                                    <i class="fa-regular fa-image"></i>
                                <?php endif; ?>
                                <?php if ($item['type'] === 'lost'): ?>
                                    <span class="badge badge-lost"><span class="bd bd-r"></span><?= escape(t('lost')) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-found"><span class="bd bd-g"></span><?= escape(t('found')) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="item-body">
                                <div class="item-title"><?= escape($item['title']) ?></div>
                                <div class="item-meta"><span><?= formatDate($item['date_posted']) ?></span></div>
                                <div class="item-desc"><?= escape(substr($item['description'] ?? '', 0, 120)) ?><?= strlen($item['description'] ?? '') > 120 ? '...' : '' ?></div>
                                <div class="item-footer">
                                    <span class="item-loc"><i class="fa-solid fa-location-dot"></i> <?= escape($item['location']) ?></span>
                                    <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-outline btn-xs"><?= escape(t('view')) ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

