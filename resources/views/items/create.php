<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/item-form.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

<div class="item-form-wrapper">

    <!-- Page heading -->
    <div class="form-header">
        <?php if ($type === 'lost'): ?>
            <h2 class="item-form-title-lost">Report a Lost Item</h2>
            <p>We hope you find it quickly. Please provide as much detail as possible.</p>
        <?php else: ?>
            <h2 class="item-form-title-found">Report a Found Item</h2>
            <p>Thank you for helping! Let's get this back to its rightful owner.</p>
        <?php endif; ?>
    </div>

    <form action="<?= BASE_URL ?>/item/create" method="POST" enctype="multipart/form-data">

        <!-- Tells the server if this is a lost or found report -->
        <input type="hidden" name="type" value="<?= escape($type) ?>">

        <!-- Item Name -->
        <div class="input-group full-row">
            <label class="input-label" for="title">Item Name (Short Reference)</label>
            <input type="text" id="title" name="title" class="input-field"
                   placeholder="e.g. Blue Hydro Flask" required value="<?= old('title') ?>">
        </div>

        <!-- Category Dropdown -->
        <div class="input-group">
            <label class="input-label" for="category_id">Category</label>
            <select id="category_id" name="category_id" class="input-field"
                    required onchange="toggleCustomCategory()">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"
                        <?= old('category_id') == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
                <option value="other" <?= old('category_id') == 'other' ? 'selected' : '' ?>>
                    Other (Specify Custom Category)
                </option>
            </select>
        </div>

        <!-- Custom Category — only shown when "Other" is selected -->
        <div class="input-group <?= old('category_id') === 'other' ? '' : 'item-form-hidden' ?>"
             id="custom_category_group">
            <label class="input-label" for="custom_category">Custom Category Name</label>
            <input type="text" id="custom_category" name="custom_category"
                   class="input-field" placeholder="e.g. Drones" value="<?= old('custom_category') ?>">
        </div>

        <!-- General Location -->
        <div class="input-group">
            <label class="input-label" for="location">General Location Area *</label>
            <input type="text" id="location" name="location" class="input-field"
                   placeholder="e.g. Central Library, Level 2" required value="<?= old('location') ?>">
        </div>

        <!-- Map Pin Picker -->
        <div class="input-group full-row">
            <label class="input-label">Pinpoint Exact Location (Optional but recommended)</label>
            <p class="item-form-map-hint">Click on the map to set a precise pin for this item.</p>
            <div id="pickerMap" class="item-form-map-container"></div>
            <input type="hidden" id="latitude"  name="latitude"  value="<?= old('latitude') ?>">
            <input type="hidden" id="longitude" name="longitude" value="<?= old('longitude') ?>">
            <div class="item-form-coords-display">
                <div>Lat: <span id="latDisplay">--</span></div>
                <div>Lng: <span id="lngDisplay">--</span></div>
            </div>
        </div>

        <!-- Description + AI Generate button -->
        <div class="input-group full-row">
            <div class="item-form-desc-header">
                <label class="input-label item-form-desc-label" for="description">Detailed Description</label>
                <button type="button" onclick="generateDescription()" class="btn-ai-generate">
                    <i class="fas fa-magic"></i> AI Generate
                </button>
            </div>
            <textarea id="description" name="description" class="input-field" rows="4"
                      placeholder="Distinctive features, contents, brand, colors..."
                      required><?= old('description') ?></textarea>
        </div>

        <!-- Contact Info -->
        <div class="input-group">
            <label class="input-label" for="contact_info">Contact Info (Optional)</label>
            <input type="text" id="contact_info" name="contact_info" class="input-field"
                   placeholder="Phone number or specific instructions" value="<?= old('contact_info') ?>">
        </div>

        <!-- WhatsApp Number -->
        <div class="input-group">
            <label class="input-label" for="whatsapp_contact">WhatsApp Number (Optional)</label>
            <input type="text" id="whatsapp_contact" name="whatsapp_contact" class="input-field"
                   placeholder="Number to be contacted via WhatsApp..." value="<?= old('whatsapp_contact') ?>">
        </div>

        <!-- Platform Messaging Checkbox -->
        <div class="input-group full-row item-form-checkbox-row">
            <input type="checkbox" id="allow_platform_message" name="allow_platform_message"
                   value="1" class="item-form-checkbox"
                   <?= (empty($_SESSION['old']) || old('allow_platform_message')) ? 'checked' : '' ?>>
            <label class="input-label item-form-checkbox-label" for="allow_platform_message">
                Allow user to message me through platform
            </label>
        </div>

        <!-- Reward Amount — lost items only -->
        <?php if ($type === 'lost'): ?>
            <div class="input-group full-row">
                <label class="input-label" for="reward_amount">Reward Amount (Optional)</label>
                <div class="item-form-reward-row">
                    <span class="item-form-reward-prefix">$</span>
                    <input type="number" step="0.01" min="0" id="reward_amount" name="reward_amount"
                           class="input-field item-form-reward-input" placeholder="0.00"
                           value="<?= old('reward_amount') ?>">
                </div>
                <p class="item-form-map-hint item-form-reward-hint">
                    Offering a reward increases the chances of your item being returned.
                </p>
            </div>
        <?php endif; ?>

        <!-- Image Upload -->
        <div class="input-group full-row mb-20">
            <label class="input-label" for="image">Upload Images (Optional, multiple allowed)</label>
            <input type="file" id="image" name="images[]" multiple
                   class="input-field item-form-file-input" accept="image/*">
        </div>

        <!-- Submit — red for lost, green for found -->
        <button type="submit"
                class="btn <?= $type === 'lost' ? 'btn-primary' : 'btn-found' ?> w-full item-form-submit-btn full-row">
            Submit Report
        </button>

    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>

// 1. Show/hide the custom category input when "Other" is selected
function toggleCustomCategory() {
    const isOther = document.getElementById('category_id').value === 'other';
    const group   = document.getElementById('custom_category_group');
    const input   = document.getElementById('custom_category');
    group.classList.toggle('item-form-hidden', !isOther);
    input.required = isOther;
}


// 2. Fill the description textarea with a generated template
function generateDescription() {
    const title = document.getElementById('title').value;
    const type  = "<?= escape($type) ?>";

    if (!title) {
        alert('Please enter an Item Name first so the AI can generate a description!');
        return;
    }

    // Show spinner on the button while generating
    const btn          = document.querySelector('button[onclick="generateDescription()"]');
    const originalHtml = btn.innerHTML;
    btn.innerHTML      = '<i class="fas fa-spinner fa-spin"></i> Generating...';

    setTimeout(() => {
        document.getElementById('description').value = type === 'lost'
            ? `I recently lost my ${title} in this general area. It holds significant value to me. If anyone has seen it or picked it up, please contact me immediately. A reward is available for its safe return.`
            : `I found a ${title} abandoned at this location. It looks fully intact. I am holding onto it safely. If this belongs to you, please message me through the platform with identifying details so I can return it.`;

        btn.innerHTML = originalHtml;
    }, 800);
}


document.addEventListener('DOMContentLoaded', () => {

    // 3. Leaflet map — click anywhere to place a pin and save the coordinates
    const savedLat = document.getElementById('latitude').value;
    const savedLng = document.getElementById('longitude').value;
    const startLat = savedLat ? parseFloat(savedLat) : 7.8731; // Default: Sri Lanka
    const startLng = savedLng ? parseFloat(savedLng) : 80.7718;

    const map = L.map('pickerMap').setView([startLat, startLng], savedLat ? 15 : 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Place a pin if coordinates were already saved (e.g. after a failed submit)
    let marker = savedLat ? L.marker([startLat, startLng]).addTo(map) : null;

    if (savedLat) {
        document.getElementById('latDisplay').textContent = parseFloat(savedLat).toFixed(5);
        document.getElementById('lngDisplay').textContent = parseFloat(savedLng).toFixed(5);
    }

    map.on('click', ({ latlng }) => {
        const { lat, lng } = latlng;

        // Save to hidden inputs so they submit with the form
        document.getElementById('latitude').value         = lat;
        document.getElementById('longitude').value        = lng;
        document.getElementById('latDisplay').textContent = lat.toFixed(5);
        document.getElementById('lngDisplay').textContent = lng.toFixed(5);

        // Move existing pin or create a new one
        marker ? marker.setLatLng(latlng) : (marker = L.marker(latlng).addTo(map));
    });


    // 4. Auto-tagger — detect category from keywords in the title/description
    const keywords = {
        'electronics': ['phone', 'iphone', 'samsung', 'laptop', 'macbook', 'ipad', 'tablet',
                        'charger', 'headphones', 'airpods', 'earbuds', 'camera', 'watch', 'apple watch'],
        'keys'       : ['key', 'keys', 'car key', 'house keys', 'fob'],
        'wallet'     : ['wallet', 'purse', 'cardholder', 'cash', 'money', 'credit card'],
        'documents'  : ['id', 'passport', 'license', 'certificate', 'document', 'folder', 'binder'],
        'clothing'   : ['jacket', 'coat', 'sweater', 'hoodie', 'hat', 'cap',
                        'scarf', 'gloves', 'shoes', 'sneakers'],
        'jewelry'    : ['ring', 'necklace', 'bracelet', 'earring', 'jewelry', 'gold', 'silver', 'diamond'],
        'bag'        : ['bag', 'backpack', 'tote', 'luggage', 'suitcase', 'briefcase']
    };

    function autoTag() {
        const select = document.getElementById('category_id');
        if (select.value !== '') return; // Don't override a manual selection

        const text = (document.getElementById('title').value + ' ' +
                      document.getElementById('description').value).toLowerCase();

        // Find the first category whose keywords appear in the text
        const match = Object.entries(keywords).find(([, words]) => words.some(w => text.includes(w)));
        if (!match) return;

        // Select the matching option in the dropdown
        const option = [...select.options].find(o => o.text.toLowerCase().includes(match[0]));
        if (!option) return;

        select.value = option.value;
        toggleCustomCategory();

        // Flash an "Auto-tagged!" badge on the label for 3 seconds
        const lbl      = document.querySelector('label[for="category_id"]');
        const original = lbl.innerHTML;
        lbl.innerHTML  = 'Category <span class="item-form-autotag-badge"><i class="fas fa-magic"></i> Auto-tagged!</span>';
        setTimeout(() => lbl.innerHTML = original, 3000);
    }

    document.getElementById('title').addEventListener('blur', autoTag);
    document.getElementById('description').addEventListener('blur', autoTag);

});

</script>

<?php
clearOld();
require_once ROOT . '/resources/views/layouts/footer.php';
?>