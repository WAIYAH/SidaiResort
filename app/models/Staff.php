<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Staff
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->database->queryAll(
            'SELECT id, full_name, email, role, is_active, last_login FROM staff WHERE deleted_at IS NULL ORDER BY full_name ASC'
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT id, full_name, email, role, is_active, last_login FROM staff WHERE id = :id AND deleted_at IS NULL',
            [':id' => $id]
        );
    }

    public function create(array $data): ?int
    {
        try {
            $this->database->query(
                'INSERT INTO staff (full_name, email, phone, role, password_hash, is_active)
                 VALUES (:name, :email, :phone, :role, :password, :active)',
                [
                    ':name' => $data['full_name'],
                    ':email' => strtolower(trim($data['email'])),
                    ':phone' => $data['phone'] ?? null,
                    ':role' => $data['role'],
                    ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
                    ':active' => $data['is_active'] ?? 1,
                ]
            );

            return $this->database->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create staff member', $exception);
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            if (isset($data['full_name'])) {
                $fields[] = 'full_name = :name';
                $params[':name'] = $data['full_name'];
            }
            if (isset($data['email'])) {
                $fields[] = 'email = :email';
                $params[':email'] = strtolower(trim($data['email']));
            }
            if (isset($data['phone'])) {
                $fields[] = 'phone = :phone';
                $params[':phone'] = $data['phone'];
            }
            if (isset($data['role'])) {
                $fields[] = 'role = :role';
                $params[':role'] = $data['role'];
            }
            if (isset($data['is_active'])) {
                $fields[] = 'is_active = :active';
                $params[':active'] = $data['is_active'];
            }
            if (isset($data['password']) && $data['password'] !== '') {
                $fields[] = 'password_hash = :password';
                $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            if (empty($fields)) {
                return false;
            }

            $this->database->query(
                'UPDATE staff SET ' . implode(', ', $fields) . ' WHERE id = :id AND deleted_at IS NULL',
                $params
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update staff member', $exception);
            return false;
        }
    }

    public function deactivate(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE staff SET is_active = 0, deleted_at = NOW() WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to deactivate staff member', $exception);
            return false;
        }
    }
}
