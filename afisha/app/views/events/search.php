<h1>Поиск мероприятий</h1>
<form method="get" action="index.php" class="form form-search">
    <input type="hidden" name="page" value="search">

    <label for="q">Ключевое слово</label>
    <input type="text" id="q" name="q" maxlength="100" value="<?= e($f['q']) ?>" placeholder="Название или описание">

    <label for="category_id">Категория</label>
    <select id="category_id" name="category_id">
        <option value="0">Все категории</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= $f['category_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="format">Формат</label>
    <select id="format" name="format">
        <option value="">Любой</option>
        <option value="offline" <?= $f['format'] === 'offline' ? 'selected' : '' ?>>Очно</option>
        <option value="online" <?= $f['format'] === 'online' ? 'selected' : '' ?>>Онлайн</option>
    </select>

    <label for="date_from">Дата с</label>
    <input type="date" id="date_from" name="date_from" value="<?= e($f['date_from']) ?>">

    <label for="date_to">Дата по</label>
    <input type="date" id="date_to" name="date_to" value="<?= e($f['date_to']) ?>">

    <label for="price_max">Максимальная цена (0 — только бесплатные)</label>
    <input type="number" id="price_max" name="price_max" min="0" step="1" value="<?= e($f['price_max']) ?>">

    <button type="submit" class="btn">Найти</button>
    <a href="index.php?page=search" class="btn btn-gray">Сбросить</a>
</form>

<h2>Найдено: <?= count($events) ?></h2>
<?php if ($events): ?>
    <div class="grid">
        <?php foreach ($events as $event): include __DIR__ . '/../partials/event_card.php'; endforeach; ?>
    </div>
<?php else: ?>
    <p>По вашему запросу ничего не найдено.</p>
<?php endif; ?>
