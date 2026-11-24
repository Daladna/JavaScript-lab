<h1>Новый пароль</h1>
<?php if (!$valid): ?>
    <div class="alert alert-error">Ссылка недействительна или срок её действия истёк.</div>
    <p><a href="index.php?page=forgot">Запросить новую ссылку</a></p>
<?php else: ?>
    <form method="post" action="index.php?page=reset&amp;token=<?= e($token) ?>" class="form js-validate">
        <?= csrf_field() ?>
        <label for="password">Новый пароль (минимум 8 символов)</label>
        <input type="password" id="password" name="password" required minlength="8" maxlength="72">
        <?= field_error($errors, 'password') ?>

        <label for="password2">Повторите пароль</label>
        <input type="password" id="password2" name="password2" required data-match="password">
        <?= field_error($errors, 'password2') ?>

        <button type="submit" class="btn">Сохранить пароль</button>
    </form>
<?php endif; ?>
