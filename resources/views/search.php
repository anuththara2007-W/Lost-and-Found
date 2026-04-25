<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/search.css">

<div class="search-header-container">
    <div>
        <h1 class="search-title">Detailed Search</h1>
        <p class="search-subtitle">Find exactly what you are looking for.</p>
    </div>
</div>

<div class="search-form-container">
    <form action="<?= BASE_URL ?>/item/search" method="GET" class="search-form">
        <div class="input-group search-input-group-large">
            <label class="input-label" for="q">Keyword</label>
            <input type="text" id="q" name="q" class="input-field" placeholder="Search title or description" value="<?= isset($data['keyword']) ? escape($data['keyword']) : '' ?>">
        </div>
        <div class="input-group search-input-group-small">
            <label class="input-label" for="type">Filter Type</label>
            <select name="type" id="type" class="input-field search-select">
                <option value="">All</option>
                <option value="lost" <?= (isset($data['type']) && $data['type'] == 'lost') ? 'selected' : '' ?>>Lost Only</option>
                <option value="found" <?= (isset($data['type']) && $data['type'] == 'found') ? 'selected' : '' ?>>Found Only</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary search-btn">Search</button>
    </form>
</div>

<!-- Browse Results -->
<?php if (empty($data['items']) && !isset($data['items'])): ?>
    <div class="search-empty-state">
        <p class="search-empty-text">Search results will populate here.</p>
    </div>
<?php elseif(empty($data['items'])): ?>
    <div class="search-no-results">
        <p class="search-empty-text">No items match your criteria.</p>
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
                    <i class="fa-solid fa-location-dot"></i> <?= escape($item['location']) ?>
                </div>

                <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-secondary w-full">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
