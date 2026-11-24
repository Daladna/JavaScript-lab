<?php
// Подключение к SQLite. При первом запуске создаёт базу и наполняет её демо-данными.
/**
 * Отвечает за единое подключение к SQLite и первичную установку демонстрационной базы.
 */
class Database
{
    /**
     * @var PDO|null Единственное подключение к базе данных, кешируемое на время запроса.
     */
    private static $pdo = null;

    /**
     * Возвращает общее подключение к SQLite, создавая базу и наполняя её при первом запуске.
     *
     * @return PDO Активное подключение к базе данных.
     */
    public static function get()
    {
        if (self::$pdo === null) {
            $path = config('db_path');
            $isNew = !file_exists($path);

            // lower_utf8 нужна SQLite для регистронезависимого поиска по кириллице.
            $lower = function ($s) {
                return mb_strtolower((string)$s, 'UTF-8');
            };

            if (PHP_VERSION_ID >= 80400) {
                // PHP 8.4+: подключение возвращает специальный объект Pdo\Sqlite
                $pdo = PDO::connect('sqlite:' . $path);
                $pdo->createFunction('lower_utf8', $lower, 1);
            } else {
                $pdo = new PDO('sqlite:' . $path);
                $pdo->sqliteCreateFunction('lower_utf8', $lower, 1);
            }
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA foreign_keys = ON');

            if ($isNew) {
                self::install($pdo);
            }
            self::$pdo = $pdo;
        }
        return self::$pdo;
    }

    /**
     * Создаёт схему базы и наполняет её демонстрационными пользователями, категориями и мероприятиями.
     *
     * @param PDO $pdo Подключение к только что созданной базе данных.
     * @return void
     */
    private static function install($pdo)
    {
        $pdo->exec(file_get_contents(__DIR__ . '/../../database/schema.sql'));

        // Пользователи: администратор и обычный пользователь
        $u = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $u->execute(['admin', 'admin@example.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin']);
        $u->execute(['ivan', 'ivan@example.com', password_hash('ivan12345', PASSWORD_DEFAULT), 'user']);

        // Категории
        $c = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        foreach (['Концерт', 'Театр', 'Выставка', 'Спорт', 'Конференция', 'Мастер-класс'] as $name) {
            $c->execute([$name]);
        }

        // Демо-мероприятия (даты считаются от сегодняшнего дня)
        $e = $pdo->prepare('INSERT INTO events (user_id, category_id, title, description, event_date, place, format, price, age_limit)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $demo = [
            [1, 1, 'Джазовый вечер', 'Живая джазовая музыка в уютной атмосфере. Выступят три местных коллектива.', '+3 days 19:00', 'Клуб «Ноктюрн»', 'offline', 150, 16],
            [2, 2, 'Спектакль «Вишнёвый сад»', 'Классическая постановка по пьесе А. П. Чехова в исполнении городского театра.', '+5 days 18:30', 'Национальный театр', 'offline', 200, 12],
            [1, 3, 'Выставка современной графики', 'Работы молодых художников региона. Вход свободный, экскурсии каждый час.', '+7 days 10:00', 'Галерея «Арт-пространство»', 'offline', 0, 0],
            [2, 4, 'Городской забег 5 км', 'Открытый забег для всех желающих. Регистрация за час до старта.', '+10 days 09:00', 'Центральный парк', 'offline', 50, 6],
            [1, 5, 'Конференция по веб-разработке', 'Доклады о современных подходах к разработке веб-приложений на PHP и JavaScript.', '+14 days 11:00', 'Zoom', 'online', 0, 16],
            [2, 6, 'Мастер-класс по керамике', 'Научитесь лепить и расписывать посуду своими руками. Материалы включены.', '+20 days 15:00', 'Студия «Глина»', 'offline', 300, 12],
        ];
        foreach ($demo as $d) {
            $d[4] = date('Y-m-d H:i', strtotime($d[4]));
            $e->execute($d);
        }
    }
}
