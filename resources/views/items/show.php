<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/item-detail.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="item-detail-container">

    <!-- Back Button -->
    <a href="<?= BASE_URL ?>/home/index" class="item-detail-back-link">
        &larr; Back to Recent
    </a>

    <div class="item-detail-card">

        <!-- Left Side: Image -->
        <div class="item-detail-left">
