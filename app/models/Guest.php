<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Guest
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
                'INSERT INTO guests (full_name, email, phone, id_number, nationality, country_code, special_requests, newsletter_opt_in)
                 VALUES (:full_name, :email, :phone, :id_number, :nationality, :country_code, :special_requests, :newsletter)',
                [
                    ':full_name' => $data['full_name'],
                    ':email' => strtolower($data['email']),
                    ':phone' => $data['phone'],
                    ':id_number' => $data['id_number'] ?? null,
                    ':nationality' => $data['nationality'] ?? null,
                    ':country_code' => $data['country_code'] ?? null,
                    ':special_requests' => $data['special_requests'] ?? null,
                    ':newsletter' => $data['newsletter_opt_in'] ?? 1,
                ]
            );

            return (int)$this->database->connection()->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create guest', $exception);
            return null;
        }
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM guests WHERE id = :id AND deleted_at IS NULL',
            [':id' => $id]
        );
    }

    public function getByEmail(string $email): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM guests WHERE email = :email AND deleted_at IS NULL',
            [':email' => strtolower($email)]
        );
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        return $this->database->queryAll(
            'SELECT * FROM guests WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit OFFSET :offset',
            [':limit' => $limit, ':offset' => $offset]
        );
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->database->query(
                'UPDATE guests SET full_name = :full_name, email = :email, phone = :phone, 
                 special_requests = :special_requests, updated_at = NOW()
                 WHERE id = :id',
                [
                    ':id' => $id,
                    ':full_name' => $data['full_name'] ?? null,
                    ':email' => $data['email'] ? strtolower($data['email']) : null,
                    ':phone' => $data['phone'] ?? null,
                    ':special_requests' => $data['special_requests'] ?? null,
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update guest', $exception);
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE guests SET deleted_at = NOW() WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to delete guest', $exception);
            return false;
        }
    }
}
