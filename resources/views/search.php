<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/search.css">

<div class="search-header-container">
    <div>
        <h1 class="search-title">Detailed Search</h1>
        <p class="search-subtitle">Find exactly what you are looking for.</p>
    </div>
</div>

<div class="search-form-container">
    <form action="<?= BASE_URL ?>/item/search" method="GET" class="search-form">
        <div class="input-group search-input-group-large">
            <label class="input-label" for="q">Keyword</label>
            <input type="text" id="q" name="q" class="input-field" placeholder="Search title or description" value="<?= isset($data['keyword']) ? escape($data['keyword']) : '' ?>">
        </div>
        <div class="input-group search-input-group-small">
            <label class="input-label" for="type">Filter Type</label>
            <select name="type" id="type" class="input-field search-select">
                <option value="">All</option>
                <option value="lost" <?= (isset($data['type']) && $data['type'] == 'lost') ? 'selected' : '' ?>>Lost Only</option>
                <option value="found" <?= (isset($data['type']) && $data['type'] == 'found') ? 'selected' : '' ?>>Found Only</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary search-btn">Search</button>
    </form>
</div>