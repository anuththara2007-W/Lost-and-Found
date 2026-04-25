<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile.css">

<div class="profile-container">
    
    <div class="profile-header">
        <div class="profile-user-info">
            <?php 
                $bgUrl = !empty($data['user']['profile_image']) ? "url('" . BASE_URL . "/uploads/avatars/" . escape($data['user']['profile_image']) . "')" : "";
            ?>
            <div class="profile-avatar-display" style="<?php if($bgUrl) echo "background-image: $bgUrl; background-position: center; background-size: cover; color: transparent;"; ?>">
                <?= empty($data['user']['profile_image']) ? strtoupper(substr($data['user']['username'], 0, 1)) : '' ?>
            </div>
            <div>
                <h1 class="profile-username">@<?= escape($data['user']['username']) ?></h1>
                
                <?php if(isset($data['user']['badge_status']) && $data['user']['badge_status'] !== 'none'): ?>
                    <?php 
                        $badgeClass = '';
                        if($data['user']['badge_status'] == 'gold') $badgeClass = 'badge-gold';
                        elseif($data['user']['badge_status'] == 'silver') $badgeClass = 'badge-silver';
                        elseif($data['user']['badge_status'] == 'verified') $badgeClass = 'badge-verified';
                        else $badgeClass = 'badge-bronze';
                    ?>
                    <span class="profile-badge <?= $badgeClass ?>" style="padding: 4px 8px; border-radius: 4px; color: white; margin-top:5px; display:inline-block; font-size:12px; <?php echo $badgeClass == 'badge-gold' ? 'background:#fbbf24;' : ($badgeClass == 'badge-silver' ? 'background:#94a3b8;' : ($badgeClass == 'badge-verified' ? 'background:#3b82f6;' : 'background:#b45309;')); ?>">
                        <i class="fa-solid <?= $data['user']['badge_status'] == 'verified' ? 'fa-circle-check' : 'fa-award' ?>"></i> <?= ucfirst(escape($data['user']['badge_status'])) ?> Member
                    </span>
                <?php else: ?>
                    <span class="profile-member-since">Member since <?= date('M Y', strtotime($data['user']['date_joined'])) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php if(isset($data['user']['badge_status']) && $data['user']['badge_status'] !== 'none'): ?>
            <?php
                $badge = $data['user']['badge_status'];
                $badgeBg = $badge === 'verified' ? '#2563eb' : ($badge === 'gold' ? '#d97706' : ($badge === 'silver' ? '#64748b' : '#92400e'));
                $badgeImg = $badge === 'verified'
                    ? 'https://cdn-icons-png.flaticon.com/512/12355/12355966.png'
                    : ($badge === 'gold'
                        ? 'https://cdn-icons-png.flaticon.com/512/17879/17879858.png'
                        : ($badge === 'silver'
                            ? 'https://cdn-icons-png.flaticon.com/512/17155/17155284.png'
                            : 'https://cdn-icons-png.flaticon.com/512/17879/17879860.png'));
            ?>
            <div title="<?= ucfirst(escape($badge)) ?>" style="width:46px; height:46px; border-radius:50%; background:<?= $badgeBg ?>; color:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(15,23,42,0.2); overflow:hidden;">
                <img src="<?= $badgeImg ?>" alt="<?= ucfirst(escape($badge)) ?> badge" style="width:28px; height:28px;">
            </div>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/user/dashboard" class="btn btn-secondary" style="font-size: 12px;">Dashboard</a>
    </div>

    <form action="<?= BASE_URL ?>/user/updateProfile" method="POST" enctype="multipart/form-data">
        
        <h3 class="profile-form-title">Update Profile Settings</h3>

        <div class="input-group">
            <label class="input-label" for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" class="input-field" value="<?= escape($data['user']['full_name']) ?>" required>
        </div>

        <div class="input-group">
            <label class="input-label" for="phone">WhatsApp / Phone Number</label>
            <input type="text" id="phone" name="phone" class="input-field" placeholder="+1234567890" value="<?= escape($data['user']['phone']) ?>">
            <p class="profile-help-text">Add this if you want finders to contact you directly via WhatsApp.</p>
        </div>

        <div class="input-group mb-20">
            <label class="input-label" for="profile_image">Change Avatar</label>
            <input type="file" id="profile_image" name="profile_image" class="input-field" accept="image/*" style="padding: 9px 12px;">
        </div>

        <button type="submit" class="btn btn-primary btn-large">Save Changes</button>
    </form>

</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
