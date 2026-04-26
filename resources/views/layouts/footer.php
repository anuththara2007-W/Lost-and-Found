    </main>

    <footer class="site-footer">
        <div class="footer-content footer-grid">
            <div class="footer-brand">
                <a href="<?= BASE_URL ?>/" class="nav-logo footer-logo"><span class="brand-main">Dakkada</span><span class="brand-suffix">.LK</span></a>
                <p class="footer-tagline"><?= escape(t('footer_tagline')) ?></p>
            </div>
            <div class="footer-col">
                <h5><?= escape(t('platform')) ?></h5>
                <a href="<?= BASE_URL ?>/item/index" class="footer-link"><?= escape(t('browse_items')) ?></a>
                <a href="<?= BASE_URL ?>/item/create?type=lost" class="footer-link"><?= escape(t('report_lost')) ?></a>
                <a href="<?= BASE_URL ?>/item/create?type=found" class="footer-link"><?= escape(t('report_found')) ?></a>
                <a href="<?= BASE_URL ?>/map/index" class="footer-link"><?= escape(t('live_map')) ?></a>
            </div>
            <div class="footer-col">
                <h5><?= escape(t('resources')) ?></h5>
                <a href="<?= BASE_URL ?>/page/about" class="footer-link"><?= escape(t('about')) ?></a>
                <a href="<?= BASE_URL ?>/page/contact" class="footer-link"><?= escape(t('contact')) ?></a>
                <a href="<?= BASE_URL ?>/page/faq" class="footer-link"><?= escape(t('faq')) ?></a>
                <a href="<?= BASE_URL ?>/home/success_stories" class="footer-link"><?= escape(t('success_stories')) ?></a>
            </div>
            <div class="footer-col">
                <h5><?= escape(t('legal')) ?></h5>
                <a href="<?= BASE_URL ?>/page/terms" class="footer-link"><?= escape(t('terms')) ?></a>
                <a href="<?= BASE_URL ?>/page/privacy" class="footer-link"><?= escape(t('privacy')) ?></a>
                <a href="<?= BASE_URL ?>/auth/login" class="footer-link"><?= escape(t('account_access')) ?></a>
            </div>
        </div>
        <div class="footer-bottom-row">
            <span>&copy; <?= date('Y') ?> Dakkada.LK. <?= escape(t('footer_rights')) ?></span>
            <span><?= escape(t('footer_safety')) ?></span>
        </div>
    </footer>

    <!-- Global Scripts -->
    <?php if(isset($data['js'])): ?>
        <?php foreach($data['js'] as $jsFile): ?>
            <script src="<?= BASE_URL ?>/assets/js/<?= $jsFile ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
