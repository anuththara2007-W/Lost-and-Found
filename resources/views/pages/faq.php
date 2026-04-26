<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/faq.css">

<div class="faq-container">

    <h1 class="faq-title">Frequently Asked Questions</h1>

    <div class="faq-list">

        <!-- FAQ Item 1 -->
        <div class="faq-item faq-border-terracotta">
            <h4>How do I claim a found item?</h4>
            <p>
                If you recognize an item on the 'Browse' page, log into your account and click the "This Is Mine" button. You can then use the messaging system to coordinate securely with the finder to verify ownership and arrange a meetup.
            </p>
        </div>

        <!-- FAQ Item 2 -->
        <div class="faq-item faq-border-sage">
            <h4>Is there a reward for returning an item?</h4>
            <p>
                Some users may offer a reward in their item description, however this is optional. The system relies on community trust and goodwill between users.
            </p>
        </div>

        <!-- FAQ Item 3 -->
        <div class="faq-item faq-border-amber">
            <h4>What if someone falsely claims my item?</h4>
            <p>
                Never hand over an item without verification. Always confirm ownership using unique details such as contents, passwords, or identifiable marks.
            </p>
        </div>

    </div>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>