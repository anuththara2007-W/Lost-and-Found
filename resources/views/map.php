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

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('laf-map');

    if (!mapElement) {
        console.error('Map element not found');
        return;
    }

    if (typeof L === 'undefined') {
        mapElement.innerHTML = '<div style="padding:20px;color:red;">Leaflet failed to load.</div>';
        return;
    }

    const items = <?= json_encode($lostItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const baseUrl = <?= json_encode(BASE_URL) ?>;

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function validLatLng(lat, lng) {
        return Number.isFinite(lat) &&
               Number.isFinite(lng) &&
               lat >= -90 && lat <= 90 &&
               lng >= -180 && lng <= 180;
    }

    const map = L.map('laf-map').setView([7.8731, 80.7718], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    const iconLost = L.divIcon({
        className: 'custom-div-icon',
        html: "<div class='custom-marker-lost'></div>",
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });

    const markerLayer = L.layerGroup().addTo(map);

    function renderMarkers(inputItems, fitView) {
        markerLayer.clearLayers();
        const bounds = [];
        let addedCount = 0;

        inputItems.forEach(function (item, index) {
            let lat = parseFloat(item.latitude);
            let lng = parseFloat(item.longitude);
            if (!validLatLng(lat, lng)) {
                lat = 6.9271 + ((index % 10) * 0.02);
                lng = 79.8612 + ((index % 10) * 0.02);
            }
            const safeTitle = escapeHtml(item.title || 'Lost Item');
            const safeLocation = escapeHtml(item.location || 'Unknown Location');
            const reportId = encodeURIComponent(item.report_id || '');

            const popupContent = `
                <div class="popup-container">
                    <div class="popup-badge-wrapper"><span class="popup-badge-lost">LOST</span></div>
                    <strong class="popup-title">${safeTitle}</strong>
                    <div class="popup-location"><i class="fa-solid fa-location-dot"></i> ${safeLocation}</div>
                    <a href="${baseUrl}/item/show/${reportId}" class="popup-link">View Details &rarr;</a>
                </div>
            `;

            L.marker([lat, lng], { icon: iconLost }).addTo(markerLayer).bindPopup(popupContent);
            bounds.push([lat, lng]);
            addedCount++;
        });

        if (fitView) {
            if (addedCount > 0) {
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
            } else {
                map.setView([7.8731, 80.7718], 7);
            }
        }
    }

    renderMarkers(items, true);

    async function refreshMarkers() {
        try {
            const res = await fetch(`${baseUrl}/map/api_markers`, { credentials: 'same-origin' });
            const data = await res.json();
            const liveLost = Array.isArray(data.items) ? data.items.filter(x => String(x.type || '').toLowerCase() === 'lost') : [];
            renderMarkers(liveLost, false);
        } catch (e) {
            console.error('Live map refresh failed', e);
        }
    }
    setInterval(refreshMarkers, 10000);

    setTimeout(function () {
        map.invalidateSize();
    }, 300);

    window.addEventListener('load', function () {
        map.invalidateSize();
    });

    window.addEventListener('resize', function () {
        map.invalidateSize();
    });
});
</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
