<section class="hero">
    <h1>Афиша мероприятий</h1>
    <p>Находите интересные события и публикуйте свои.</p>
    <a class="btn" href="index.php?page=search">Найти мероприятие</a>
</section>

<section>
    <h2>Ближайшие мероприятия</h2>
    <?php if ($upcoming): ?>
        <div class="grid">
            <?php foreach ($upcoming as $event): include __DIR__ . '/partials/event_card.php'; endforeach; ?>
        </div>
    <?php else: ?>
        <p>Пока нет предстоящих мероприятий.</p>
    <?php endif; ?>
</section>

<section>
    <h2>Категории</h2>
    <ul class="cats">
        <?php foreach ($categories as $c): ?>
            <li><a href="index.php?page=search&amp;category_id=<?= (int)$c['id'] ?>"><?= e($c['name']) ?> (<?= (int)$c['cnt'] ?>)</a></li>
        <?php endforeach; ?>
    </ul>
</section>

<section>
    <h2>О сайте</h2>
    <p>Всего мероприятий: <strong><?= (int)$eventsCnt ?></strong>. Зарегистрированных пользователей: <strong><?= (int)$usersCnt ?></strong>.</p>
</section>
