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

     <!-- MAIN CHAT -->
    <div class="wa-main">

        <div class="wa-main-header">

            <div class="wa-main-header-user">

                <div class="wa-main-header-avatar">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2z" />
                    </svg>
                </div>

                <div class="wa-main-header-info">
                    <h3 class="wa-main-title"><?= escape($data['item']['title']) ?></h3>
                    <div id="chatStatusIndicator" class="wa-main-status"></div>
                </div>

            </div>

            <a href="<?= BASE_URL ?>/item/show/<?= $data['item']['report_id'] ?>" class="btn btn-secondary" style="font-size:13px;padding:6px 12px;border-radius:20px;">
                View Item
            </a>

        </div>

         <!-- MESSAGES -->
        <div class="wa-messages-area" id="chatMessagesArea">
            <div class="wa-messages-empty">Loading messages...</div>
        </div>

         <!-- INPUT -->
        <div class="wa-input-area">

            <form id="chatForm" class="wa-form" enctype="multipart/form-data">

                <input type="hidden" name="report_id" id="report_id" value="<?= $data['item']['report_id'] ?>">

                <label for="chat_attachment" class="wa-attachment-btn">
                    📎
                </label>

                <input type="file"
                    name="attachment"
                    id="chat_attachment"
                    accept="image/*"
                    class="wa-file-input"
                    onchange="document.getElementById('file_alert').style.display='block';">

                <div class="wa-input-wrapper">

                    <input type="text"
                        name="comment_text"
                        id="comment_text"
                        class="wa-text-input"
                        placeholder="Type a message"
                        autocomplete="off">

                    <div id="file_alert" class="wa-file-alert" style="display:none">
                        Image ready
                    </div>

                </div>

                <button type="submit" class="wa-send-btn">
                    Send
                </button>

            </form>

        </div>

    </div>
</div>

<script>//script to get values
    const reportId = document.getElementById('report_id').value;
    const currentUserId = <?= json_encode($_SESSION['user_id']) ?>;
    const chatMessagesArea = document.getElementById('chatMessagesArea');
    const chatForm = document.getElementById('chatForm');
    const baseUrl = '<?= BASE_URL ?>';
    let lastMessageCount = 0;


    function escapeHtml(text) {
        return (text || '')
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }

       function renderMessages(messages) {//function to render messages in the chat area

        if (messages.length === 0) {
            chatMessagesArea.innerHTML = '<div class="wa-messages-empty">No messages yet.</div>';
            return;
        }

        let html = '';

        messages.forEach(msg => {

            const mine = msg.user_id == currentUserId;

            let attach = '';

            if (msg.attachment_path) {
                attach = `
<a href="${baseUrl}/uploads/chat/${msg.attachment_path}" target="_blank">
<img src="${baseUrl}/uploads/chat/${msg.attachment_path}" class="wa-attachment-img">
</a>`;
            }

            html += `
<div class="wa-msg-row ${mine?'wa-msg-row-mine':'wa-msg-row-theirs'}">

<div class="wa-msg-bubble ${mine?'wa-bubble-mine':'wa-bubble-theirs'}">

${attach}

${escapeHtml(msg.comment_text)}

<span class="wa-msg-time">${msg.formatted_date.split(',')[1]}</span>

</div>

</div>`;

        });

        chatMessagesArea.innerHTML = html;

        if (messages.length > lastMessageCount) {
            chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
            lastMessageCount = messages.length;
        }

    }



