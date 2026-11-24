<?php
/**
 * Модель категории мероприятий: справочник категорий и проверка их существования.
 */
class Category
{
    /**
     * Возвращает все категории, упорядоченные по названию.
     *
     * @return array[] Список категорий {id, name}.
     */
    public static function all()
    {
        return Database::get()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    /**
     * Проверяет, существует ли категория с указанным идентификатором.
     *
     * @param int $id Идентификатор категории.
     * @return bool true, если категория найдена.
     */
    public static function exists($id)
    {
        $s = Database::get()->prepare('SELECT 1 FROM categories WHERE id = ?');
        $s->execute([$id]);
        return (bool)$s->fetchColumn();
    }

    // Категории с количеством мероприятий
    /**
     * Возвращает категории вместе с количеством мероприятий в каждой.
     *
     * @return array[] Список категорий {id, name, cnt}.
     */
    public static function withCounts()
    {
        return Database::get()->query(
            'SELECT c.id, c.name, COUNT(e.id) AS cnt
             FROM categories c LEFT JOIN events e ON e.category_id = c.id
             GROUP BY c.id ORDER BY c.name'
        )->fetchAll();
    }
}
