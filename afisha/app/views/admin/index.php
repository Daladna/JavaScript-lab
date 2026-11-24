<h1>Панель администратора</h1>
<p>Мероприятий: <strong><?= (int)$eventsCnt ?></strong>, пользователей: <strong><?= (int)$usersCnt ?></strong>.</p>
<ul class="admin-menu">
    <li><a href="index.php?page=admin_events">Все мероприятия (просмотр, изменение, удаление)</a></li>
    <li><a href="index.php?page=event_create">Добавить мероприятие</a></li>
    <li><a href="index.php?page=admin_users">Пользователи (просмотр, удаление)</a></li>
    <li><a href="index.php?page=admin_create">Создать учётную запись администратора</a></li>
</ul>
