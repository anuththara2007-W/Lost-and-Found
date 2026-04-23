<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/item-form.css">

<div class="form-container item-form-wrapper">
    <div class="form-header">
        <?php if($type === 'lost'): ?>
            <h2 class="item-form-title-lost">Report a Lost Item</h2>
            <p>We hope you find it quickly. Please provide as much detail as possible.</p>
        <?php else: ?>
            <h2 class="item-form-title-found">Report a Found Item</h2>
            <p>Thank you for helping! Let's get this back to its rightful owner.</p>
        <?php endif; ?>
    </div>

     <form action="<?= BASE_URL ?>/item/create" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="<?= escape($type) ?>">

        <div class="input-group">
            <label class="input-label" for="title">Item Name (Short Reference)</label>
            <input type="text" id="title" name="title" class="input-field" placeholder="e.g. Blue Hydro Flask" required value="<?= old('title') ?>">
        </div>

        <div class="input-group">
            <label class="input-label" for="category_id">Category</label>
            <select id="category_id" name="category_id" class="input-field" required onchange="toggleCustomCategory()">
                <option value="">-- Select Category --</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= old('category_id') == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
                <option value="other" <?= old('category_id') == 'other' ? 'selected' : '' ?>>Other (Specify Custom Category)</option>
            </select>
        </div>

        <div class="input-group" id="custom_category_group" style="display: <?= old('category_id') == 'other' ? 'block' : 'none' ?>;">
            <label class="input-label" for="custom_category">Custom Category Name</label>
            <input type="text" id="custom_category" name="custom_category" class="input-field" placeholder="e.g. Drones" value="<?= old('custom_category') ?>">
        </div>

        <div class="input-group">
            <label class="input-label" for="location">General Location Area *</label>
            <input type="text" id="location" name="location" class="input-field" placeholder="e.g. Central Library, Level 2" required value="<?= old('location') ?>">
        </div>

         <div class="input-group">
            <label class="input-label">Pinpoint Exact Location (Optional but recommended)</label>
            <p class="item-form-map-hint">Click on the map to set a precise pin for this item.</p>
            <div id="pickerMap" class="item-form-map-container"></div>
            <input type="hidden" id="latitude" name="latitude" value="<?= old('latitude') ?>">
            <input type="hidden" id="longitude" name="longitude" value="<?= old('longitude') ?>">
            <div class="item-form-coords-display">
                <div>Lat: <span id="latDisplay">--</span></div>
                <div>Lng: <span id="lngDisplay">--</span></div>
            </div>
        </div>

         <div class="input-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label class="input-label" for="description" style="margin-bottom: 0;">Detailed Description</label>
                <button type="button" onclick="generateDescription()" class="btn-secondary" style="padding: 2px 8px; font-size: 11px; border-radius: 4px; color: #d97706; border-color: #fcd34d; background: #fef3c7;">
                    <i class="fas fa-magic"></i> AI Generate
                </button>
            </div>
            <textarea id="description" name="description" class="input-field" rows="4" placeholder="Distinctive features, contents, brand, colors..." required><?= old('description') ?></textarea>
        </div>

         <div class="input-group">
            <label class="input-label" for="contact_info">Contact Info (Optional)</label>
            <input type="text" id="contact_info" name="contact_info" class="input-field" placeholder="Phone number or specific instructions" value="<?= old('contact_info') ?>">
        </div>

        <div class="input-group">
            <label class="input-label" for="whatsapp_contact">WhatsApp Number (Optional)</label>
            <input type="text" id="whatsapp_contact" name="whatsapp_contact" class="input-field" placeholder="Number to be contacted via WhatsApp..." value="<?= old('whatsapp_contact') ?>">
        </div>

         <div class="input-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px; margin-bottom: 20px;">
            <input type="checkbox" id="allow_platform_message" name="allow_platform_message" value="1" <?= (empty($_SESSION['old']) || old('allow_platform_message')) ? 'checked' : '' ?> style="width: 16px; height: 16px;">
            <label class="input-label" for="allow_platform_message" style="margin-bottom: 0;">Allow user to message me through platform</label>
        </div>

        <?php if($type === 'lost'): ?>
        <div class="input-group">
            <label class="input-label" for="reward_amount">Reward Amount (Optional)</label>
            <div style="display:flex; align-items:center;">
                <span style="padding: 12px 16px; background: var(--warm-mid); color: white; border-radius: 8px 0 0 8px; font-weight: bold;">$</span>
                <input type="number" step="0.01" min="0" id="reward_amount" name="reward_amount" class="input-field" placeholder="0.00" value="<?= old('reward_amount') ?>" style="border-radius: 0 8px 8px 0;">
            </div>
            <p class="item-form-map-hint" style="margin-top: 5px;">Offering a reward increases the chances of your item being returned.</p>
        </div>
        <?php endif; ?>

        <div class="input-group mb-20">
            <label class="input-label" for="image">Upload Images (Optional, multiple allowed)</label>
            <input type="file" id="image" name="images[]" multiple class="input-field item-form-file-input" accept="image/*">
        </div>

        <button type="submit" class="btn <?= $type === 'lost' ? 'btn-primary' : 'btn-found' ?> w-full item-form-submit-btn">
            Submit Report
        </button>
    </form>
</div>

<!-- LEAFLET MAP INJECTION -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function toggleCustomCategory() {
    var select = document.getElementById('category_id');
    var customGroup = document.getElementById('custom_category_group');
    if (select.value === 'other') {
        customGroup.style.display = 'block';
        document.getElementById('custom_category').required = true;
    } else {
        customGroup.style.display = 'none';
        document.getElementById('custom_category').required = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Default center on Sri Lanka
    var defaultLat = 7.8731;
    var defaultLng = 80.7718;
    
    var existingLat = document.getElementById('latitude').value;
    var existingLng = document.getElementById('longitude').value;
    
    var startLat = existingLat ? parseFloat(existingLat) : defaultLat;
    var startLng = existingLng ? parseFloat(existingLng) : defaultLng;
    var startZoom = existingLat ? 15 : 7;

    // Initialize Map
    var map = L.map('pickerMap').setView([startLat, startLng], startZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = null;

    // If we have existing data from a failed form sub, immediately place it
    if(existingLat && existingLng) {
        marker = L.marker([startLat, startLng]).addTo(map);
        document.getElementById('latDisplay').textContent = startLat.toFixed(5);
        document.getElementById('lngDisplay').textContent = startLng.toFixed(5);
    }

    // Handle Map Clicks
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Update hidden inputs
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Update display text
        document.getElementById('latDisplay').textContent = lat.toFixed(5);
        document.getElementById('lngDisplay').textContent = lng.toFixed(5);

        // Move or create marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    });
});

function generateDescription() {
    var title = document.getElementById('title').value;
    var type = "<?= escape($type) ?>";
    if (!title) {
        alert('Please enter an Item Name first so the AI can generate a description!');
        return;
    }
    
    var btn = document.querySelector('button[onclick="generateDescription()"]');
    var originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    
    // Simulate AI generation delay seamlessly
    setTimeout(function() {
        var desc = "";
        if (type === 'lost') {
            desc = "I recently lost my " + title + " in this general area. It holds significant value to me. If anyone has seen it or picked it up, please contact me immediately. A reward is available for its safe return.";
        } else {
            desc = "I found a " + title + " abandoned at this location. It looks fully intact. I am holding onto it safely. If this belongs to you, please message me through the platform with identifying details so I can return it.";
        }
        
        document.getElementById('description').value = desc;
        btn.innerHTML = originalHtml;
    }, 800);
}
/ AI Auto-Tagging via JS Heuristics
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');
    const categorySelect = document.getElementById('category_id');

    const keywords = {
        'electronics': ['phone', 'iphone', 'samsung', 'laptop', 'macbook', 'ipad', 'tablet', 'charger', 'headphones', 'airpods', 'earbuds', 'camera', 'watch', 'apple watch'],
        'keys': ['key', 'keys', 'car key', 'house keys', 'fob'],
        'wallet': ['wallet', 'purse', 'cardholder', 'cash', 'money', 'credit card'],
        'documents': ['id', 'passport', 'license', 'certificate', 'document', 'folder', 'binder'],
        'clothing': ['jacket', 'coat', 'sweater', 'hoodie', 'hat', 'cap', 'scarf', 'gloves', 'shoes', 'sneakers'],
        'jewelry': ['ring', 'necklace', 'bracelet', 'earring', 'jewelry', 'gold', 'silver', 'diamond'],
        'bag': ['bag', 'backpack', 'tote', 'luggage', 'suitcase', 'briefcase']
    };

    function autoTag() {
        // Only auto tag if user hasn't manually selected something yet
        if (categorySelect.value !== '') return;
        
        let text = (titleInput.value + ' ' + descInput.value).toLowerCase();
        
        let foundCategory = null;
        for (let cat in keywords) {
            if (keywords[cat].some(kw => text.includes(kw))) {
                foundCategory = cat;
                break;
            }
        }

        if (foundCategory) {
            // Find option matching this category text partially
            let options = categorySelect.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].text.toLowerCase().includes(foundCategory)) {
                    categorySelect.selectedIndex = i;
                    toggleCustomCategory(); // Trigger the related UI updates if any
                    
                    // Show a tiny feedback
                    let lbl = document.querySelector('label[for="category_id"]');
                    let originalHTML = lbl.innerHTML;
                    lbl.innerHTML = 'Category <span style="color:#34c759; font-size:11px; margin-left:5px;"><i class="fas fa-magic"></i> Auto-tagged!</span>';
                    setTimeout(() => lbl.innerHTML = originalHTML, 3000);
                    break;
                }
            }
        }
    }

    titleInput.addEventListener('blur', autoTag);
    descInput.addEventListener('blur', autoTag);
});
</script>
