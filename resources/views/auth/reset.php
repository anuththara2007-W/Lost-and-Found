<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<style>
.reset-container {
    max-width: 600px;
    margin: 60px auto;
    background: var(--off-white);
    padding: 40px;
    border-radius: 16px;
    box-shadow: var(--shadow-card);
    text-align: center;
}

.reset-title {
    color: var(--midnight);
    font-size: 2rem;
    margin-bottom: 10px;
}

.reset-subtitle {
    color: var(--clay);
    font-size: 14px;
    margin-bottom: 30px;
}

.input-group {
    margin-bottom: 15px;
}

.input-field {
    width: 100%;
    padding: 10px;
    font-size: 14px;
}

.btn {
    padding: 12px;
    cursor: pointer;
}

.btn-secondary {
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

<div class="reset-container">

    <h2 class="reset-title">Reset Password</h2>

    <p class="reset-subtitle">
        Enter your new password below.
    </p>

    <form action="" method="POST">

        <div class="input-group">
            <input type="password"
                   name="password"
                   class="input-field"
                   placeholder="New Password"
                   required>
        </div>

        <button type="submit" class="btn btn-secondary w-full mt-20">
            Reset Password
        </button>

    </form>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>