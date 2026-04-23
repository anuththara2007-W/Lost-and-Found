<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<style>
.contact-container {
    max-width: 800px;
    margin: 40px auto;
    background: var(--off-white);
    padding: 40px;
    border-radius: 16px;
    box-shadow: var(--shadow-card);
}

.contact-title {
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: var(--midnight);
}

.contact-wrapper {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

.contact-info {
    flex: 1;
    min-width: 250px;
}

.contact-info h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: var(--midnight);
}

.contact-info p {
    color: var(--clay);
    font-size: 14px;
    margin-bottom: 20px;
}

.contact-item {
    font-size: 14px;
    margin-bottom: 10px;
}

.contact-form {
    flex: 1.5;
    min-width: 300px;
}

.input-group {
    margin-bottom: 15px;
}

.input-label {
    display: block;
    margin-bottom: 5px;
}

.input-field {
    width: 100%;
    padding: 10px;
    font-size: 14px;
}

.btn {
    padding: 12px;
    font-size: 14px;
    cursor: pointer;
}

.btn-primary {
    background: var(--midnight);
    color: white;
    border: none;
}

.w-full {
    width: 100%;
}

.mt-20 {
    margin-top: 20px;
}
</style>

<div class="contact-container">

    <h1 class="contact-title">Contact Us</h1>

    <div class="contact-wrapper">

        <!-- LEFT SIDE -->
        <div class="contact-info">

            <h3>Get in Touch</h3>

            <p>
                Have a question about a report or need help navigating the platform?
                Send us a message and our support team will get back to you shortly.
            </p>

            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i> Galle, Sri Lanka
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-phone"></i> +94 707266991 | +94 785145945
            </div>

            <div class="contact-item">
                <i class="fa-regular fa-envelope"></i> dekkada@gmail.com
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="contact-form">

            <form action="<?= BASE_URL ?>/page/contact" method="POST">

                <div class="input-group">
                    <label class="input-label" for="name">Your Name</label>
                    <input type="text" id="name" name="name"
                           class="input-field"
                           placeholder="Jane Doe"
                           required>
                </div>

                <div class="input-group">
                    <label class="input-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           class="input-field"
                           placeholder="jane@example.com"
                           required>
                </div>

                <div class="input-group">
                    <label class="input-label" for="message">Message</label>
                    <textarea id="message" name="message"
                              class="input-field"
                              rows="5"
                              placeholder="How can we help you?"
                              required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-20">
                    Send Message
                </button>

            </form>

        </div>

    </div>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>