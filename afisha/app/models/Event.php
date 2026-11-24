<?php
class Event
{
    private static $select = 'SELECT e.*, c.name AS category_name, u.username
        FROM events e
        JOIN categories c ON c.id = e.category_id
        JOIN users u ON u.id = e.user_id';

    public static function find($id)
    {
        $s = Database::get()->prepare(self::$select . ' WHERE e.id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function all()
    {
        return Database::get()->query(self::$select . ' ORDER BY e.event_date')->fetchAll();
    }

    public static function byUser($userId)
    {
        $s = Database::get()->prepare(self::$select . ' WHERE e.user_id = ? ORDER BY e.event_date');
        $s->execute([$userId]);
        return $s->fetchAll();
    }

    public static function upcoming($limit)
    {
        $s = Database::get()->prepare(self::$select . ' WHERE e.event_date >= ? ORDER BY e.event_date LIMIT ' . (int)$limit);
        $s->execute([date('Y-m-d H:i')]);
        return $s->fetchAll();
    }

    public static function count()
    {
        return (int)Database::get()->query('SELECT COUNT(*) FROM events')->fetchColumn();
    }

    public static function create($userId, $d)
    {
        $s = Database::get()->prepare(
            'INSERT INTO events (user_id, category_id, title, description, event_date, place, format, price, age_limit)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $s->execute([$userId, $d['category_id'], $d['title'], $d['description'], str_replace('T', ' ', $d['event_date']),
            $d['place'], $d['format'], (float)$d['price'], (int)$d['age_limit']]);
    }

    public static function update($id, $d)
    {
        $s = Database::get()->prepare(
            'UPDATE events SET category_id = ?, title = ?, description = ?, event_date = ?, place = ?,
             format = ?, price = ?, age_limit = ? WHERE id = ?'
        );
        $s->execute([$d['category_id'], $d['title'], $d['description'], str_replace('T', ' ', $d['event_date']),
            $d['place'], $d['format'], (float)$d['price'], (int)$d['age_limit'], $id]);
    }

    public static function delete($id)
    {
        Database::get()->prepare('DELETE FROM events WHERE id = ?')->execute([$id]);
    }

    // Поиск по критериям. Значения подставляются через подготовленные запросы.
    public static function search($f)
    {
        $sql = self::$select . ' WHERE 1 = 1';
        $p = [];
        if ($f['q'] !== '') {
            $sql .= ' AND (lower_utf8(e.title) LIKE ? OR lower_utf8(e.description) LIKE ?)';
            $like = '%' . mb_strtolower($f['q'], 'UTF-8') . '%';
            $p[] = $like;
            $p[] = $like;
        }
        if ($f['category_id'] > 0) {
            $sql .= ' AND e.category_id = ?';
            $p[] = $f['category_id'];
        }
        if ($f['format'] !== '') {
            $sql .= ' AND e.format = ?';
            $p[] = $f['format'];
        }
        if ($f['date_from'] !== '') {
            $sql .= ' AND e.event_date >= ?';
            $p[] = $f['date_from'] . ' 00:00';
        }
        if ($f['date_to'] !== '') {
            $sql .= ' AND e.event_date <= ?';
            $p[] = $f['date_to'] . ' 23:59';
        }
        if ($f['price_max'] !== '') {
            $sql .= ' AND e.price <= ?';
            $p[] = (float)$f['price_max'];
        }
        $sql .= ' ORDER BY e.event_date';
        $s = Database::get()->prepare($sql);
        $s->execute($p);
        return $s->fetchAll();
    }
}
