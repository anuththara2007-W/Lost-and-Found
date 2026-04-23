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

            <div class="item-gallery-container"
                style="position: relative; border-radius: 12px; overflow: hidden; background: var(--parchment); aspect-ratio: 4/3; box-shadow: 0 4px 12px rgba(0,0,0,0.05); cursor: zoom-in;"
                onclick="openLightbox(0)">
                <?php if (empty($images)): ?>
                    <div
                        style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--clay); font-weight: bold; background: #e2e8f0;">
                        [ NO PHOTO UPLOADED ]</div>
                <?php else: ?>
                    <img id="main-gallery-image" src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>"
                        style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s;"
                        alt="Main Item Image">
                    <?php if (count($images) > 1): ?>
                        <div
                            style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.6); color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                            <i class="fas fa-images"></i> +<?= count($images) - 1 ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($images) && count($images) > 1): ?>
                <div class="gallery-thumbnails"
                    style="display: flex; gap: 10px; margin-top: 10px; overflow-x: auto; padding-bottom: 5px;">
                    <?php foreach ($images as $index => $img): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>"
                            onclick="changeMainImage(<?= $index ?>)"
                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid <?= $index == 0 ? 'var(--surface-text)' : 'transparent' ?>; transition: border-color 0.2s;"
                            class="gallery-thumb" data-index="<?= $index ?>">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
