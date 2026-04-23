<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<div style="max-width:900px; margin:30px auto; background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:20px;">
    <h1 style="margin-top:0;">Edit Report #<?= (int)$data['report']['report_id'] ?></h1>
    <form method="POST" action="<?= BASE_URL ?>/admin/update_report/<?= (int)$data['report']['report_id'] ?>">
        <div style="display:grid; gap:12px;">
            <label>Title <input type="text" name="title" value="<?= escape($data['report']['title']) ?>" required style="width:100%; padding:8px;"></label>
            <label>Description <textarea name="description" required style="width:100%; padding:8px; min-height:110px;"><?= escape($data['report']['description']) ?></textarea></label>
            <label>Location <input type="text" name="location" value="<?= escape($data['report']['location']) ?>" required style="width:100%; padding:8px;"></label>
            <label>Type
                <select name="type" style="width:100%; padding:8px;">
                    <option value="lost" <?= $data['report']['type'] === 'lost' ? 'selected' : '' ?>>Lost</option>
                    <option value="found" <?= $data['report']['type'] === 'found' ? 'selected' : '' ?>>Found</option>
                </select>
            </label>
            <label>Status
                <select name="status" style="width:100%; padding:8px;">
                    <option value="open" <?= $data['report']['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="resolved" <?= $data['report']['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="removed" <?= $data['report']['status'] === 'removed' ? 'selected' : '' ?>>Removed</option>
                </select>
            </label>
            <label>Category
                <select name="category_id" style="width:100%; padding:8px;">
                    <option value="">None</option>
                    <?php foreach (($data['categories'] ?? []) as $cat): ?>
                        <option value="<?= (int)$cat['category_id'] ?>" <?= (string)($data['report']['category_id'] ?? '') === (string)$cat['category_id'] ? 'selected' : '' ?>>
                            <?= escape($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <div style="margin-top:16px; display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
