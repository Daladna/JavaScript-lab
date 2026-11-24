<h1>Восстановление пароля</h1>
<?php if ($sent): ?>
    <div class="alert alert-ok">Если пользователь с таким email существует, на него отправлена ссылка для сброса пароля (действует 1 час).</div>
    <?php if ($link): ?>
        <div class="alert alert-info">
            Демо-режим (почта на локальном сервере не настроена). Ваша ссылка:<br>
            <a href="<?= e($link) ?>"><?= e($link) ?></a>
        </div>
    <?php endif; ?>
<?php else: ?>
    <form method="post" action="index.php?page=forgot" class="form js-validate">
        <?= csrf_field() ?>
        <label for="email">Ваш email</label>
        <input type="email" id="email" name="email" required value="<?= e($email) ?>">
        <?= field_error($errors, 'email') ?>
        <button type="submit" class="btn">Отправить ссылку</button>
    </form>
<?php endif; ?>
<p><a href="index.php?page=login">Назад ко входу</a></p>
