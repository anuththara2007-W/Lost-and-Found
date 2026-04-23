<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<div class="success-container">
    <div class="success-header">
        <h1>Success Stories</h1>
        <p>Celebrating items that found their way back home.</p>
    </div>

    <div class="success-grid">
        <?php if (!empty($data['resolvedItems'])): ?>
            <?php foreach ($data['resolvedItems'] as $item): ?>
                <div class="success-card">
                    <div class="success-image">
                        <?php if(!empty($item['image_path'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= escape($item['image_path']) ?>" alt="<?= escape($item['title']) ?>">
                        <?php else: ?>
                            <div class="success-placeholder">
                                <i class="fas <?= $item['type'] === 'lost' ? 'fa-search' : 'fa-hand-holding-heart' ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="success-badge-wrapper">
                            <span class="success-badge">Reunited</span>
                        </div>
                    </div>
                    <div class="success-content">
                        <h3><?= escape($item['title']) ?></h3>
                        <p class="success-meta">
                            By <strong><?= escape($item['username']) ?></strong> &bull; <?= formatDate($item['date_posted']) ?>
                        </p>
                        <?php if (!empty($item['reward_amount']) && $item['reward_amount'] > 0): ?>
                            <p class="success-reward">
                                <i class="fas fa-gift"></i> Reward Awarded: <strong>$<?= escape(number_format($item['reward_amount'], 2)) ?></strong>
                            </p>
                        <?php endif; ?>
                        <p class="success-desc">
                            <?= escape(substr($item['description'], 0, 100)) ?>...
                        </p>
                        <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-secondary success-view-btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: var(--clay); grid-column: 1 / -1;">No success stories to display yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
