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

    public function toggleAvailability(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE rooms SET is_available = NOT is_available WHERE id = :id',
                [':id' => $id]
            );
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to toggle room availability', $exception);
            return false;
        }
    }

    public function create(array $data): bool
    {
        try {
            $this->database->query(
                'INSERT INTO rooms (room_number, name, type, capacity, price_per_night, description, amenities, is_available) VALUES (:room_number, :name, :type, :capacity, :price_per_night, :description, :amenities, :is_available)',
                [
                    ':room_number' => $data['room_number'],
                    ':name' => $data['name'],
                    ':type' => $data['type'],
                    ':capacity' => $data['capacity'],
                    ':price_per_night' => $data['price_per_night'],
                    ':description' => $data['description'] ?? '',
                    ':amenities' => $data['amenities'] ?? '[]',
                    ':is_available' => $data['is_available'] ?? 1
                ]
            );
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to create room', $exception);
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->database->query(
                'UPDATE rooms SET room_number = :room_number, name = :name, type = :type, capacity = :capacity, price_per_night = :price_per_night, description = :description, amenities = :amenities WHERE id = :id',
                [
                    ':id' => $id,
                    ':room_number' => $data['room_number'],
                    ':name' => $data['name'],
                    ':type' => $data['type'],
                    ':capacity' => $data['capacity'],
                    ':price_per_night' => $data['price_per_night'],
                    ':description' => $data['description'] ?? '',
                    ':amenities' => $data['amenities'] ?? '[]'
                ]
            );
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update room', $exception);
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->database->query('DELETE FROM rooms WHERE id = :id', [':id' => $id]);
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to delete room', $exception);
            return false;
        }
    }
}
