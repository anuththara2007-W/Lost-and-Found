<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<?php
// Get all items from backend
$allItems = $data['items'] ?? [];
$lostItems = [];

// Filter only "lost" type items
foreach ($allItems as $item) {
    $type = strtolower(trim($item['type'] ?? ''));
    if ($type === 'lost') {
        $lostItems[] = $item;
    }
}
?>

<!-- Leaflet Map Library (CSS) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/map.css">

<!-- Page Layout -->
<div class="map-container">

    <!-- Header Section -->
    <div class="map-header">
        <div>
            <h1 class="map-title">Interactive Map</h1>
            <p class="map-subtitle">
                Browse reported lost items by their geographical location.
                <span class="card-badge map-badge map-badge-lost">Lost</span>
            </p>
        </div>
    </div>

    <!-- Map Element (appear) -->
    <div class="map-wrapper">
        <div id="laf-map" class="laf-map-element"></div>
    </div>

    <!-- msg if no lost items -->
    <?php if (empty($lostItems)): ?>
        <div class="map-empty-note">No lost items available to show on the map.</div>
    <?php endif; ?>

</div>

<!-- Leaflet Map Library (JS) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Safety Checks (prevent errors if,)
    const mapElement = document.getElementById('laf-map');

    if (!mapElement) {
        console.error('Map element not found');
        return;
    }

    if (typeof L === 'undefined') {
        mapElement.innerHTML = '<div style="padding:20px;color:red;">Leaflet failed to load.</div>';
        return;
    }

    // Pass lost items PHP to JS
    const items   = <?= json_encode($lostItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const baseUrl = <?= json_encode(BASE_URL) ?>;


    // To prevent XSS attacks
    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Check longitidinal & Laditudinal values are in correct range
    function validLatLng(lat, lng) {
        return Number.isFinite(lat) &&
               Number.isFinite(lng) &&
               lat >= -90  && lat <= 90 &&
               lng >= -180 && lng <= 180;
    }

    // map centered to SL 
    const map = L.map('laf-map').setView([7.8731, 80.7718], 7);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // red dot icon for lost items
    const iconLost = L.divIcon({
        className: 'custom-div-icon',
        html: "<div class='custom-marker-lost'></div>",
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });

    // to hold all markers
    const markerLayer = L.layerGroup().addTo(map);

    // Place markers on the map for each lost item
    function renderMarkers(inputItems, fitView) {
        markerLayer.clearLayers(); // Remove old markers
        const bounds = [];
        let addedCount = 0;

        inputItems.forEach(function (item, index) {

            // if given locations is invalid, the marker drop near colombo
            let lat = parseFloat(item.latitude);
            let lng = parseFloat(item.longitude);
            if (!validLatLng(lat, lng)) { //    // Set default Sri Lanka location (Colombo base)

                lat = 6.9271 + ((index % 10) * 0.02);
                lng = 79.8612 + ((index % 10) * 0.02);
            }

            // Sanitize data before display
            const safeTitle    = escapeHtml(item.title    || 'Lost Item');
            const safeLocation = escapeHtml(item.location || 'Unknown Location');
            const reportId     = encodeURIComponent(item.report_id || '');

            // popup msg
            const popupContent = `
                <div class="popup-container">
                    <div class="popup-badge-wrapper"><span class="popup-badge-lost">LOST</span></div>
                    <strong class="popup-title">${safeTitle}</strong>
                    <div class="popup-location"><i class="fa-solid fa-location-dot"></i> ${safeLocation}</div>
                    <a href="${baseUrl}/item/show/${reportId}" class="popup-link">View Details &rarr;</a>
                </div>
            `;

            // Add those popup contents
            L.marker([lat, lng], { icon: iconLost }).addTo(markerLayer).bindPopup(popupContent);
            bounds.push([lat, lng]);
            addedCount++;
        });

        // Zoom map to fit all markers if requested
        if (fitView) {
            if (addedCount > 0) {
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
            } else {
                map.setView([7.8731, 80.7718], 7); // Default view if no markers
            }
        }
    }

    // show markers when page load
    renderMarkers(items, true);

    // live update & refresh 
    async function refreshMarkers() {
        try {
            const res  = await fetch(`${baseUrl}/map/api_markers`, { credentials: 'same-origin' });
            const data = await res.json();

            const liveLost = Array.isArray(data.items)
                ? data.items.filter(x => String(x.type || '').toLowerCase() === 'lost')
                : [];

            renderMarkers(liveLost, false); // Re-render without changing zoom
        } catch (e) {
            console.error('Live map refresh failed', e);
        }
    }

    setInterval(refreshMarkers, 10000); // Refresh every 10s

    // Map Resize Fixes 
    setTimeout(() => map.invalidateSize(), 300);        // After initial load
    window.addEventListener('load',   () => map.invalidateSize()); // After all assets load
    window.addEventListener('resize', () => map.invalidateSize()); // On window resize

});
</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>