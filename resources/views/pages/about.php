<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/about.css">

<!-- About Page Container -->
<div class="about-container">

    <!-- Title -->
    <h1 class="about-title">About Lost & Found</h1>

    <!-- Content -->
    <div class="about-content">

        <p class="about-text">
            We created this platform to bridge the gap between those who have lost precious belongings and the good Samaritans who find them. Our mission is to provide a secure, streamlined, and community-driven system for item recovery.
        </p>

        <p class="about-text">
            Whether it's a misplaced wallet at the library or a dropped phone in the food court, the platform helps users report and recover items easily using real-time communication and location-based tracking.
        </p>

        <!-- Buttons -->
        <div class="about-actions">

            <a href="<?= BASE_URL ?>/item/create?type=found"
               class="btn btn-found">
                I Found Something
            </a>

            <a href="<?= BASE_URL ?>/item/index"
               class="btn btn-secondary">
                Browse Missing Items
            </a>

        </div>

    </div>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>