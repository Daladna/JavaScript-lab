<?php $u = Auth::user(); $flash = flash_get(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Афиша') ?> — Афиша мероприятий</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="container nav">
        <a class="logo" href="index.php">Афиша</a>
        <nav>
            <a href="index.php?page=search">Поиск</a>
            <?php if ($u): ?>
                <a href="index.php?page=my_events">Мои мероприятия</a>
                <a href="index.php?page=event_create">Добавить</a>
                <?php if ($u['role'] === 'admin'): ?>
                    <a href="index.php?page=admin">Админ-панель</a>
                <?php endif; ?>
                <span class="user"><?= e($u['username']) ?></span>
                <form method="post" action="index.php?page=logout" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="link">Выйти</button>
                </form>
            <?php else: ?>
                <a href="index.php?page=login">Вход</a>
                <a href="index.php?page=register">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
<?php if ($flash): ?>
    <div class="alert <?= $flash['type'] === 'error' ? 'alert-error' : 'alert-ok' ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>
