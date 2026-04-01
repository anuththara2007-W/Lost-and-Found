<?php
if (!defined('ROOT')) {
    require_once dirname(__DIR__, 3) . '/config/config.php';
}
$pageCss = ['admin/admin-items.css'];
require_once ROOT . '/resources/views/layouts/header.php';
if (empty($data)) {
    $data = [
        'reports' => [
            ['report_id' => '101', 'title' => 'Lost Wallet', 'status' => 'active'],
            ['report_id' => '102', 'title' => 'Found Keys', 'status' => 'resolved']
        ]
    ];
}
?>
<div style="max-width: 1000px; margin: 40px auto; min-height: 50vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--midnight); padding-bottom: 15px; margin-bottom: 30px;">
        <h1 style="font-size: 2.2rem; color: var(--midnight);">Admin: Manage Items</h1>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary" style="font-size: 12px; padding: 6px 15px;">&larr; Back to Dashboard</a>
    </div>
    <div style="background: var(--white); padding: 20px; border-radius: 12px; border: 1px solid var(--parchment);">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead style="background: var(--off-white); border-bottom: 2px solid var(--parchment); text-align: left;">
                <tr>
                    <th style="padding: 12px 15px; color: var(--clay);">Item ID</th>
                    <th style="padding: 12px 15px; color: var(--clay);">Title</th>
                    <th style="padding: 12px 15px; color: var(--clay);">Status</th>
                    <th style="padding: 12px 15px; color: var(--clay);">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($data['reports'] ?? []) as $report): ?>
                <tr style="border-bottom: 1px solid var(--parchment);">
                    <td style="padding: 12px 15px; color: var(--midnight); font-weight: 500;"><?= $report['report_id'] ?></td>
                    <td style="padding: 12px 15px; color: var(--midnight);"><a href="<?= BASE_URL ?>/item/show/<?= $report['report_id'] ?>" style="color:var(--midnight);"><?= escape($report['title']) ?></a></td>
                    <td style="padding: 12px 15px; color: var(--clay);"><?= ucfirst(escape($report['status'])) ?></td>
                    <td style="padding: 12px 15px;">
                        <form action="<?= BASE_URL ?>/admin/delete_report/<?= $report['report_id'] ?>" method="POST" onsubmit="return confirm('Delete this item?');">
                            <button type="submit" style="background:none; border:none; color:var(--terracotta); cursor:pointer; font-weight:500; text-decoration:underline;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(empty($data['reports'])): ?>
            <div style="padding: 30px; text-align: center; color: var(--clay);">No items found.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
