<?php
/**
 * Admin View: Edit Report
 *
 * Pre-populates all editable fields for an existing report so an admin
 * can correct details or change its status. POSTs to update_report route.
 *
 * Expected $data keys:
 *   - report     (array): The report record (report_id, title, description,
 *                         location, type, status, category_id).
 *   - categories (array): All available categories for the dropdown.
 */
require_once ROOT . '/resources/views/layouts/header.php';
?>
<!-- Scoped card container for the edit form -->
<div style="max-width:900px; margin:30px auto; background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:20px;">
    <!-- Page heading includes the report ID (cast to int to prevent injection) -->
    <h1 style="margin-top:0;">Edit Report #<?= (int)$data['report']['report_id'] ?></h1>

    <!-- POST to update_report/{id}; the report ID is embedded in the action URL -->
    <form method="POST" action="<?= BASE_URL ?>/admin/update_report/<?= (int)$data['report']['report_id'] ?>">
        <div style="display:grid; gap:12px;">
            <!-- Title: pre-filled with the existing value (XSS-safe via escape()) -->
            <label>Title <input type="text" name="title" value="<?= escape($data['report']['title']) ?>" required style="width:100%; padding:8px;"></label>

            <!-- Description textarea: pre-filled with the existing description -->
            <label>Description <textarea name="description" required style="width:100%; padding:8px; min-height:110px;"><?= escape($data['report']['description']) ?></textarea></label>

            <!-- Location: pre-filled with the existing location -->
            <label>Location <input type="text" name="location" value="<?= escape($data['report']['location']) ?>" required style="width:100%; padding:8px;"></label>

            <!-- Type: "lost" or "found"; pre-selects the current report type -->
            <label>Type
                <select name="type" style="width:100%; padding:8px;">
                    <option value="lost" <?= $data['report']['type'] === 'lost' ? 'selected' : '' ?>>Lost</option>
                    <option value="found" <?= $data['report']['type'] === 'found' ? 'selected' : '' ?>>Found</option>
                </select>
            </label>

            <!-- Status: open / resolved / removed; pre-selects the current status -->
            <label>Status
                <select name="status" style="width:100%; padding:8px;">
                    <option value="open" <?= $data['report']['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="resolved" <?= $data['report']['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="removed" <?= $data['report']['status'] === 'removed' ? 'selected' : '' ?>>Removed</option>
                </select>
            </label>

            <!-- Category: "None" option for uncategorised reports; pre-selects the current category -->
            <label>Category
                <select name="category_id" style="width:100%; padding:8px;">
                    <option value="">None</option>
                    <?php foreach (($data['categories'] ?? []) as $cat): ?>
                        <!-- Cast both values to string for reliable equality comparison -->
                        <option value="<?= (int)$cat['category_id'] ?>" <?= (string)($data['report']['category_id'] ?? '') === (string)$cat['category_id'] ? 'selected' : '' ?>>
                            <?= escape($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <!-- Action buttons: Save or navigate back without saving -->
        <div style="margin-top:16px; display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
