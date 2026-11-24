<?php
// Работа с аутентификацией и ролями
class Auth
{
    private static $user = false;

    // Текущий пользователь (каждый раз берётся из БД, поэтому удаление/смена роли действуют сразу)
    public static function user()
    {
        if (self::$user === false) {
            $u = isset($_SESSION['user_id']) ? User::findById((int)$_SESSION['user_id']) : null;
            if (!$u) {
                unset($_SESSION['user_id']);
            }
            self::$user = $u;
        }
        return self::$user;
    }

    public static function isAdmin()
    {
        $u = self::user();
        return $u && $u['role'] === 'admin';
    }

    public static function login($userId)
    {
        session_regenerate_id(true); // защита от фиксации сессии
        $_SESSION['user_id'] = (int)$userId;
        self::$user = false;
    }

    public static function logout()
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
        self::$user = false;
    }

    public static function requireLogin()
    {
        if (!self::user()) {
            flash_set('Для доступа к этой странице необходимо войти.', 'error');
            redirect('login');
        }
    }

    public static function requireAdmin()
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            abort(403);
        }
    }
}
