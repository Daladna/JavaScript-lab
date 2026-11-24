<h1><?= e($heading) ?></h1>
<form method="post" action="<?= e($action) ?>" class="form js-validate">
    <?= csrf_field() ?>

    <label for="title">Название</label>
    <input type="text" id="title" name="title" required minlength="3" maxlength="100" value="<?= e($d['title']) ?>">
    <?= field_error($errors, 'title') ?>

    <label for="category_id">Категория</label>
    <select id="category_id" name="category_id" required>
        <option value="">— выберите —</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= (string)$d['category_id'] === (string)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <?= field_error($errors, 'category_id') ?>

    <label for="event_date">Дата и время</label>
    <input type="datetime-local" id="event_date" name="event_date" required data-future value="<?= e($d['event_date']) ?>">
    <?= field_error($errors, 'event_date') ?>

    <label for="place">Место проведения (для онлайн — платформа)</label>
    <input type="text" id="place" name="place" required minlength="2" maxlength="150" value="<?= e($d['place']) ?>">
    <?= field_error($errors, 'place') ?>

    <label>Формат</label>
    <div class="radios">
        <label><input type="radio" name="format" value="offline" required <?= $d['format'] === 'offline' ? 'checked' : '' ?>> Очно</label>
        <label><input type="radio" name="format" value="online" <?= $d['format'] === 'online' ? 'checked' : '' ?>> Онлайн</label>
    </div>
    <?= field_error($errors, 'format') ?>

    <label for="price">Цена, MDL (0 — бесплатно)</label>
    <input type="number" id="price" name="price" required min="0" max="100000" step="0.01" value="<?= e($d['price']) ?>">
    <?= field_error($errors, 'price') ?>

    <label for="age_limit">Возрастное ограничение</label>
    <select id="age_limit" name="age_limit" required>
        <?php foreach ($ages as $a): ?>
            <option value="<?= $a ?>" <?= (string)$d['age_limit'] === (string)$a ? 'selected' : '' ?>><?= $a ?>+</option>
        <?php endforeach; ?>
    </select>
    <?= field_error($errors, 'age_limit') ?>

    <label for="description">Описание</label>
    <textarea id="description" name="description" rows="5" required minlength="10" maxlength="2000"><?= e($d['description']) ?></textarea>
    <?= field_error($errors, 'description') ?>

    <button type="submit" class="btn"><?= e($submitText) ?></button>
</form>
