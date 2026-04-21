<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<?php
$allItems = $data['items'] ?? [];
$lostItems = [];

foreach ($allItems as $item) {
    $type = strtolower(trim($item['type'] ?? ''));
    if ($type === 'lost') {
        $lostItems[] = $item;
    }
}
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/map.css">

<div class="map-container">
    <div class="map-header">
        <div>
            <h1 class="map-title">Interactive Map</h1>
            <p class="map-subtitle">
                Browse reported lost items by their geographical location.
                <span class="card-badge map-badge map-badge-lost">Lost</span>
            </p>
        </div>
    </div>

    <div class="map-wrapper">
        <div id="laf-map" class="laf-map-element"></div>
    </div>

    <?php if (empty($lostItems)): ?>
        <div class="map-empty-note">No lost items available to show on the map.</div>
    <?php endif; ?>
</div>
