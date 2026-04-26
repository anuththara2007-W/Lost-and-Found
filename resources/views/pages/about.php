<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<!-- Main About Page Container -->
<div style="max-width: 800px; margin: 40px auto; background: var(--off-white); padding: 40px; border-radius: 16px; box-shadow: var(--shadow-card);">

    <!-- Page Title -->
    <h1 style="font-size: 2.5rem; margin-bottom: 20px; color: var(--midnight);">
        About Lost & Found
    </h1>

    <div style="color: var(--ink); line-height: 1.8; font-size: 15px;">

        <!-- Introduction paragraph -->
        <p style="margin-bottom: 20px;">
            We created this platform to bridge the gap between those who have lost precious belongings and the good Samaritans who find them. Our mission is to provide a secure, streamlined, and community-driven system for item recovery.
        </p>

        <!-- Second description -->
        <p style="margin-bottom: 20px;">
            Whether it's a misplaced wallet at the library or a dropped phone in the food court, the platform helps users report and recover items easily using real-time communication and location-based tracking.
        </p>

        <!-- Action buttons -->
        <div style="margin-top: 40px; text-align: center;">

            <!-- Redirect to create FOUND item report -->
            <a href="<?= BASE_URL ?>/item/create?type=found"
               class="btn btn-found"
               style="padding: 12px 30px; margin-right: 10px;">
                I Found Something
            </a>

            <!-- Browse all lost items -->
            <a href="<?= BASE_URL ?>/item/index"
               class="btn btn-secondary"
               style="padding: 12px 30px;">
                Browse Missing Items
            </a>

        </div>

    </div>
</div>

<!-- Footer -->
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>