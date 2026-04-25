<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Payment
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
                'INSERT INTO payments (payment_ref, booking_id, order_id, amount, method, status, notes)
                 VALUES (:ref, :booking_id, :order_id, :amount, :method, :status, :notes)',
                [
                    ':ref' => generate_payment_ref(),
                    ':booking_id' => $data['booking_id'] ?? null,
                    ':order_id' => $data['order_id'] ?? null,
                    ':amount' => $data['amount'],
                    ':method' => $data['method'],
                    ':status' => $data['status'] ?? 'pending',
                    ':notes' => $data['notes'] ?? null,
                ]
            );

            return (int)$this->database->connection()->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create payment', $exception);
            return null;
        }
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM payments WHERE id = :id',
            [':id' => $id]
        );
    }

    public function getByRef(string $ref): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM payments WHERE payment_ref = :ref',
            [':ref' => $ref]
        );
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        return $this->database->queryAll(
            'SELECT * FROM payments ORDER BY created_at DESC LIMIT :limit OFFSET :offset',
            [':limit' => $limit, ':offset' => $offset]
        );
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->database->query(
                'UPDATE payments SET status = :status WHERE id = :id',
                [
                    ':id' => $id,
                    ':status' => $data['status'] ?? null,
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update payment', $exception);
            return false;
        }
    }
}
