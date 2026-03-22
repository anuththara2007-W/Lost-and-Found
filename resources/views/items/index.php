<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/search.css">

<div class="search-header-container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; width: 100%;">
        <div>
            <h1 class="search-title">Browse & Search</h1>
            <p class="search-subtitle">Use the filters below to find exactly what you are looking for.</p>
        </div>
        <div class="index-actions-container">
            <a href="<?= BASE_URL ?>/item/create?type=lost" class="btn btn-primary index-action-btn">Report Lost</a>
            <a href="<?= BASE_URL ?>/item/create?type=found" class="btn btn-found index-action-btn">Report Found</a>
        </div>
    </div>

    <!-- Advanced Search Form -->
    <form action="<?= BASE_URL ?>/item/search" method="GET" class="advanced-search-form" style="margin-top: 20px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid var(--parchment); display: grid; gap: 15px; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        
        <div class="input-group" style="margin-bottom: 0;">
            <label class="input-label" for="q" style="font-size: 13px;">Keyword Search</label>
            <input type="text" id="q" name="q" class="input-field" placeholder="Brand, color, etc." value="<?= isset($data['keyword']) ? escape($data['keyword']) : '' ?>">
        </div>

        <div class="input-group" style="margin-bottom: 0;">
            <label class="input-label" for="type" style="font-size: 13px;">Type</label>
            <select name="type" id="type" class="input-field">
                <option value="">All Types</option>
                <option value="lost" <?= (isset($data['type']) && $data['type'] == 'lost') ? 'selected' : '' ?>>Lost Only</option>
                <option value="found" <?= (isset($data['type']) && $data['type'] == 'found') ? 'selected' : '' ?>>Found Only</option>
            </select>
        </div>

        <div class="input-group" style="margin-bottom: 0;">
            <label class="input-label" for="category_id" style="font-size: 13px;">Category</label>
            <select name="category_id" id="category_id" class="input-field">
                <option value="">All Categories</option>
                <?php if(isset($data['categories'])): foreach($data['categories'] as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= (isset($data['category_id']) && $data['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <div class="input-group" style="margin-bottom: 0;">
            <label class="input-label" for="location" style="font-size: 13px;">Location</label>
            <input type="text" id="location" name="location" class="input-field" placeholder="City, building..." value="<?= isset($data['location']) ? escape($data['location']) : '' ?>">
        </div>

        <div class="input-group" style="margin-bottom: 0;">
            <label class="input-label" for="date" style="font-size: 13px;">Posted Date</label>
            <select name="date" id="date" class="input-field">
                <option value="">Any Time</option>
                <option value="today" <?= (isset($data['date']) && $data['date'] == 'today') ? 'selected' : '' ?>>Today</option>
                <option value="week" <?= (isset($data['date']) && $data['date'] == 'week') ? 'selected' : '' ?>>Past 7 Days</option>
                <option value="month" <?= (isset($data['date']) && $data['date'] == 'month') ? 'selected' : '' ?>>Past 30 Days</option>
            </select>
        </div>

        <div style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-secondary w-full" style="height: 48px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
        </div>

    </form>
</div>

<!-- Browse Results -->
<?php if (empty($data['items'])): ?>
    <div class="search-no-results" style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin-top: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <i class="fas fa-search" style="font-size: 3rem; color: var(--clay); margin-bottom: 15px;"></i>
        <p class="search-empty-text" style="font-size: 1.2rem; color: var(--midnight);">No items match your criteria.</p>
        <a href="<?= BASE_URL ?>/item/search" class="btn btn-secondary" style="margin-top: 15px;">Clear Filters</a>
    </div>
<?php else: ?>
    <div class="search-results-grid">
        <?php foreach ($data['items'] as $item): ?>
            <div class="preview-card preview-card-styled" onclick="window.location.href='<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>'">
                
                <?php if ($item['type'] === 'lost'): ?>
                    <span class="card-badge badge-lost badge-inline badge-inline-lost"><span class="badge-dot"></span> Lost</span>
                <?php else: ?>
                    <span class="card-badge badge-found badge-inline badge-inline-found"><span class="badge-dot"></span> Found</span>
                <?php endif; ?>
                
                <?php if(empty($item['image_path'])): ?>
                    <div class="card-image-container card-image-no-photo">
                         [ NO PHOTO ]
                    </div>
                <?php else: ?>
                    <div class="card-image-container" style="background: url('<?= BASE_URL ?>/uploads/<?= $item['image_path'] ?>') center/cover;">
                    </div>
                <?php endif; ?>
                
                <div class="card-info-title">
                    <?= escape($item['title']) ?>
                </div>
                
                <div class="card-info-date">
                    <?= formatDate($item['date_posted']) ?>
                </div>
                
                <div class="card-info-location">
                    📍 <?= escape($item['location']) ?>
                </div>

                <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-secondary w-full">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
