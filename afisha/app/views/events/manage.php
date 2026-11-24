<h1><?= e($title) ?></h1>
<?php if ($events): ?>
<div class="table-wrap">
<table>
    <tr>
        <th>Название</th><th>Дата</th><th>Категория</th>
        <?php if ($showAuthor): ?><th>Автор</th><?php endif; ?>
        <th>Действия</th>
    </tr>
    <?php foreach ($events as $ev): ?>
    <tr>
        <td><?= e($ev['title']) ?></td>
        <td><?= e(format_date($ev['event_date'])) ?></td>
        <td><?= e($ev['category_name']) ?></td>
        <?php if ($showAuthor): ?><td><?= e($ev['username']) ?></td><?php endif; ?>
        <td class="actions">
            <a href="index.php?page=event_edit&amp;id=<?= (int)$ev['id'] ?>">Изменить</a>
            <form method="post" action="index.php?page=event_delete" class="inline" onsubmit="return confirm('Удалить мероприятие?');">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$ev['id'] ?>">
                <button type="submit" class="link danger">Удалить</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<?php else: ?>
    <p>Мероприятий пока нет.</p>
<?php endif; ?>
<p><a class="btn" href="index.php?page=event_create">Добавить мероприятие</a></p>
