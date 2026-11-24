<?php
class Category
{
    public static function all()
    {
        return Database::get()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    public static function exists($id)
    {
        $s = Database::get()->prepare('SELECT 1 FROM categories WHERE id = ?');
        $s->execute([$id]);
        return (bool)$s->fetchColumn();
    }

    // Категории с количеством мероприятий
    public static function withCounts()
    {
        return Database::get()->query(
            'SELECT c.id, c.name, COUNT(e.id) AS cnt
             FROM categories c LEFT JOIN events e ON e.category_id = c.id
             GROUP BY c.id ORDER BY c.name'
        )->fetchAll();
    }
}
