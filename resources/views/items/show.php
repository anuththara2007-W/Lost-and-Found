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

        <!-- Timeline UI -->
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding: 15px; background: var(--bg-primary); border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="text-align: center; flex: 1;">
                    <div
                        style="width: 30px; height: 30px; border-radius: 50%; background: var(--surface-text); color: white; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 5px;">
                        1</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--midnight);">Reported</div>
                </div>
                <div
                    style="flex: 1; height: 3px; background: <?= !empty($comments) ? 'var(--surface-text)' : '#e2e8f0' ?>; margin: 0 10px;">
                </div>
                <div style="text-align: center; flex: 1;">
                    <div
                        style="width: 30px; height: 30px; border-radius: 50%; background: <?= !empty($comments) ? 'var(--surface-text)' : '#e2e8f0' ?>; color: <?= !empty($comments) ? 'white' : 'var(--clay)' ?>; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 5px;">
                        2</div>
                    <div
                        style="font-size: 11px; font-weight: 600; color: <?= !empty($comments) ? 'var(--midnight)' : 'var(--clay)' ?>;">
                        Activity</div>
                </div>
                <div
                    style="flex: 1; height: 3px; background: <?= $item['status'] === 'resolved' ? 'var(--success, #34c759)' : '#e2e8f0' ?>; margin: 0 10px;">
                </div>
                <div style="text-align: center; flex: 1;">
                    <div
                        style="width: 30px; height: 30px; border-radius: 50%; background: <?= $item['status'] === 'resolved' ? 'var(--success, #34c759)' : '#e2e8f0' ?>; color: <?= $item['status'] === 'resolved' ? 'white' : 'var(--clay)' ?>; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 5px;">
                        3</div>
                    <div
                        style="font-size: 11px; font-weight: 600; color: <?= $item['status'] === 'resolved' ? 'var(--midnight)' : 'var(--clay)' ?>;">
                        Resolved</div>
                </div>
            </div>
