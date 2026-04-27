<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/search.css">

<!-- ── Page Header & Action Buttons ────────────────────────────────────────── -->
<div class="search-header-container">
    <div class="search-header-row">

        <!-- Page Title -->
        <div>
            <h1 class="search-title">Browse & Search</h1>
            <p class="search-subtitle">Use the filters below to find exactly what you are looking for.</p>
        </div>

        <!-- Top-right action buttons -->
        <div class="index-actions-container">
            <a href="<?= BASE_URL ?>/item/create?type=lost"  class="btn btn-primary   index-action-btn">Report Lost</a>
            <a href="<?= BASE_URL ?>/item/create?type=found" class="btn btn-found     index-action-btn">Report Found</a>
            <!-- Toggles the filter panel open/closed -->
            <button type="button" class="btn btn-secondary index-action-btn filter-toggle-btn" id="filterToggleBtn">
                <i class="fas fa-sliders-h"></i> Filters
            </button>
        </div>
    </div>

    <!-- ── Advanced Filter Panel (hidden by default, toggled via JS) ──────── -->
    <div class="filter-panel" id="filterPanel">
    <form action="<?= BASE_URL ?>/item/search" method="GET" class="advanced-search-form">

        <!-- Keyword: searches title, description, and custom category -->
        <div class="input-group filter-group">
            <label class="input-label filter-label" for="q">Keyword Search</label>
            <input type="text" id="q" name="q" class="input-field"
                   placeholder="Brand, color, etc."
                   value="<?= isset($data['keyword']) ? escape($data['keyword']) : '' ?>">
        </div>

        <!-- Type: Lost or Found -->
        <div class="input-group filter-group">
            <label class="input-label filter-label" for="type">Type</label>
            <select name="type" id="type" class="input-field">
                <option value="">All Types</option>
                <option value="lost"  <?= (isset($data['type']) && $data['type'] == 'lost')  ? 'selected' : '' ?>>Lost Only</option>
                <option value="found" <?= (isset($data['type']) && $data['type'] == 'found') ? 'selected' : '' ?>>Found Only</option>
            </select>
        </div>

        <!-- Category: dynamically populated from the database -->
        <div class="input-group filter-group">
            <label class="input-label filter-label" for="category_id">Category</label>
            <select name="category_id" id="category_id" class="input-field">
                <option value="">All Categories</option>
                <?php if (isset($data['categories'])): foreach ($data['categories'] as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"
                        <?= (isset($data['category_id']) && $data['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <!-- Location: partial text match -->
        <div class="input-group filter-group">
            <label class="input-label filter-label" for="location">Location</label>
            <input type="text" id="location" name="location" class="input-field"
                   placeholder="City, building..."
                   value="<?= isset($data['location']) ? escape($data['location']) : '' ?>">
        </div>

        <!-- Date: today / past 7 days / past 30 days -->
        <div class="input-group filter-group">
            <label class="input-label filter-label" for="date">Posted Date</label>
            <select name="date" id="date" class="input-field">
                <option value="">Any Time</option>
                <option value="today" <?= (isset($data['date']) && $data['date'] == 'today') ? 'selected' : '' ?>>Today</option>
                <option value="week"  <?= (isset($data['date']) && $data['date'] == 'week')  ? 'selected' : '' ?>>Past 7 Days</option>
                <option value="month" <?= (isset($data['date']) && $data['date'] == 'month') ? 'selected' : '' ?>>Past 30 Days</option>
            </select>
        </div>

        <!-- Submit button -->
        <div class="filter-action">
            <button type="submit" class="btn btn-secondary w-full filter-submit-btn">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
        </div>

    </form>
    </div>
</div>

<!-- ── Search Results ───────────────────────────────────────────────────────── -->

<?php if (empty($data['items'])): ?>

    <!-- No results state -->
    <div class="search-no-results no-results-card">
        <i class="fas fa-search no-results-icon"></i>
        <p class="search-empty-text no-results-text">No items match your criteria.</p>
        <a href="<?= BASE_URL ?>/item/search" class="btn btn-secondary no-results-btn">Clear Filters</a>
    </div>

<?php else: ?>

    <!-- Results grid: one card per report -->
    <div class="search-results-grid">
        <?php foreach ($data['items'] as $item): ?>

            <!-- Clicking anywhere on the card navigates to the detail page -->
            <div class="preview-card preview-card-styled"
                 onclick="window.location.href='<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>'">

                <!-- Lost / Found badge -->
                <?php if ($item['type'] === 'lost'): ?>
                    <span class="card-badge badge-lost  badge-inline badge-inline-lost" ><span class="badge-dot"></span> Lost</span>
                <?php else: ?>
                    <span class="card-badge badge-found badge-inline badge-inline-found"><span class="badge-dot"></span> Found</span>
                <?php endif; ?>

                <!-- Item image or placeholder if none uploaded -->
                <?php if (empty($item['image_path'])): ?>
                    <div class="card-image-container card-image-no-photo">[ NO PHOTO ]</div>
                <?php else: ?>
                    <div class="card-image-container"
                         style="background: url('<?= BASE_URL ?>/uploads/<?= $item['image_path'] ?>') center/cover;">
                    </div>
                <?php endif; ?>

                <!-- Item details -->
                <div class="card-info-title">   <?= escape($item['title'])            ?> </div>
                <div class="card-info-date">    <?= formatDate($item['date_posted'])  ?> </div>
                <div class="card-info-location">
                    <i class="fa-solid fa-location-dot"></i> <?= escape($item['location']) ?>
                </div>

                <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-secondary w-full">View Details</a>
            </div>

        <?php endforeach; ?>
    </div>

<?php endif; ?>

<!-- ── Filter Panel Toggle + Auto-Submit Logic ─────────────────────────────── -->
<script>
(() => {
    const toggleBtn = document.getElementById('filterToggleBtn');
    const panel     = document.getElementById('filterPanel');
    if (!toggleBtn || !panel) return;

    // If the URL already has query params, keep the filter panel open on load
    if (window.location.search.length > 0) panel.classList.add('is-open');

    // Toggle panel open/closed when the Filters button is clicked
    toggleBtn.addEventListener('click', () => panel.classList.toggle('is-open'));

    const form = document.querySelector('.advanced-search-form');
    if (!form) return;

    // Text inputs: submit after the user stops typing (350ms debounce)
    const typingInputs = [form.querySelector('#q'), form.querySelector('#location')].filter(Boolean);

    // Select inputs: submit immediately on change
    const selectInputs = [form.querySelector('#type'), form.querySelector('#category_id'), form.querySelector('#date')].filter(Boolean);

    let debounceTimer;
    const scheduleSubmit = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => form.submit(), 350);
    };

    typingInputs.forEach(input => input.addEventListener('input',  scheduleSubmit));
    selectInputs.forEach(input => input.addEventListener('change', () => form.submit()));
})();
</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>