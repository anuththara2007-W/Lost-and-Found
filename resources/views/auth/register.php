<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<style>
.form-container.register-container {
    margin-top: 5px;
    margin-bottom: 40px;
    max-width: 550px;
    border: 1px solid grey;
    padding: 20px;
    border-radius: 6px;
}

.form-header h2 {
    margin-bottom: 5px;
}

.form-header p {
    margin-top: 0;
    font-size: 14px;
    color: #666;
}

.input-group {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.input-label {
    margin-bottom: 5px;
    font-size: 14px;
}

.input-field {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.input-row {
    display: flex;
    gap: 16px;
}

.input-row .input-group {
    flex: 1;
}

.btn {
    padding: 12px;
    font-size: 15px;
    cursor: pointer;
}

.btn-found {
    background: #000;
    color: #fff;
    border: none;
}

.w-full {
    width: 100%;
}

.mt-20 {
    margin-top: 20px;
}

.form-footer {
    margin-top: 20px;
    font-size: 14px;
}

.form-footer a {
    color: blue;
    text-decoration: none;
}
</style>

<div class="form-container register-container">

    <div class="form-header">
        <h2>Create an Account</h2>
        <p>Join our community to safely report or match found items.</p>
    </div>

    <form action="<?= BASE_URL ?>/auth/register" method="POST">

        <div class="input-group">
            <label class="input-label" for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name"
                   class="input-field"
                   placeholder="John Doe"
                   required
                   value="<?= old('full_name') ?>">
        </div>

        <div class="input-row">

            <div class="input-group">
                <label class="input-label" for="username">Username</label>
                <input type="text" id="username" name="username"
                       class="input-field"
                       placeholder="johndoe88"
                       required
                       value="<?= old('username') ?>">
            </div>

            <div class="input-group">
                <label class="input-label" for="phone">Phone (Optional)</label>
                <input type="text" id="phone" name="phone"
                       class="input-field"
                       placeholder="+65 9123 4567"
                       value="<?= old('phone') ?>">
            </div>

        </div>

        <div class="input-group">
            <label class="input-label" for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   class="input-field"
                   placeholder="john@example.com"
                   required
                   value="<?= old('email') ?>">
        </div>

        <div class="input-row">

            <div class="input-group">
                <label class="input-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="input-field"
                       required>
            </div>

            <div class="input-group">
                <label class="input-label" for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                       class="input-field"
                       required>
            </div>

        </div>

        <button type="submit" class="btn btn-found w-full mt-20">
            Register
        </button>

    </form>

    <div class="form-footer">
        Already have an account?
        <a href="<?= BASE_URL ?>/auth/login">Log In</a>
    </div>

</div>

<?php
clearOld();
require_once ROOT . '/resources/views/layouts/footer.php';
?>