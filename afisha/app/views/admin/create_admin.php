<h1>Новый администратор</h1>
<form method="post" action="index.php?page=admin_create" class="form js-validate">
    <?= csrf_field() ?>
    <label for="username">Имя пользователя</label>
    <input type="text" id="username" name="username" required minlength="3" maxlength="30" value="<?= e($old['username']) ?>">
    <?= field_error($errors, 'username') ?>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required maxlength="100" value="<?= e($old['email']) ?>">
    <?= field_error($errors, 'email') ?>

    <label for="password">Пароль (минимум 8 символов)</label>
    <input type="password" id="password" name="password" required minlength="8" maxlength="72">
    <?= field_error($errors, 'password') ?>

    <label for="password2">Повторите пароль</label>
    <input type="password" id="password2" name="password2" required data-match="password">
    <?= field_error($errors, 'password2') ?>

    <button type="submit" class="btn">Создать</button>
</form>
