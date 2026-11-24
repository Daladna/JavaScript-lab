<h1>Пользователи</h1>
<div class="table-wrap">
<table>
    <tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Создан</th><th>Действия</th></tr>
    <?php foreach ($users as $usr): ?>
    <tr>
        <td><?= (int)$usr['id'] ?></td>
        <td><?= e($usr['username']) ?></td>
        <td><?= e($usr['email']) ?></td>
        <td><?= $usr['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?></td>
        <td><?= e($usr['created_at']) ?></td>
        <td class="actions">
            <?php if ($usr['id'] != Auth::user()['id']): ?>
            <form method="post" action="index.php?page=admin_user_delete" class="inline" onsubmit="return confirm('Удалить пользователя и все его мероприятия?');">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$usr['id'] ?>">
                <button type="submit" class="link danger">Удалить</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<p><a class="btn" href="index.php?page=admin_create">Создать администратора</a></p>
