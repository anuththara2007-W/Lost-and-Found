<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<!-- Inbox Page Styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/messages/index-inbox.css">

<!-- Main Inbox Container -->
<div class="inbox-container">

    <!-- Page Title -->
    <h1 class="inbox-title">My Messages</h1>

    <div class="inbox-content">

        <!-- Description -->
        <p class="inbox-subtitle">
            Your private inbox conversations related to reports you own or have commented on.
        </p>

        <!-- If no conversations exist -->
        <?php if(empty($data['conversations'])): ?>

            <div class="inbox-empty">
                <p>You have no active conversations yet.</p>

                <!-- Redirect to home page -->
                <a href="<?= BASE_URL ?>/home/index" class="btn btn-secondary mt-20">
                    Return Home
                </a>
            </div>

        <?php else: ?>

            <!-- Conversation List -->
            <div class="inbox-list">

                <!-- Loop through all conversations -->
                <?php foreach($data['conversations'] as $convo): ?>

                    <!-- Each conversation item links to chat page -->
                    <a href="<?= BASE_URL ?>/message/chat/<?= $convo['report_id'] ?>" class="inbox-item-card">

                        <div>

                            <!-- Report Title -->
                            <div class="inbox-item-title-row">
                                <?= escape($convo['title']) ?>

                                <!-- Report Status (open/resolved) -->
                                <span class="inbox-item-status">
                                    <?= escape($convo['status']) ?>
                                </span>
                            </div>

                            <!-- Date of report creation -->
                            <div class="inbox-item-date">
                                Created: <?= formatDate($convo['date_posted']) ?>
                            </div>

                        </div>

                        <!-- Arrow icon for UI -->
                        <div class="inbox-item-arrow">&rarr;</div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</div>

<!-- Footer -->
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>