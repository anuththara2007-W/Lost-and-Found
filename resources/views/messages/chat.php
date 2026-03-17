<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/messages/chat-conversation.css">//add resourcebundle_locales
div class="wa-chat-container">

    <!-- LEFT SIDEBAR -->
    <div class="wa-sidebar">
        <div class="wa-sidebar-header">
            <h2 class="wa-sidebar-title">Chats</h2>
        </div>

        <div class="wa-chat-list">
            <?php if (empty($data['conversations'])): ?>
                <div class="wa-chat-empty-state">No ongoing conversations.</div>
            <?php else: ?>
                <?php foreach ($data['conversations'] as $convo): ?>
                    <a href="<?= BASE_URL ?>/message/chat/<?= $convo['report_id'] ?>" class="wa-chat-item <?= ($convo['report_id'] == $data['item']['report_id']) ? 'active' : '' ?>">
                        <div class="wa-chat-item-avatar">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2z" />
                            </svg>
                        </div>

                        <div class="wa-chat-item-info">
                            <div class="wa-chat-item-title"><?= escape($convo['title']) ?></div>
                            <div class="wa-chat-item-status"><?= escape($convo['status']) ?></div>
                        </div>

                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>