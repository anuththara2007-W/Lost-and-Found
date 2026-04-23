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
