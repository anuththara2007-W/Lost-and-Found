<?php
require_once ROOT . '/resources/views/layouts/header.php';
?>

<!-- Stylesheet for this page only -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/item-form.css">

<!-- Leaflet map CSS — loaded here so it applies to the picker map below -->
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="">


<!-- ════════════════════════════════════════════════════════
     PAGE WRAPPER
     ════════════════════════════════════════════════════════ -->
<div class="item-form-wrapper">

    <!-- ── Form Header ──────────────────────────────────────
         The title and subtitle change depending on whether
         the user is reporting a lost or found item.
    ─────────────────────────────────────────────────────── -->
    <div class="form-header">
        <?php if ($type === 'lost'): ?>
            <h2 class="item-form-title-lost">Report a Lost Item</h2>
            <p>We hope you find it quickly. Please provide as much detail as possible.</p>
        <?php else: ?>
            <h2 class="item-form-title-found">Report a Found Item</h2>
            <p>Thank you for helping! Let's get this back to its rightful owner.</p>
        <?php endif; ?>
    </div>


    <!-- ── The Main Form ────────────────────────────────────
         enctype="multipart/form-data" is required whenever
         the form includes a file upload field.
    ─────────────────────────────────────────────────────── -->
    <form action="<?= BASE_URL ?>/item/create" method="POST" enctype="multipart/form-data">

        <!-- Tell the server whether this is a 'lost' or 'found' report -->
        <input type="hidden" name="type" value="<?= escape($type) ?>">


        <!-- ── Item Name ─────────────────────────────────────
             A short label for the item (e.g. "Blue Hydro Flask").
             old('title') re-fills the field if the form was
             submitted but failed validation.
        ──────────────────────────────────────────────────── -->
        <div class="input-group full-row">
            <label class="input-label" for="title">Item Name (Short Reference)</label>
            <input type="text"
                   id="title"
                   name="title"
                   class="input-field"
                   placeholder="e.g. Blue Hydro Flask"
                   required
                   value="<?= old('title') ?>">
        </div>


        <!-- ── Category Dropdown ─────────────────────────────
             Loops through all categories from the database.
             Selecting "Other" reveals a free-text field below.
             onchange="toggleCustomCategory()" is the JS function
             that shows/hides that extra field.
        ──────────────────────────────────────────────────── -->
        <div class="input-group">
            <label class="input-label" for="category_id">Category</label>
            <select id="category_id"
                    name="category_id"
                    class="input-field"
                    required
                    onchange="toggleCustomCategory()">

                <option value="">-- Select Category --</option>

                <!-- Output one <option> per category row from the database -->
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"
                        <?= old('category_id') == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>

                <!-- The "Other" option lets users type a custom category name -->
                <option value="other"
                    <?= old('category_id') == 'other' ? 'selected' : '' ?>>
                    Other (Specify Custom Category)
                </option>

            </select>
        </div>


        <!-- ── Custom Category Field ─────────────────────────
             Hidden by default. The CSS class item-form-hidden
             sets display:none. JavaScript removes that class
             when the user picks "Other" from the dropdown above.
        ──────────────────────────────────────────────────── -->
        <div class="input-group <?= old('category_id') === 'other' ? '' : 'item-form-hidden' ?>"
             id="custom_category_group">
            <label class="input-label" for="custom_category">Custom Category Name</label>
            <input type="text"
                   id="custom_category"
                   name="custom_category"
                   class="input-field"
                   placeholder="e.g. Drones"
                   value="<?= old('custom_category') ?>">
        </div>


        <!-- ── General Location ──────────────────────────────
             A text description of the area (e.g. "Central Library, Level 2").
             The map below lets users add a precise pin on top of this.
        ──────────────────────────────────────────────────── -->
        <div class="input-group">
            <label class="input-label" for="location">General Location Area *</label>
            <input type="text"
                   id="location"
                   name="location"
                   class="input-field"
                   placeholder="e.g. Central Library, Level 2"
                   required
                   value="<?= old('location') ?>">
        </div>


        <!-- ── Map Pin Picker ─────────────────────────────────
             An interactive Leaflet map the user can click to
             place an exact pin. The chosen coordinates are stored
             in the two hidden inputs below so they get submitted
             with the rest of the form.
        ──────────────────────────────────────────────────── -->
        <div class="input-group full-row">
            <label class="input-label">Pinpoint Exact Location (Optional but recommended)</label>
            <p class="item-form-map-hint">Click on the map to set a precise pin for this item.</p>

            <!-- Leaflet renders the interactive map into this div -->
            <div id="pickerMap" class="item-form-map-container"></div>

            <!-- Hidden inputs that store the chosen lat/lng values -->
            <input type="hidden" id="latitude"  name="latitude"  value="<?= old('latitude') ?>">
            <input type="hidden" id="longitude" name="longitude" value="<?= old('longitude') ?>">

            <!-- Small coordinate readout shown below the map -->
            <div class="item-form-coords-display">
                <div>Lat: <span id="latDisplay">--</span></div>
                <div>Lng: <span id="lngDisplay">--</span></div>
            </div>
        </div>


        <!-- ── Description ───────────────────────────────────
             A multi-line text area for full details.
             The header row holds the label AND the AI Generate
             button side by side using a CSS flex class.
        ──────────────────────────────────────────────────── -->
        <div class="input-group full-row">

            <!-- Label + AI button sit on the same line -->
            <div class="item-form-desc-header">
                <label class="input-label item-form-desc-label" for="description">
                    Detailed Description
                </label>
                <!-- Clicking this button calls generateDescription() in JS below -->
                <button type="button"
                        onclick="generateDescription()"
                        class="btn-ai-generate">
                    <i class="fas fa-magic"></i> AI Generate
                </button>
            </div>

            <textarea id="description"
                      name="description"
                      class="input-field"
                      rows="4"
                      placeholder="Distinctive features, contents, brand, colors..."
                      required><?= old('description') ?></textarea>
        </div>


        <!-- ── Contact Info ───────────────────────────────────
             Optional phone number or other instructions the
             poster wants to display publicly on the item page.
        ──────────────────────────────────────────────────── -->
        <div class="input-group">
            <label class="input-label" for="contact_info">Contact Info (Optional)</label>
            <input type="text"
                   id="contact_info"
                   name="contact_info"
                   class="input-field"
                   placeholder="Phone number or specific instructions"
                   value="<?= old('contact_info') ?>">
        </div>


        <!-- ── WhatsApp Number ────────────────────────────────
             If provided, the item detail page will show a
             WhatsApp button that opens a pre-filled chat.
        ──────────────────────────────────────────────────── -->
        <div class="input-group">
            <label class="input-label" for="whatsapp_contact">WhatsApp Number (Optional)</label>
            <input type="text"
                   id="whatsapp_contact"
                   name="whatsapp_contact"
                   class="input-field"
                   placeholder="Number to be contacted via WhatsApp..."
                   value="<?= old('whatsapp_contact') ?>">
        </div>


        <!-- ── Allow Platform Messaging Checkbox ─────────────
             When checked, other users can send messages through
             the site's built-in chat. The checkbox is pre-checked
             on a fresh form, and re-checked if the form was
             previously submitted with it ticked.
        ──────────────────────────────────────────────────── -->
        <div class="input-group full-row item-form-checkbox-row">
            <input type="checkbox"
                   id="allow_platform_message"
                   name="allow_platform_message"
                   value="1"
                   class="item-form-checkbox"
                   <?= (empty($_SESSION['old']) || old('allow_platform_message')) ? 'checked' : '' ?>>
            <label class="input-label item-form-checkbox-label" for="allow_platform_message">
                Allow user to message me through platform
            </label>
        </div>


        <!-- ── Reward Amount (Lost items only) ───────────────
             Only shown when reporting a LOST item.
             The "$" prefix is a styled <span> — no inline style.
        ──────────────────────────────────────────────────── -->
        <?php if ($type === 'lost'): ?>
            <div class="input-group full-row">
                <label class="input-label" for="reward_amount">Reward Amount (Optional)</label>

                <!-- Flex wrapper that places the "$" badge and the number input side by side -->
                <div class="item-form-reward-row">
                    <span class="item-form-reward-prefix">$</span>
                    <input type="number"
                           step="0.01"
                           min="0"
                           id="reward_amount"
                           name="reward_amount"
                           class="input-field item-form-reward-input"
                           placeholder="0.00"
                           value="<?= old('reward_amount') ?>">
                </div>

                <p class="item-form-map-hint item-form-reward-hint">
                    Offering a reward increases the chances of your item being returned.
                </p>
            </div>
        <?php endif; ?>


        <!-- ── Image Upload ───────────────────────────────────
             Accepts multiple image files in one go.
             The images[] name (with square brackets) tells PHP
             to treat this as an array of uploaded files.
        ──────────────────────────────────────────────────── -->
        <div class="input-group full-row mb-20">
            <label class="input-label" for="image">Upload Images (Optional, multiple allowed)</label>
            <input type="file"
                   id="image"
                   name="images[]"
                   multiple
                   class="input-field item-form-file-input"
                   accept="image/*">
        </div>


        <!-- ── Submit Button ──────────────────────────────────
             The button colour changes based on lost vs found:
             btn-primary (red/terracotta) for lost
             btn-found   (green)          for found
        ──────────────────────────────────────────────────── -->
        <button type="submit"
                class="btn <?= $type === 'lost' ? 'btn-primary' : 'btn-found' ?> w-full item-form-submit-btn full-row">
            Submit Report
        </button>

    </form>
</div><!-- /.item-form-wrapper -->


<!-- ════════════════════════════════════════════════════════
     SCRIPTS
     ════════════════════════════════════════════════════════ -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
/* =================================================================
   CUSTOM CATEGORY TOGGLE
   Shows or hides the "Custom Category Name" text field
   depending on whether the user picks "Other" from the dropdown.
   ================================================================= */

function toggleCustomCategory() {
    var select      = document.getElementById('category_id');
    var customGroup = document.getElementById('custom_category_group');
    var customInput = document.getElementById('custom_category');

    if (select.value === 'other') {
        // User chose "Other" — reveal the text field and make it required
        customGroup.classList.remove('item-form-hidden');
        customInput.required = true;
    } else {
        // User chose a real category — hide the text field and remove required
        customGroup.classList.add('item-form-hidden');
        customInput.required = false;
    }
}


/* =================================================================
   LEAFLET MAP PICKER
   Renders an interactive map. When the user clicks anywhere on it,
   a marker is placed and the hidden latitude/longitude inputs are
   updated so those coordinates are included in the form submission.
   ================================================================= */

document.addEventListener('DOMContentLoaded', function () {

    // Default centre point — Sri Lanka
    var defaultLat = 7.8731;
    var defaultLng = 80.7718;

    // Check if the form was previously submitted and had coordinates
    // (old() re-populates fields after a failed validation attempt)
    var existingLat = document.getElementById('latitude').value;
    var existingLng = document.getElementById('longitude').value;

    // If we have saved coords, start there zoomed in; otherwise show the whole country
    var startLat  = existingLat ? parseFloat(existingLat) : defaultLat;
    var startLng  = existingLng ? parseFloat(existingLng) : defaultLng;
    var startZoom = existingLat ? 15 : 7;

    // Create the Leaflet map inside the #pickerMap div
    var map = L.map('pickerMap').setView([startLat, startLng], startZoom);

    // Load OpenStreetMap tiles (the actual map imagery)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // `marker` holds the current pin (null = no pin placed yet)
    var marker = null;

    // If there were already saved coordinates, place a marker immediately
    if (existingLat && existingLng) {
        marker = L.marker([startLat, startLng]).addTo(map);
        document.getElementById('latDisplay').textContent = startLat.toFixed(5);
        document.getElementById('lngDisplay').textContent = startLng.toFixed(5);
    }

    // Listen for clicks anywhere on the map
    map.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Save coordinates into the hidden form inputs
        document.getElementById('latitude').value  = lat;
        document.getElementById('longitude').value = lng;

        // Update the visible coordinate readout below the map
        document.getElementById('latDisplay').textContent = lat.toFixed(5);
        document.getElementById('lngDisplay').textContent = lng.toFixed(5);

        // Move the existing marker, or create a new one on first click
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    });
});


/* =================================================================
   AI DESCRIPTION GENERATOR
   When the user clicks "AI Generate", this function builds a
   template description based on the item name and type (lost/found).
   In a real app this would call an AI API — here it uses a template.
   ================================================================= */

function generateDescription() {
    var title = document.getElementById('title').value;

    // PHP outputs the type ('lost' or 'found') directly into the JS string
    var type = "<?= escape($type) ?>";

    // Don't generate if the title is empty — we need it for the sentence
    if (!title) {
        alert('Please enter an Item Name first so the AI can generate a description!');
        return;
    }

    // Grab the button so we can swap its label while "loading"
    var btn          = document.querySelector('button[onclick="generateDescription()"]');
    var originalHtml = btn.innerHTML;

    // Show a spinner to indicate something is happening
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

    // Simulate a short delay (like an API call would have)
    setTimeout(function () {
        var desc = '';

        if (type === 'lost') {
            desc = 'I recently lost my ' + title + ' in this general area. '
                 + 'It holds significant value to me. If anyone has seen it or picked it up, '
                 + 'please contact me immediately. A reward is available for its safe return.';
        } else {
            desc = 'I found a ' + title + ' abandoned at this location. '
                 + 'It looks fully intact. I am holding onto it safely. '
                 + 'If this belongs to you, please message me through the platform '
                 + 'with identifying details so I can return it.';
        }

        // Fill the description textarea with the generated text
        document.getElementById('description').value = desc;

        // Restore the button to its original state
        btn.innerHTML = originalHtml;
    }, 800);
}


/* =================================================================
   AI AUTO-TAGGER
   Watches the title and description fields. When either loses focus
   (blur event), it scans the text for keywords and automatically
   selects the matching category in the dropdown — saving the user
   a click.
   ================================================================= */

document.addEventListener('DOMContentLoaded', function () {

    var titleInput      = document.getElementById('title');
    var descInput       = document.getElementById('description');
    var categorySelect  = document.getElementById('category_id');

    // Each key maps to an array of words that suggest that category
    var keywords = {
        'electronics': ['phone', 'iphone', 'samsung', 'laptop', 'macbook', 'ipad',
                        'tablet', 'charger', 'headphones', 'airpods', 'earbuds',
                        'camera', 'watch', 'apple watch'],
        'keys'       : ['key', 'keys', 'car key', 'house keys', 'fob'],
        'wallet'     : ['wallet', 'purse', 'cardholder', 'cash', 'money', 'credit card'],
        'documents'  : ['id', 'passport', 'license', 'certificate', 'document', 'folder', 'binder'],
        'clothing'   : ['jacket', 'coat', 'sweater', 'hoodie', 'hat', 'cap',
                        'scarf', 'gloves', 'shoes', 'sneakers'],
        'jewelry'    : ['ring', 'necklace', 'bracelet', 'earring', 'jewelry',
                        'gold', 'silver', 'diamond'],
        'bag'        : ['bag', 'backpack', 'tote', 'luggage', 'suitcase', 'briefcase']
    };

    /**
     * autoTag()
     * Runs when the user finishes typing in the title or description.
     * Skips tagging if the user already picked a category manually.
     */
    function autoTag() {
        // Don't override if the user already made a choice
        if (categorySelect.value !== '') return;

        // Combine title + description into one lowercase string to search
        var text = (titleInput.value + ' ' + descInput.value).toLowerCase();

        // Find the first keyword category whose words appear in the text
        var foundCategory = null;
        for (var cat in keywords) {
            var matches = keywords[cat].some(function (kw) {
                return text.includes(kw);
            });
            if (matches) {
                foundCategory = cat;
                break; // stop at the first match
            }
        }

        if (!foundCategory) return; // no keyword matched — nothing to do

        // Find the <option> whose label contains the matched category name
        var options = categorySelect.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].text.toLowerCase().includes(foundCategory)) {
                categorySelect.selectedIndex = i;

                // Run the toggle in case "Other" was somehow auto-matched
                toggleCustomCategory();

                // Show a brief "Auto-tagged!" badge on the Category label
                var lbl         = document.querySelector('label[for="category_id"]');
                var originalHTML = lbl.innerHTML;
                lbl.innerHTML   = 'Category <span class="item-form-autotag-badge">'
                                + '<i class="fas fa-magic"></i> Auto-tagged!</span>';

                // Remove the badge after 3 seconds
                setTimeout(function () { lbl.innerHTML = originalHTML; }, 3000);
                break;
            }
        }
    }

    // Trigger auto-tagging when the user leaves either field
    titleInput.addEventListener('blur', autoTag);
    descInput.addEventListener('blur', autoTag);
});
</script>

<?php
// Clear the old form data from the session so it doesn't
// re-appear if the user navigates back to this page later
clearOld();
require_once ROOT . '/resources/views/layouts/footer.php';
?>