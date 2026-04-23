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