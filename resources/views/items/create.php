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