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

            <div class="browse-more">
                <a href="<?= BASE_URL ?>/item/index" class="btn btn-ghost"><?= escape(t('home_browse_all')) ?></a>
            </div>
        </div>
    </section>

     <section id="how" class="section">
        <div class="section-inner">
            <div class="eyebrow"><?= escape(t('home_process')) ?></div>
            <h2 class="sec-title"><?= t('home_how_recovery_html') ?></h2>
            <p class="sec-sub"><?= escape(t('home_three_steps')) ?></p>
            <div class="steps-grid">
                <div class="step-card" data-num="01">
                    <div class="step-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="step-title"><?= escape(t('home_step1_title')) ?></div>
                    <p class="step-text"><?= escape(t('home_step1_text')) ?></p>
                </div>
                <div class="step-card" data-num="02">
                    <div class="step-icon"><i class="fa-solid fa-link"></i></div>
                    <div class="step-title"><?= escape(t('home_step2_title')) ?></div>
                    <p class="step-text"><?= escape(t('home_step2_text')) ?></p>
                </div>
                <div class="step-card" data-num="03">
                    <div class="step-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="step-title"><?= escape(t('home_step3_title')) ?></div>
                    <p class="step-text"><?= escape(t('home_step3_text')) ?></p>
                </div>
            </div>
        </div>
    </section>

      <section id="categories" class="section bg-white">
        <div class="section-inner">
            <div class="eyebrow"><?= escape(t('home_item_types')) ?></div>
            <h2 class="sec-title"><?= t('home_browse_category_html') ?></h2>
            <p class="sec-sub"><?= escape(t('home_category_sub')) ?></p>
            <div class="cats-grid">
                <a href="<?= BASE_URL ?>/item/search?category_id=1" class="cat-card"><div class="cat-icon"><i class="fa-regular fa-id-card"></i></div><div class="cat-name"><?= escape(t('home_cat_identification')) ?></div><div class="cat-count"><?= escape(t('home_cat_identification_sub')) ?></div></a>
                <a href="<?= BASE_URL ?>/item/search?category_id=2" class="cat-card"><div class="cat-icon"><i class="fa-solid fa-mobile-screen-button"></i></div><div class="cat-name"><?= escape(t('home_cat_electronics')) ?></div><div class="cat-count"><?= escape(t('home_cat_electronics_sub')) ?></div></a>
                <a href="<?= BASE_URL ?>/item/search?category_id=3" class="cat-card"><div class="cat-icon"><i class="fa-regular fa-credit-card"></i></div><div class="cat-name"><?= escape(t('home_cat_wallets')) ?></div><div class="cat-count"><?= escape(t('home_cat_wallets_sub')) ?></div></a>
                <a href="<?= BASE_URL ?>/item/search?category_id=4" class="cat-card"><div class="cat-icon"><i class="fa-solid fa-key"></i></div><div class="cat-name"><?= escape(t('home_cat_keys')) ?></div><div class="cat-count"><?= escape(t('home_cat_keys_sub')) ?></div></a>
                <a href="<?= BASE_URL ?>/item/search?category_id=5" class="cat-card"><div class="cat-icon"><i class="fa-solid fa-paw"></i></div><div class="cat-name"><?= escape(t('home_cat_pets')) ?></div><div class="cat-count"><?= escape(t('home_cat_pets_sub')) ?></div></a>
                <a href="<?= BASE_URL ?>/item/search?category_id=6" class="cat-card"><div class="cat-icon"><i class="fa-solid fa-suitcase"></i></div><div class="cat-name"><?= escape(t('home_cat_bags')) ?></div><div class="cat-count"><?= escape(t('home_cat_bags_sub')) ?></div></a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-inner">
            <div class="split">
                <div>
                    <div class="split-title red"><?= escape(t('home_psychology_title')) ?></div>
                    <p class="split-text"><?= escape(t('home_psychology_text1')) ?></p>
                    <p class="split-text"><?= escape(t('home_psychology_text2')) ?></p>
                </div>
                <div>
                    <div class="split-title green"><?= escape(t('home_anatomy_title')) ?></div>
                    <p class="split-text"><?= escape(t('home_anatomy_text1')) ?></p>
                    <p class="split-text"><?= escape(t('home_anatomy_text2')) ?></p>
                </div>
            </div>
        </div>
    </section>

    <div class="trust-bar">
        <div class="trust-item"><div class="trust-icon"><i class="fa-solid fa-lock"></i></div><div class="trust-val"><?= escape(t('home_trust_secure')) ?></div><div class="trust-lbl"><?= escape(t('home_trust_secure_sub')) ?></div></div>
        <div class="trust-item"><div class="trust-icon"><i class="fa-solid fa-award"></i></div><div class="trust-val"><?= escape(t('home_trust_verified')) ?></div><div class="trust-lbl"><?= escape(t('home_trust_verified_sub')) ?></div></div>
        <div class="trust-item"><div class="trust-icon"><i class="fa-solid fa-map-location-dot"></i></div><div class="trust-val"><?= escape(t('home_trust_geo')) ?></div><div class="trust-lbl"><?= escape(t('home_trust_geo_sub')) ?></div></div>
        <div class="trust-item"><div class="trust-icon"><i class="fa-solid fa-bolt"></i></div><div class="trust-val"><?= escape(t('home_trust_alerts')) ?></div><div class="trust-lbl"><?= escape(t('home_trust_alerts_sub')) ?></div></div>
    </div>

    <section id="safety" class="section bg-white">
        <div class="section-inner">
            <div class="eyebrow"><?= escape(t('home_stay_safe')) ?></div>
            <h2 class="sec-title"><?= t('home_safe_handoff_html') ?></h2>
            <p class="sec-sub"><?= escape(t('home_safe_sub')) ?></p>
            <div class="safety-grid">
                <div class="safety-card"><h4><i class="fa-solid fa-city"></i> <?= escape(t('home_safe_public')) ?></h4><p><?= escape(t('home_safe_public_sub')) ?></p></div>
                <div class="safety-card"><h4><i class="fa-solid fa-circle-check"></i> <?= escape(t('home_safe_verify')) ?></h4><p><?= escape(t('home_safe_verify_sub')) ?></p></div>
                <div class="safety-card"><h4><i class="fa-solid fa-sun"></i> <?= escape(t('home_safe_daytime')) ?></h4><p><?= escape(t('home_safe_daytime_sub')) ?></p></div>
                <div class="safety-card"><h4><i class="fa-solid fa-user-group"></i> <?= escape(t('home_safe_support')) ?></h4><p><?= escape(t('home_safe_support_sub')) ?></p></div>
            </div>
        </div>
    </section>

