<h1>Вход</h1>
<form method="post" action="index.php?page=login" class="form js-validate">
    <?= csrf_field() ?>
    <?php if (!empty($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required value="<?= e($email) ?>">
    <?= field_error($errors, 'email') ?>

    <label for="password">Пароль</label>
    <input type="password" id="password" name="password" required>
    <?= field_error($errors, 'password') ?>

    <button type="submit" class="btn">Войти</button>
</form>
<p><a href="index.php?page=forgot">Забыли пароль?</a> · <a href="index.php?page=register">Регистрация</a></p>
