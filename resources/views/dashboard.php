<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">

<div class="dashboard-container">
    
    <!-- Left Sidebar: Profile Summary -->
    <div class="dashboard-sidebar">
        <div class="dashboard-profile-card">
            <?php if(!empty($user['profile_image'])): ?>
                <div class="dashboard-avatar" style="background: url('<?= BASE_URL ?>/uploads/avatars/<?= escape($user['profile_image']) ?>') center/cover; color: transparent;"></div>
            <?php else: ?> //runs when IF condition is false
                <div class="dashboard-avatar">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>//get first letter of username from index 0 and make it uppercase
                </div>
            <?php endif; ?>
            
            <h3 class="dashboard-user-name"><?= escape($user['full_name']) ?></h3>
            <p class="dashboard-username-handle">@<?= escape($user['username']) ?></p>
            
            <div class="dashboard-user-details">
                <p class="dashboard-detail-item"><strong>Email:</strong><br><?= escape($user['email']) ?></p>
                <p class="dashboard-detail-item"><strong>Member Since:</strong><br><?= date('M Y', strtotime($user['date_joined'])) ?></p>
                <?php if($user['badge_status'] !== 'none'): ?>
                    <p class="dashboard-detail-item"><strong>Trust Badge:</strong><br><span class="dashboard-trust-badge"><?= escape($user['badge_status']) ?></span></p>
                <?php endif; ?>
            </div>

            <a href="<?= BASE_URL ?>/user/profile" class="btn btn-secondary w-full" style="display:block; margin-top: 20px; text-decoration:none;">Edit Profile</a>
        </div>
    </div>

    <!-- Right Content: My Reports -->
    <div class="dashboard-content">
        <div class="dashboard-content-header">
            <h2 class="dashboard-content-title">My Reports</h2>
            <div class="dashboard-actions">
                <a href="<?= BASE_URL ?>/item/create?type=lost" class="btn btn-secondary btn-small">+ Lost</a>
                <a href="<?= BASE_URL ?>/item/create?type=found" class="btn btn-secondary btn-small">+ Found</a>
            </div>
        </div>

        <?php if(empty($reports)): ?>
            <div class="dashboard-empty">
                You haven't posted any reports yet.
            </div>
        <?php else: ?>
            <div class="dashboard-reports-grid">
                <?php foreach($reports as $item): ?>
                    <div class="preview-card dashboard-report-card">
                        
                        <div class="report-card-header">
                            <?php if ($item['type'] === 'lost'): ?>
                                <span class="card-badge badge-lost badge-small"><span class="badge-dot"></span> Lost</span>
                            <?php else: ?>
                                <span class="card-badge badge-found badge-small"><span class="badge-dot"></span> Found</span>
                            <?php endif; ?>
                            
                            <?php if ($item['status'] === 'resolved'): ?>
                                <span class="badge-status-resolved"><i class="fa-solid fa-check"></i> Resolved</span>
                            <?php endif; ?>
                        </div>

                        <?php if(!empty($item['image_path'])): ?>
                            <div style="height:120px; width:100%; border-radius:8px; margin-bottom:10px; background: url('<?= BASE_URL ?>/uploads/<?= escape($item['image_path']) ?>') center/cover;"></div>
                        <?php endif; ?>

                        <h4 class="report-card-title">
                            <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="report-card-link">
                                <?= escape($item['title']) ?>
                            </a>
                        </h4>
                        
                        <p class="report-card-location"><i class="fa-solid fa-location-dot"></i> <?= escape($item['location']) ?></p>
                        
                        <div class="report-card-actions">
                            <a href="<?= BASE_URL ?>/item/show/<?= $item['report_id'] ?>" class="btn btn-secondary btn-flex">View</a>
                            <?php if ($item['status'] === 'open'): ?>
                                <form action="<?= BASE_URL ?>/item/resolve/<?= $item['report_id'] ?>" method="POST" class="btn-flex-form">
                                    <button type="submit" class="btn btn-secondary btn-flex">Mark Resolved</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
