<?php
class User
{
    public static function findById($id)
    {
        $s = Database::get()->prepare('SELECT * FROM users WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function findByEmail($email)
    {
        $s = Database::get()->prepare('SELECT * FROM users WHERE email = ?');
        $s->execute([mb_strtolower($email, 'UTF-8')]);
        return $s->fetch() ?: null;
    }

    public static function findByUsername($username)
    {
        $s = Database::get()->prepare('SELECT * FROM users WHERE username = ? COLLATE NOCASE');
        $s->execute([$username]);
        return $s->fetch() ?: null;
    }

    public static function all()
    {
        return Database::get()->query('SELECT id, username, email, role, created_at FROM users ORDER BY id')->fetchAll();
    }

    public static function count()
    {
        return (int)Database::get()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public static function create($username, $email, $password, $role)
    {
        $s = Database::get()->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $s->execute([$username, mb_strtolower($email, 'UTF-8'), password_hash($password, PASSWORD_DEFAULT), $role]);
        return (int)Database::get()->lastInsertId();
    }

    public static function delete($id)
    {
        $s = Database::get()->prepare('DELETE FROM users WHERE id = ?');
        $s->execute([$id]);
    }

    public static function setPassword($id, $password)
    {
        $pdo = Database::get();
        $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
        $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$id]);
    }

    // Проверка данных формы регистрации. Возвращает массив ошибок [поле => сообщение]
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
    public static function createResetToken($userId)
    {
        $pdo = Database::get();
        $pdo->prepare('DELETE FROM password_resets WHERE user_id = ? OR expires_at < ?')->execute([$userId, time()]);
        $token = bin2hex(random_bytes(32));
        $pdo->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)')
            ->execute([$userId, hash('sha256', $token), time() + config('reset_ttl')]);
        return $token;
    }

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
