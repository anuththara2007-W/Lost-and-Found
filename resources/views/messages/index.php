// This is the inbox view where users can see all their active conversations related to reports they own or have commented on.
// It lists the conversations with links to the chat pages for each report.
<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/messages/index-inbox.css">

<div class="inbox-container">
    <h1 class="inbox-title">My Messages</h1>
    <div class="inbox-content">
        <p class="inbox-subtitle">Your private inbox conversations related to reports you own or have commented on.</p>
        
        <?php if(empty($data['conversations'])): ?>
            <div class="inbox-empty">
                <p>You have no active conversations yet.</p>
                <a href="<?= BASE_URL ?>/home/index" class="btn btn-secondary mt-20">Return Home</a>
            </div>
        <?php else: ?>
            <div class="inbox-list">
                <?php foreach($data['conversations'] as $convo): ?>
                    <a href="<?= BASE_URL ?>/message/chat/<?= $convo['report_id'] ?>" class="inbox-item-card">
                        <div>
                            <div class="inbox-item-title-row">
                                <?= escape($convo['title']) ?> 
                                <span class="inbox-item-status"><?= escape($convo['status']) ?></span>
                            </div>
                            <div class="inbox-item-date">
                                Created: <?= formatDate($convo['date_posted']) ?>
                            </div>
                        </div>
                        <div class="inbox-item-arrow">&rarr;</div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>