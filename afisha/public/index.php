<?php
// Единая точка входа: выбирает контроллер по параметру ?page=
require __DIR__ . '/../app/bootstrap.php';

$routes = [
    'home'          => ['HomeController', 'index'],
    'search'        => ['EventController', 'search'],
    'register'      => ['AuthController', 'register'],
    'login'         => ['AuthController', 'login'],
    'logout'        => ['AuthController', 'logout'],
    'forgot'        => ['AuthController', 'forgot'],
    'reset'         => ['AuthController', 'reset'],
    'my_events'     => ['EventController', 'myEvents'],
    'event_create'  => ['EventController', 'create'],
    'event_edit'    => ['EventController', 'edit'],
    'event_delete'  => ['EventController', 'delete'],
    'admin'         => ['AdminController', 'index'],
    'admin_events'  => ['AdminController', 'events'],
    'admin_users'   => ['AdminController', 'users'],
    'admin_user_delete' => ['AdminController', 'deleteUser'],
    'admin_create'  => ['AdminController', 'createAdmin'],
];

$page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : 'home';

if (!isset($routes[$page])) {
    abort(404);
}

list($class, $method) = $routes[$page];
(new $class())->$method();
