<?php
/**
 * Модель пользователя: выборка, создание, удаление, смена пароля и валидация данных.
 */
class User
{
    /**
     * Находит пользователя по идентификатору.
     *
     * @param int $id Идентификатор пользователя.
     * @return array|null Данные пользователя или null, если он не найден.
     */
    public static function findById($id)
    {
        $s = Database::get()->prepare('SELECT * FROM users WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    /**
     * Находит пользователя по email без учёта регистра.
     *
     * @param string $email Email пользователя.
     * @return array|null Данные пользователя или null, если он не найден.
     */
    public static function findByEmail($email)
    {
        $s = Database::get()->prepare('SELECT * FROM users WHERE email = ?');
        $s->execute([mb_strtolower($email, 'UTF-8')]);
        return $s->fetch() ?: null;
    }

    /**
     * Находит пользователя по имени без учёта регистра.
     *
     * @param string $username Имя пользователя.
     * @return array|null Данные пользователя или null, если он не найден.
     */
    public static function findByUsername($username)
    {
        $s = Database::get()->prepare('SELECT * FROM users WHERE username = ? COLLATE NOCASE');
        $s->execute([$username]);
        return $s->fetch() ?: null;
    }

    /**
     * Возвращает список всех пользователей без хешей паролей.
     *
     * @return array[] Список пользователей {id, username, email, role, created_at}.
     */
    public static function all()
    {
        return Database::get()->query('SELECT id, username, email, role, created_at FROM users ORDER BY id')->fetchAll();
    }

    /**
     * Считает общее количество пользователей.
     *
     * @return int Число пользователей в базе.
     */
    public static function count()
    {
        return (int)Database::get()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    /**
     * Создаёт пользователя с хешированным паролем.
     *
     * @param string $username Имя пользователя.
     * @param string $email Email пользователя.
     * @param string $password Пароль в открытом виде (сохраняется только его хеш).
     * @param string $role Роль пользователя (user или admin).
     * @return int Идентификатор созданного пользователя.
     */
    public static function create($username, $email, $password, $role)
    {
        $s = Database::get()->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $s->execute([$username, mb_strtolower($email, 'UTF-8'), password_hash($password, PASSWORD_DEFAULT), $role]);
        return (int)Database::get()->lastInsertId();
    }

    /**
     * Удаляет пользователя по идентификатору.
     *
     * @param int $id Идентификатор пользователя.
     * @return void
     */
    public static function delete($id)
    {
        $s = Database::get()->prepare('DELETE FROM users WHERE id = ?');
        $s->execute([$id]);
    }

    /**
     * Устанавливает новый пароль и удаляет все активные токены восстановления пользователя.
     *
     * @param int $id Идентификатор пользователя.
     * @param string $password Новый пароль в открытом виде.
     * @return void
     */
    public static function setPassword($id, $password)
    {
        $pdo = Database::get();
        $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
        $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$id]);
    }

    // Проверка данных формы регистрации. Возвращает массив ошибок [поле => сообщение]
    /**
     * Проверяет данные формы регистрации и собирает ошибки по полям.
     *
     * @param array $in Данные формы {username, email, password, password2}.
     * @return array Ошибки в виде [имя поля => сообщение]; пустой массив, если данные корректны.
     */
    public static function validateNew($in)
    {
        $errors = [];
        $username = str_in($in, 'username');
        $email = str_in($in, 'email');
        $password = isset($in['password']) && is_string($in['password']) ? $in['password'] : '';
        $password2 = isset($in['password2']) && is_string($in['password2']) ? $in['password2'] : '';

        if ($username === '') {
            $errors['username'] = 'Введите имя пользователя';
        } elseif (mb_strlen($username) < 3 || mb_strlen($username) > 30) {
            $errors['username'] = 'Имя должно содержать от 3 до 30 символов';
        } elseif (!preg_match('/^[\p{L}\p{N}_]+$/u', $username)) {
            $errors['username'] = 'Допустимы только буквы, цифры и знак подчёркивания';
        } elseif (User::findByUsername($username)) {
            $errors['username'] = 'Это имя уже занято';
        }

        if ($email === '') {
            $errors['email'] = 'Введите email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
            $errors['email'] = 'Введите корректный email';
        } elseif (User::findByEmail($email)) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        }

        $errors += self::validatePassword($password, $password2);
        return $errors;
    }

    /**
     * Проверяет пароль и его повтор по длине и совпадению.
     *
     * @param string $password Пароль.
     * @param string $password2 Повтор пароля.
     * @return array Ошибки в виде [имя поля => сообщение]; пустой массив, если пароли корректны.
     */
    public static function validatePassword($password, $password2)
    {
        $errors = [];
        if ($password === '') {
            $errors['password'] = 'Введите пароль';
        } elseif (strlen($password) < 8 || strlen($password) > 72) {
            $errors['password'] = 'Пароль должен содержать от 8 до 72 символов';
        }
        if ($password2 === '') {
            $errors['password2'] = 'Повторите пароль';
        } elseif ($password !== $password2) {
            $errors['password2'] = 'Пароли не совпадают';
        }
        return $errors;
    }

    // ---- Восстановление пароля ----

    // Создаёт одноразовый токен. В БД хранится только его хеш.
    /**
     * Создаёт одноразовый токен восстановления пароля, сохраняя в базе только его хеш.
     *
     * @param int $userId Идентификатор пользователя.
     * @return string Токен в открытом виде для ссылки восстановления.
     */
    public static function createResetToken($userId)
    {
        $pdo = Database::get();
        $pdo->prepare('DELETE FROM password_resets WHERE user_id = ? OR expires_at < ?')->execute([$userId, time()]);
        $token = bin2hex(random_bytes(32));
        $pdo->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)')
            ->execute([$userId, hash('sha256', $token), time() + config('reset_ttl')]);
        return $token;
    }

    /**
     * Находит пользователя по действующему токену восстановления пароля.
     *
     * @param string $token Токен в открытом виде из ссылки.
     * @return array|null Данные пользователя или null, если токен неверен или истёк.
     */
    public static function findByResetToken($token)
    {
        $s = Database::get()->prepare(
            'SELECT u.* FROM password_resets r JOIN users u ON u.id = r.user_id
             WHERE r.token_hash = ? AND r.expires_at > ?'
        );
        $s->execute([hash('sha256', $token), time()]);
        return $s->fetch() ?: null;
    }
}
