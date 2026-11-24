<?php
// Функции администратора
class AdminController
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    // Панель администратора
    public function index()
    {
        render('admin/index', [
            'title'     => 'Панель администратора',
            'eventsCnt' => Event::count(),
            'usersCnt'  => User::count(),
        ]);
    }

    // Просмотр всех мероприятий (редактирование и удаление — через EventController)
    public function events()
    {
        render('events/manage', [
            'title'      => 'Все мероприятия',
            'events'     => Event::all(),
            'showAuthor' => true,
        ]);
    }

    // Список пользователей
    public function users()
    {
        render('admin/users', ['title' => 'Пользователи', 'users' => User::all()]);
    }

    // Удаление пользователя (вместе с его мероприятиями)
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin_users');
        }
        csrf_check();
        $id = (int)str_in($_POST, 'id');
        if ($id === (int)Auth::user()['id']) {
            flash_set('Нельзя удалить свою учётную запись.', 'error');
        } elseif (!User::findById($id)) {
            flash_set('Пользователь не найден.', 'error');
        } else {
            User::delete($id);
            flash_set('Пользователь удалён.');
        }
        redirect('admin_users');
    }

    // Создание новой учётной записи с ролью администратора
    public function createAdmin()
    {
        $errors = [];
        $old = ['username' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $old = ['username' => str_in($_POST, 'username'), 'email' => str_in($_POST, 'email')];
            $errors = User::validateNew($_POST);
            if (!$errors) {
                User::create($old['username'], $old['email'], $_POST['password'], 'admin');
                flash_set('Администратор «' . $old['username'] . '» создан.');
                redirect('admin_users');
            }
        }
        render('admin/create_admin', ['title' => 'Новый администратор', 'errors' => $errors, 'old' => $old]);
    }
}
