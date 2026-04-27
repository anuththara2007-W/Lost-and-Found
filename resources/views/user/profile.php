<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<style>
/* Profile container */
.profile-container {
    max-width: 900px;
    margin: auto;
    padding: 20px;
}

/* Header section */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* User info layout */
.profile-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Avatar */
.profile-avatar-display {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: bold;
}

/* Username */
.profile-username {
    margin: 0;
}

/* Badge */
.profile-badge {
    margin-top: 5px;
    display: inline-block;
    font-size: 12px;
}

/* Member text */
.profile-member-since {
    font-size: 13px;
    color: gray;
}

/* Form title */
.profile-form-title {
    margin-bottom: 15px;
}

/* Input group */
.input-group {
    margin-bottom: 15px;
}

/* Labels */
.input-label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

/* Inputs */
.input-field {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

/* Help text */
.profile-help-text {
    font-size: 12px;
    color: gray;
}

/* Buttons */
.btn {
    padding: 8px 14px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #ff8f0e;
    color: white;
    height: 40px;
    width:30%;
    align:center;
}

.btn-secondary {
    background: #ff8f0e;
    color: black;
    
}

.btn-large {
    width: 100%;
}

/* Margin helper */
.mb-20 {
    margin-bottom: 20px;
}
</style>

<div class="profile-container">

    <div class="profile-header">
        <div class="profile-user-info">

            <?php 
                $bgUrl = !empty($data['user']['profile_image']) ? "url('" . BASE_URL . "/uploads/avatars/" . escape($data['user']['profile_image']) . "')" : "";
            ?>

            <div class="profile-avatar-display"
                style="<?php if($bgUrl) echo "background-image: $bgUrl; background-position: center; background-size: cover; color: transparent;"; ?>">

                <?= empty($data['user']['profile_image']) ? strtoupper(substr($data['user']['username'], 0, 1)) : '' ?>
            </div>

            <div>
                <h1 class="profile-username">@<?= escape($data['user']['username']) ?></h1>

                <?php if(isset($data['user']['badge_status']) && $data['user']['badge_status'] !== 'none'): ?>
                    <span class="profile-badge">
                        <?= ucfirst(escape($data['user']['badge_status'])) ?> Member
                    </span>
                <?php else: ?>
                    <span class="profile-member-since">
                        Member since <?= date('M Y', strtotime($data['user']['date_joined'])) ?>
                    </span>
                <?php endif; ?>
            </div>

        </div>

        <a href="<?= BASE_URL ?>/user/dashboard" class="btn btn-secondary">Dashboard</a>
    </div>

    <form action="<?= BASE_URL ?>/user/updateProfile" method="POST" enctype="multipart/form-data">

        <h3 class="profile-form-title">Update Profile Settings</h3>

        <div class="input-group">
            <label class="input-label">Full Name</label>
            <input type="text" name="full_name" class="input-field"
                value="<?= escape($data['user']['full_name']) ?>" required>
        </div>

        <div class="input-group">
            <label class="input-label">Phone</label>
            <input type="text" name="phone" class="input-field"
                value="<?= escape($data['user']['phone']) ?>">
        </div>

        <div class="input-group mb-20">
            <label class="input-label">Avatar</label>
            <input type="file" name="profile_image" class="input-field" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-large">Save Changes</button>

    </form>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>