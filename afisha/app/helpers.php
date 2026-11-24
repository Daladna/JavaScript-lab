<?php
// Вспомогательные функции

/**
 * Возвращает значение параметра конфигурации приложения.
 *
 * @param string $key Имя параметра из app/config.php.
 * @return mixed Значение параметра.
 */
function config($key)
{
    static $c = null;
    if ($c === null) {
        $c = require __DIR__ . '/config.php';
    }
    return $c[$key];
}

// Экранирование вывода (защита от XSS)
/**
 * Экранирует значение для безопасного вывода в HTML и защищает от XSS.
 *
 * @param mixed $v Значение, которое нужно вывести.
 * @return string Экранированная строка.
 */
function e($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Безопасно получить строку из $_GET / $_POST
/**
 * Безопасно читает строковое значение из входных данных ($_GET или $_POST) и обрезает пробелы.
 *
 * @param array $src Исходный массив входных данных.
 * @param string $key Ключ, значение которого нужно получить.
 * @return string Строка без лишних пробелов или пустая строка, если значения нет.
 */
function str_in($src, $key)
{
    return (isset($src[$key]) && is_string($src[$key])) ? trim($src[$key]) : '';
}

/**
 * Перенаправляет на страницу приложения и завершает выполнение скрипта.
 *
 * @param string $page Имя страницы (параметр page).
 * @param array $params Дополнительные GET-параметры запроса.
 * @return void Скрипт завершается через exit после отправки заголовка Location.
 */
function redirect($page, $params = [])
{
    $q = http_build_query(array_merge(['page' => $page], $params));
    header('Location: index.php?' . $q);
    exit;
}

// Одноразовые сообщения
/**
 * Сохраняет одноразовое сообщение в сессии для показа после перехода.
 *
 * @param string $msg Текст сообщения.
 * @param string $type Тип уведомления (success или error).
 * @return void
 */
function flash_set($msg, $type = 'success')
{
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

/**
 * Извлекает одноразовое сообщение из сессии и удаляет его.
 *
 * @return array|null Массив {msg, type} или null, если сообщения нет.
 */
function flash_get()
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// CSRF-защита форм
/**
 * Возвращает CSRF-токен текущей сессии, создавая его при первом обращении.
 *
 * @return string Токен в виде hex-строки.
 */
function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Формирует скрытое HTML-поле с CSRF-токеном для формы.
 *
 * @return string HTML-код скрытого поля.
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/**
 * Проверяет CSRF-токен из POST-запроса; при несовпадении завершает запрос ошибкой 400.
 *
 * @return void Скрипт завершается через abort при неверном токене.
 */
function csrf_check()
{
    $t = $_POST['csrf'] ?? '';
    if (!is_string($t) || !hash_equals(csrf_token(), $t)) {
        abort(400);
    }
}

/**
 * Подключает шапку, указанное представление и подвал, передавая данные в шаблон.
 *
 * @param string $view Путь к представлению относительно app/views (без расширения .php).
 * @param array $data Переменные, доступные внутри представления.
 * @return void
 */
function render($view, $data = [])
{
    extract($data);
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . "/views/$view.php";
    require __DIR__ . '/views/layout/footer.php';
}

/**
 * Завершает запрос с указанным HTTP-кодом и выводит страницу ошибки.
 *
 * @param int $code HTTP-код ошибки (400, 403 или 404).
 * @return void Скрипт завершается через exit после вывода страницы.
 */
function abort($code)
{
    $msgs = [400 => 'Неверный запрос', 403 => 'Доступ запрещён', 404 => 'Страница не найдена'];
    http_response_code($code);
    render('error', ['title' => $msgs[$code], 'code' => $code, 'message' => $msgs[$code]]);
    exit;
}

// Место для сообщения об ошибке поля (заполняется сервером или JS)
/**
 * Формирует HTML-контейнер для сообщения об ошибке конкретного поля.
 *
 * @param array $errors Ошибки валидации в виде [имя поля => сообщение].
 * @param string $name Имя поля, для которого выводится ошибка.
 * @return string HTML-код элемента с сообщением.
 */
function field_error($errors, $name)
{
    return '<span class="error" data-error-for="' . e($name) . '">' . e($errors[$name] ?? '') . '</span>';
}

/**
 * Приводит дату и время к формату для отображения пользователю.
 *
 * @param string $s Дата в формате, который понимает strtotime().
 * @return string Дата в формате «дд.мм.гггг чч:мм».
 */
function format_date($s)
{
    return date('d.m.Y H:i', strtotime($s));
}

/**
 * Форматирует цену мероприятия для вывода: бесплатно или сумма в MDL.
 *
 * @param float|int $p Цена мероприятия.
 * @return string «Бесплатно» для нулевой цены или сумма с суффиксом MDL.
 */
function format_price($p)
{
    if ($p <= 0) {
        return 'Бесплатно';
    }
    return rtrim(rtrim(number_format($p, 2, '.', ' '), '0'), '.') . ' MDL';
}

/**
 * Преобразует техническое значение формата проведения в подпись для интерфейса.
 *
 * @param string $f Формат проведения (online или offline).
 * @return string «Онлайн» или «Очно».
 */
function format_label($f)
{
    return $f === 'online' ? 'Онлайн' : 'Очно';
}
