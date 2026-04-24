<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<?php
$allItems = $data['items'] ?? [];
$lostItems = [];

foreach ($allItems as $item) {
    $type = strtolower(trim($item['type'] ?? ''));
    if ($type === 'lost') {
        $lostItems[] = $item;
    }
}
?>
