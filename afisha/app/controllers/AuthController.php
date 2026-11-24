<?php
/**
 * Контроллер аутентификации: регистрация, вход, выход и восстановление пароля.
 */
class AuthController
{
    /**
     * Обрабатывает регистрацию: проверяет данные, создаёт пользователя и авторизует его.
     *
     * @return void При успешной регистрации скрипт завершается через redirect.
     */
    public function register()
    {
        if (Auth::user()) {
            redirect('home');
        }
        $errors = [];
        $old = ['username' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $old = ['username' => str_in($_POST, 'username'), 'email' => str_in($_POST, 'email')];
            $errors = User::validateNew($_POST);
            if (!$errors) {
                $id = User::create($old['username'], $old['email'], $_POST['password'], 'user');
                Auth::login($id);
                flash_set('Регистрация прошла успешно. Добро пожаловать!');
                redirect('home');
            }
        }
        render('auth/register', ['title' => 'Регистрация', 'errors' => $errors, 'old' => $old]);
    }

    /**
     * Обрабатывает вход по email и паролю и показывает форму при ошибке.
     *
     * @return void При успешном входе скрипт завершается через redirect.
     */
    public function login()
    {
        if (Auth::user()) {
            redirect('home');
        }
        $errors = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $email = str_in($_POST, 'email');
            $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password_hash'])) {
                Auth::login($user['id']);
                flash_set('Вы вошли в систему.');
                redirect('home');
            }
            // Одинаковое сообщение для неверного email и неверного пароля
            $errors['form'] = 'Неверный email или пароль';
        }
        render('auth/login', ['title' => 'Вход', 'errors' => $errors, 'email' => $email]);
    }

    /**
     * Завершает сеанс по POST-запросу и перенаправляет на главную страницу.
     *
     * @return void Скрипт завершается через redirect.
     */
    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            Auth::logout();
            flash_set('Вы вышли из системы.');
        }
        redirect('home');
    }

    // Шаг 1 восстановления: пользователь вводит email, ему «отправляется» ссылка
    /**
     * Первый шаг восстановления пароля: проверяет email и отправляет ссылку со сбросом.
     *
     * @return void
     */
    public function forgot()
    {
        $errors = [];
        $email = '';
        $sent = false;
        $link = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $email = str_in($_POST, 'email');
            if ($email === '') {
                $errors['email'] = 'Введите email';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Введите корректный email';
            } else {
                $user = User::findByEmail($email);
                if ($user) {
                    $token = User::createResetToken($user['id']);
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                    $link = $scheme . '://' . $_SERVER['HTTP_HOST'] . $dir . '/index.php?page=reset&token=' . $token;
                    $this->sendMail($user['email'], $link);
                    if (!config('show_reset_link')) {
                        $link = null;
                    }
                }
                // Ответ одинаковый независимо от того, есть ли такой email
                $sent = true;
            }
        }
        render('auth/forgot', ['title' => 'Восстановление пароля', 'errors' => $errors,
            'email' => $email, 'sent' => $sent, 'link' => $link]);
    }

    // Шаг 2: пользователь переходит по ссылке и задаёт новый пароль
    /**
     * Второй шаг восстановления: по токену из ссылки позволяет задать новый пароль.
     *
     * @return void При успешной смене пароля скрипт завершается через redirect.
     */
    public function reset()
    {
        $token = str_in($_GET, 'token');
        $user = $token !== '' ? User::findByResetToken($token) : null;
        $errors = [];

        if ($user && $_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
            $password2 = isset($_POST['password2']) && is_string($_POST['password2']) ? $_POST['password2'] : '';
            $errors = User::validatePassword($password, $password2);
            if (!$errors) {
                User::setPassword($user['id'], $password);
                flash_set('Пароль изменён. Теперь вы можете войти.');
                redirect('login');
            }
        }
        render('auth/reset', ['title' => 'Новый пароль', 'errors' => $errors, 'token' => $token, 'valid' => (bool)$user]);
    }

    // Письмо пишется в лог-файл и отправляется через mail() (если на сервере настроена почта)
    /**
     * Отправляет письмо со ссылкой восстановления и дублирует его в лог-файл.
     *
     * @param string $to Email получателя.
     * @param string $link Ссылка для сброса пароля.
     * @return void
     */
    private function sendMail($to, $link)
    {
        $body = "Здравствуйте!\nДля сброса пароля перейдите по ссылке:\n$link\nСсылка действительна 1 час.";
        file_put_contents(config('mail_log'), date('c') . " Кому: $to\n$body\n\n", FILE_APPEND);
        @mail($to, '=?UTF-8?B?' . base64_encode('Восстановление пароля') . '?=', $body,
            "Content-Type: text/plain; charset=UTF-8");
    }
}
