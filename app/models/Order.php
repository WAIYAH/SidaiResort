<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Order
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function create(array $data): ?int
    {
        try {
            $this->database->query(
                'INSERT INTO orders (order_ref, booking_id, guest_id, items, subtotal, tax, total, delivery_type, special_instructions)
                 VALUES (:ref, :booking_id, :guest_id, :items, :subtotal, :tax, :total, :delivery_type, :instructions)',
                [
                    ':ref' => generate_order_ref(),
                    ':booking_id' => $data['booking_id'] ?? null,
                    ':guest_id' => $data['guest_id'] ?? null,
                    ':items' => json_encode($data['items']),
                    ':subtotal' => $data['subtotal'],
                    ':tax' => $data['tax'] ?? 0,
                    ':total' => $data['total'],
                    ':delivery_type' => $data['delivery_type'] ?? 'dine_in',
                    ':instructions' => $data['special_instructions'] ?? null,
                ]
            );

            return (int)$this->database->connection()->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create order', $exception);
            return null;
        }
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM orders WHERE id = :id',
            [':id' => $id]
        );
    }

    public function getByRef(string $ref): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM orders WHERE order_ref = :ref',
            [':ref' => $ref]
        );
    }

    public function getAll(int $limit = 100): array
    {
        return $this->database->queryAll(
            'SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function updateStatus(int $id, string $status): bool
    {
        try {
            $this->database->query(
                'UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id',
                [':id' => $id, ':status' => $status]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update order', $exception);
            return false;
        }
    }
}
