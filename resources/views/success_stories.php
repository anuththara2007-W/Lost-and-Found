<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/success.css">

<!-- Success Stories Page Container -->
<div class="success-container">

    <!-- Page Header Section -->
    <div class="success-header">
        <h1>Success Stories</h1>
        <p>Celebrating items that found their way back home.</p>
    </div>

    <!-- Grid layout for displaying all success cards -->
    <div class="success-grid">

        <!-- Check if resolved items exist -->
        <?php if (!empty($data['resolvedItems'])): ?>

            <!-- Loop through each resolved item -->
            <?php foreach ($data['resolvedItems'] as $item): ?>

                <div class="success-card">

                    <!-- Image Section -->
                    <div class="success-image">

                        <!-- If item has image -->
                        <?php if(!empty($item['image_path'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= escape($item['image_path']) ?>"
                                 alt="<?= escape($item['title']) ?>">

                        <?php else: ?>
                            <!-- Default placeholder icon -->
                            <div class="success-placeholder">
                                <i class="fas <?= $item['type'] === 'lost' ? 'fa-search' : 'fa-hand-holding-heart' ?>"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Reunited status badge -->
                        <div class="success-badge-wrapper">
                            <span class="success-badge">Reunited</span>
                        </div>

                    </div>

                    <!-- Content Section -->
                    <div class="success-content">

                        <!-- Item title -->
                        <h3><?= escape($item['title']) ?></h3>

                        <!-- User + date info -->
                        <p class="success-meta">
                            By <strong><?= escape($item['username']) ?></strong>
                            &bull;
                            <?= formatDate($item['date_posted']) ?>
                        </p>

                        <!-- Reward display (if available) -->
                        <?php if (!empty($item['reward_amount']) && $item['reward_amount'] > 0): ?>
                            <p class="success-reward">
                                <i class="fas fa-gift"></i>
                                Reward Awarded:
                                <strong>$<?= escape(number_format($item['reward_amount'], 2)) ?></strong>
                            </p>
                        <?php endif; ?>

                        <!-- Short description preview -->
                        <p class="success-desc">
                            <?= escape(substr($item['description'], 100)) ?>...
                        </p>

                        <!-- View details button -->
                        <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>"
                           class="btn btn-secondary success-view-btn">
                            View Details
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <!-- Empty state message -->
            <p style="text-align: center; color: var(--clay); grid-column: 1 / -1;">
                No success stories to display yet.
            </p>

        <?php endif; ?>

    </div>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>