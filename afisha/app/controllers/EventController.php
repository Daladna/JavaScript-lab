<?php
/**
 * Контроллер мероприятий: поиск, список своих мероприятий, создание, редактирование и удаление.
 */
class EventController
{
    /**
     * @var int[] Допустимые возрастные ограничения для формы мероприятия.
     */
    private $ages = [0, 6, 12, 16, 18];

    // Публичный поиск мероприятий
    /**
     * Показывает результаты публичного поиска мероприятий по фильтрам из GET-запроса.
     *
     * @return void
     */
    public function search()
    {
        $f = [
            'q'           => mb_substr(str_in($_GET, 'q'), 0, 100),
            'category_id' => (int)str_in($_GET, 'category_id'),
            'format'      => in_array(str_in($_GET, 'format'), ['offline', 'online'], true) ? str_in($_GET, 'format') : '',
            'date_from'   => preg_match('/^\d{4}-\d{2}-\d{2}$/', str_in($_GET, 'date_from')) ? str_in($_GET, 'date_from') : '',
            'date_to'     => preg_match('/^\d{4}-\d{2}-\d{2}$/', str_in($_GET, 'date_to')) ? str_in($_GET, 'date_to') : '',
            'price_max'   => (is_numeric(str_in($_GET, 'price_max')) && str_in($_GET, 'price_max') >= 0) ? str_in($_GET, 'price_max') : '',
        ];
        render('events/search', [
            'title'      => 'Поиск мероприятий',
            'f'          => $f,
            'events'     => Event::search($f),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Показывает мероприятия текущего авторизованного пользователя.
     *
     * @return void Гость перенаправляется на страницу входа через Auth::requireLogin().
     */
    public function myEvents()
    {
        Auth::requireLogin();
        render('events/manage', [
            'title'      => 'Мои мероприятия',
            'events'     => Event::byUser(Auth::user()['id']),
            'showAuthor' => false,
        ]);
    }

    /**
     * Обрабатывает создание мероприятия и показывает форму с ошибками при необходимости.
     *
     * @return void При успешном сохранении скрипт завершается через redirect.
     */
    public function create()
    {
        Auth::requireLogin();
        $errors = [];
        $d = ['title' => '', 'description' => '', 'category_id' => '', 'event_date' => '',
              'place' => '', 'format' => 'offline', 'price' => '0', 'age_limit' => '0'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            list($d, $errors) = $this->validate($_POST);
            if (!$errors) {
                Event::create(Auth::user()['id'], $d);
                flash_set('Мероприятие добавлено.');
                redirect('my_events');
            }
        }
        $this->showForm('Новое мероприятие', 'index.php?page=event_create', 'Создать', $d, $errors);
    }

    /**
     * Обрабатывает редактирование мероприятия и показывает заполненную форму.
     *
     * @return void При успешном сохранении скрипт завершается через redirect.
     */
    public function edit()
    {
        Auth::requireLogin();
        $event = $this->findOwn((int)str_in($_GET, 'id'));
        $errors = [];
        $d = [
            'title' => $event['title'], 'description' => $event['description'],
            'category_id' => $event['category_id'], 'event_date' => str_replace(' ', 'T', $event['event_date']),
            'place' => $event['place'], 'format' => $event['format'],
            'price' => $event['price'], 'age_limit' => $event['age_limit'],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            list($d, $errors) = $this->validate($_POST);
            if (!$errors) {
                Event::update($event['id'], $d);
                flash_set('Мероприятие обновлено.');
                redirect(Auth::isAdmin() ? 'admin_events' : 'my_events');
            }
        }
        $this->showForm('Редактирование мероприятия', 'index.php?page=event_edit&id=' . $event['id'], 'Сохранить', $d, $errors);
    }

    /**
     * Удаляет мероприятие по POST-запросу после проверки прав.
     *
     * @return void Скрипт завершается через redirect.
     */
    public function delete()
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('my_events');
        }
        csrf_check();
        $event = $this->findOwn((int)str_in($_POST, 'id'));
        Event::delete($event['id']);
        flash_set('Мероприятие удалено.');
        redirect(Auth::isAdmin() ? 'admin_events' : 'my_events');
    }

    // Находит мероприятие и проверяет права: изменять можно только своё (администратор — любое)
    /**
     * Находит мероприятие и проверяет, что текущий пользователь вправе его изменять.
     * При отсутствии мероприятия или нехватке прав завершает запрос ошибкой 404 или 403.
     *
     * @param int $id Идентификатор мероприятия.
     * @return array Данные мероприятия.
     */
    private function findOwn($id)
    {
        $event = Event::find($id);
        if (!$event) {
            abort(404);
        }
        if ($event['user_id'] != Auth::user()['id'] && !Auth::isAdmin()) {
            abort(403);
        }
        return $event;
    }

    /**
     * Подготавливает данные и выводит форму создания или редактирования мероприятия.
     *
     * @param string $heading Заголовок формы.
     * @param string $action URL отправки формы.
     * @param string $submitText Текст кнопки отправки.
     * @param array $d Данные мероприятия для заполнения полей.
     * @param array $errors Ошибки валидации в виде [имя поля => сообщение].
     * @return void
     */
    private function showForm($heading, $action, $submitText, $d, $errors)
    {
        render('events/form', [
            'title' => $heading, 'heading' => $heading, 'action' => $action, 'submitText' => $submitText,
            'd' => $d, 'errors' => $errors, 'categories' => Category::all(), 'ages' => $this->ages,
        ]);
    }

    // Серверная проверка формы. Возвращает [данные, ошибки]
    /**
     * Проверяет данные формы мероприятия и нормализует их.
     *
     * @param array $in Данные POST-запроса {title, description, category_id, event_date, place, format, price, age_limit}.
     * @return array Массив из двух элементов: [0] — очищенные данные мероприятия, [1] — ошибки по полям.
     */
    private function validate($in)
    {
        $d = [
            'title'       => str_in($in, 'title'),
            'description' => str_in($in, 'description'),
            'category_id' => str_in($in, 'category_id'),
            'event_date'  => str_in($in, 'event_date'),
            'place'       => str_in($in, 'place'),
            'format'      => str_in($in, 'format'),
            'price'       => str_replace(',', '.', str_in($in, 'price')),
            'age_limit'   => str_in($in, 'age_limit'),
        ];
        $err = [];
        $required = 'Поле обязательно для заполнения';

        if ($d['title'] === '') {
            $err['title'] = $required;
        } elseif (mb_strlen($d['title']) < 3 || mb_strlen($d['title']) > 100) {
            $err['title'] = 'Название должно содержать от 3 до 100 символов';
        }

        if ($d['description'] === '') {
            $err['description'] = $required;
        } elseif (mb_strlen($d['description']) < 10 || mb_strlen($d['description']) > 2000) {
            $err['description'] = 'Описание должно содержать от 10 до 2000 символов';
        }

        if ($d['category_id'] === '') {
            $err['category_id'] = 'Выберите категорию';
        } elseif (!ctype_digit($d['category_id']) || !Category::exists((int)$d['category_id'])) {
            $err['category_id'] = 'Выбрана несуществующая категория';
        }

        if ($d['event_date'] === '') {
            $err['event_date'] = $required;
        } else {
            $dt = DateTime::createFromFormat('Y-m-d\TH:i', $d['event_date']);
            if (!$dt || $dt->format('Y-m-d\TH:i') !== $d['event_date']) {
                $err['event_date'] = 'Некорректная дата';
            } elseif ($dt < new DateTime()) {
                $err['event_date'] = 'Дата не может быть в прошлом';
            }
        }

        if ($d['place'] === '') {
            $err['place'] = $required;
        } elseif (mb_strlen($d['place']) < 2 || mb_strlen($d['place']) > 150) {
            $err['place'] = 'Место должно содержать от 2 до 150 символов';
        }

        if (!in_array($d['format'], ['offline', 'online'], true)) {
            $err['format'] = 'Выберите формат проведения';
        }

        if ($d['price'] === '') {
            $err['price'] = $required;
        } elseif (!is_numeric($d['price']) || $d['price'] < 0 || $d['price'] > 100000) {
            $err['price'] = 'Цена должна быть числом от 0 до 100000';
        }

        if (!ctype_digit($d['age_limit']) || !in_array((int)$d['age_limit'], $this->ages, true)) {
            $err['age_limit'] = 'Выберите возрастное ограничение';
        }

        return [$d, $err];
    }
}
