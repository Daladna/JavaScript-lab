<?php
// Работа с аутентификацией и ролями
/**
 * Управляет аутентификацией: текущим пользователем, входом, выходом и проверкой ролей.
 */
class Auth
{
    /**
     * @var array|null|false Кеш текущего пользователя: false — ещё не загружен, null — гость.
     */
    private static $user = false;

    // Текущий пользователь (каждый раз берётся из БД, поэтому удаление/смена роли действуют сразу)
    /**
     * Возвращает текущего пользователя из сессии, загружая его данные из базы.
     *
     * @return array|null Данные пользователя или null, если он не авторизован.
     */
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

    /**
     * Проверяет, имеет ли текущий пользователь роль администратора.
     *
     * @return bool true, если пользователь авторизован и является администратором.
     */
    public static function isAdmin()
    {
        $u = self::user();
        return $u && $u['role'] === 'admin';
    }

    /**
     * Авторизует пользователя: сохраняет его идентификатор в сессии и обновляет её.
     *
     * @param int $userId Идентификатор пользователя.
     * @return void
     */
    public static function login($userId)
    {
        session_regenerate_id(true); // защита от фиксации сессии
        $_SESSION['user_id'] = (int)$userId;
        self::$user = false;
    }

    /**
     * Завершает сеанс пользователя и сбрасывает кеш текущего пользователя.
     *
     * @return void
     */
    public static function logout()
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
        self::$user = false;
    }

    /**
     * Требует авторизации: гостя перенаправляет на страницу входа.
     *
     * @return void Скрипт завершается через redirect, если пользователь не авторизован.
     */
    public static function requireLogin()
    {
        if (!self::user()) {
            flash_set('Для доступа к этой странице необходимо войти.', 'error');
            redirect('login');
        }
    }

    /**
     * Требует прав администратора: гостя перенаправляет на вход, остальным отдаёт ошибку 403.
     *
     * @return void Скрипт завершается через redirect или abort при недостатке прав.
     */
    public static function requireAdmin()
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            abort(403);
        }
    }
}
