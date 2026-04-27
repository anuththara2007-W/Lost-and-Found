<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<!-- WhatsApp-style chat CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/messages/chat-conversation.css">

<!-- Main Chat Container -->
<div class="wa-chat-container">
    
    <div class="wa-sidebar">

        <!-- Sidebar Header -->
        <div class="wa-sidebar-header">
            <h2 class="wa-sidebar-title">Chats</h2>
        </div>

        <!-- Chat List -->
        <div class="wa-chat-list">

            <!-- If no conversations exist -->
            <?php if (empty($data['conversations'])): ?>
                <div class="wa-chat-empty-state">No ongoing conversations.</div>

            <?php else: ?>

                <!-- Loop through all conversations -->
                <?php foreach ($data['conversations'] as $convo): ?>

                    <!-- Each chat item links to a report chat -->
                    <a href="<?= BASE_URL ?>/message/chat/<?= $convo['report_id'] ?>"
                       class="wa-chat-item <?= ($convo['report_id'] == $data['item']['report_id']) ? 'active' : '' ?>">

                        <!-- Avatar icon -->
                        <div class="wa-chat-item-avatar">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path fill="currentColor"
                                      d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2z"/>
                            </svg>
                        </div>

                        <!-- Chat info -->
                        <div class="wa-chat-item-info">
                            <div class="wa-chat-item-title">
                                <?= escape($convo['title']) ?>
                            </div>
                            <div class="wa-chat-item-status">
                                <?= escape($convo['status']) ?>
                            </div>
                        </div>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </div>


    <!-- ================= MAIN CHAT AREA ================= -->
    <div class="wa-main">

        <!-- Chat Header -->
        <div class="wa-main-header">

            <div class="wa-main-header-user">

                <!-- Avatar -->
                <div class="wa-main-header-avatar">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path fill="currentColor"
                              d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2z"/>
                    </svg>
                </div>

                <!-- Title + status -->
                <div class="wa-main-header-info">
                    <h3 class="wa-main-title">
                        <?= escape($data['item']['title']) ?>
                    </h3>

                    <!-- Online / typing indicator -->
                    <div id="chatStatusIndicator" class="wa-main-status"></div>
                </div>

            </div>

            <!-- Button to view item details -->
            <a href="<?= BASE_URL ?>/item/show/<?= $data['item']['report_id'] ?>"
               class="btn btn-secondary">
                View Item
            </a>

        </div>


        <!-- ================= MESSAGES AREA ================= -->
        <div class="wa-messages-area" id="chatMessagesArea">
            <div class="wa-messages-empty">Loading messages...</div>
        </div>


        <!-- ================= MESSAGE INPUT ================= -->
        <div class="wa-input-area">

            <form id="chatForm" class="wa-form">

                <!-- Hidden report ID -->
                <input type="hidden"
                       name="report_id"
                       id="report_id"
                       value="<?= $data['item']['report_id'] ?>">

                <!-- Message input -->
                <div class="wa-input-wrapper">
                    <input type="text"
                           name="comment_text"
                           id="comment_text"
                           class="wa-text-input"
                           placeholder="Type a message"
                           autocomplete="off">
                </div>

                <!-- Send button -->
                <button type="submit" class="wa-send-btn">
                    Send
                </button>

            </form>

        </div>

    </div>
</div>


<!-- ================= JAVASCRIPT (REAL-TIME CHAT LOGIC) ================= -->
<script>
//handles real time chat
// Get basic data from PHP
//takes data from backend api
//convert in to HTML
////Display it in the ui
const reportId = document.getElementById('report_id').value;
const currentUserId = <?= json_encode($_SESSION['user_id']) ?>;
const baseUrl = '<?= BASE_URL ?>';

let lastMessageCount = 0;

// Escape HTML for security
function escapeHtml(text) {
    return (text || '')
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

// Render messages into chat UI
function renderMessages(messages) {

    if (messages.length === 0) {
        chatMessagesArea.innerHTML = '<div class="wa-messages-empty">No messages yet.</div>';
        return;
    }

    let html = '';

    messages.forEach(msg => {

        const mine = msg.user_id == currentUserId;

        html += `
        <div class="wa-msg-row ${mine ? 'wa-msg-row-mine' : 'wa-msg-row-theirs'}">

            <div class="wa-msg-bubble ${mine ? 'wa-bubble-mine' : 'wa-bubble-theirs'}">

                ${escapeHtml(msg.comment_text || '')}

                <span class="wa-msg-time">
                    ${msg.formatted_date || ''}
                </span>

            </div>

        </div>`;
    });

    chatMessagesArea.innerHTML = html;

    // Auto scroll to latest message
    if (messages.length > lastMessageCount) {
        chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
        lastMessageCount = messages.length;
    }
}

// Fetch messages from backend API
function fetchMessages() {

    fetch(`${baseUrl}/message/apiGetMessages/${reportId}`, {
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {

        if (data.messages) {
            renderMessages(data.messages);
        }

        // Typing / online status
        let status = document.getElementById('chatStatusIndicator');

        if (data.typing?.length) {
            status.textContent = 'typing...';
        } else if (data.partner_online) {
            status.textContent = 'online';
        } else {
            status.textContent = '';
        }

    })
    .catch(err => console.error(err));
}

// Send message to backend
function handleChatSubmit(e) {

    if (e) e.preventDefault();

    const textInput = document.getElementById('comment_text');

    if (textInput.value.trim() === '') {
        alert('Please type a message');
        return;
    }

    const formData = new FormData(chatForm);

    fetch(`${baseUrl}/message/apiSendMessage`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            textInput.value = '';
            fetchMessages(); // refresh chat
        } else {
            alert(data.error || 'Send failed');
        }

    })
    .catch(err => console.error(err));
}

// Events
chatForm.addEventListener('submit', handleChatSubmit);

// Send message on Enter key
document.getElementById('comment_text').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        handleChatSubmit(e);
    }
});

// Initial load + auto refresh every 3 seconds
fetchMessages();
setInterval(fetchMessages, 3000);

</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>