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

    public function seedDefaults(): int
    {
        try {
            $statement = $this->database->query(
                'INSERT IGNORE INTO halls (
                    hall_number,
                    name,
                    capacity,
                    price_full_day,
                    price_half_day,
                    price_evening,
                    description,
                    is_available
                ) VALUES
                (1, :name_1, 80, 75000.00, 48000.00, 42000.00, :description_1, 1),
                (2, :name_2, 220, 130000.00, 80000.00, 70000.00, :description_2, 1)',
                [
                    ':name_1' => 'Enchula',
                    ':description_1' => 'Premium meeting hall for conferences and executive gatherings.',
                    ':name_2' => 'Entumo',
                    ':description_2' => 'Large event hall for celebrations, summits, and social functions.',
                ]
            );

            return $statement->rowCount();
        } catch (\Throwable $exception) {
            log_error('Failed to seed default halls', $exception);
            return 0;
        }
    }
}

