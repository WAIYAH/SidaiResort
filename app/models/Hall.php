<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Hall
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->database->queryAll(
            'SELECT * FROM halls ORDER BY hall_number ASC'
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM halls WHERE id = :id',
            [':id' => $id]
        );
    }

    public function getAvailable(string $date, string $timeSlot = 'full_day'): array
    {
        return $this->database->queryAll(
            'SELECT h.* FROM halls h
             WHERE h.is_available = 1
             AND h.id NOT IN (
                SELECT DISTINCT hall_id FROM hall_availability
                WHERE date_from <= :date AND date_to >= :date
                AND time_slot = :time_slot
                AND status = "booked"
             )
             ORDER BY h.capacity ASC',
            [
                ':date' => $date,
                ':time_slot' => $timeSlot,
            ]
        );
    }

    public function updateAvailability(int $id, int $isAvailable): bool
    {
        try {
            $this->database->query(
                'UPDATE halls SET is_available = :available WHERE id = :id',
                [':id' => $id, ':available' => $isAvailable]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update hall availability', $exception);
            return false;
        }
    }
}

