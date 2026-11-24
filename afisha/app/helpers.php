<?php
// Вспомогательные функции

function config($key)
{
    static $c = null;
    if ($c === null) {
        $c = require __DIR__ . '/config.php';
    }
    return $c[$key];
}

// Экранирование вывода (защита от XSS)
function e($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Безопасно получить строку из $_GET / $_POST
function str_in($src, $key)
{
    return (isset($src[$key]) && is_string($src[$key])) ? trim($src[$key]) : '';
}

function redirect($page, $params = [])
{
    $q = http_build_query(array_merge(['page' => $page], $params));
    header('Location: index.php?' . $q);
    exit;
}

// Одноразовые сообщения
function flash_set($msg, $type = 'success')
{
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function flash_get()
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// CSRF-защита форм
function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check()
{
    $t = $_POST['csrf'] ?? '';
    if (!is_string($t) || !hash_equals(csrf_token(), $t)) {
        abort(400);
    }
}

function render($view, $data = [])
{
    extract($data);
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . "/views/$view.php";
    require __DIR__ . '/views/layout/footer.php';
}

function abort($code)
{
    $msgs = [400 => 'Неверный запрос', 403 => 'Доступ запрещён', 404 => 'Страница не найдена'];
    http_response_code($code);
    render('error', ['title' => $msgs[$code], 'code' => $code, 'message' => $msgs[$code]]);
    exit;
}

// Место для сообщения об ошибке поля (заполняется сервером или JS)
function field_error($errors, $name)
{
    return '<span class="error" data-error-for="' . e($name) . '">' . e($errors[$name] ?? '') . '</span>';
}

function format_date($s)
{
    return date('d.m.Y H:i', strtotime($s));
}

function format_price($p)
{
    if ($p <= 0) {
        return 'Бесплатно';
    }
    return rtrim(rtrim(number_format($p, 2, '.', ' '), '0'), '.') . ' MDL';
}

function format_label($f)
{
    return $f === 'online' ? 'Онлайн' : 'Очно';
}
