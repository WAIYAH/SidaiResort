<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Room
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(int $limit = 100): array
    {
        return $this->database->queryAll(
            'SELECT * FROM rooms ORDER BY room_number ASC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM rooms WHERE id = :id',
            [':id' => $id]
        );
    }

    public function getByType(string $type): array
    {
        return $this->database->queryAll(
            'SELECT * FROM rooms WHERE type = :type AND is_available = 1 ORDER BY room_number ASC',
            [':type' => $type]
        );
    }

    public function getAvailableRooms(string $from, string $to, int $capacity = 1): array
    {
        return $this->database->queryAll(
            'SELECT r.* FROM rooms r
             WHERE r.is_available = 1 AND r.capacity >= :capacity
             AND r.id NOT IN (
                SELECT DISTINCT room_id FROM bookings 
                WHERE room_id IS NOT NULL
                AND status NOT IN ("cancelled")
                AND (
                    (check_in <= :to AND check_out >= :from)
                )
             )
             ORDER BY r.price_per_night ASC',
            [
                ':from' => $from,
                ':to' => $to,
                ':capacity' => $capacity,
            ]
        );
    }
}
