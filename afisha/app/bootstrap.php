<?php
/**
 * Инициализация приложения: установка часового пояса, подключение помощников,
 * автозагрузка классов и запуск сессии.
 */
date_default_timezone_set('Europe/Chisinau');

require __DIR__ . '/helpers.php';

// Автоподключение классов
spl_autoload_register(function ($class) {
    foreach (['core', 'models', 'controllers'] as $dir) {
        $file = __DIR__ . "/$dir/$class.php";
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
