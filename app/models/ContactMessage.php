<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class ContactMessage
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function create(array $data): bool
    {
        try {
            $this->database->query(
                'INSERT INTO contact_messages (full_name, email, phone, subject, message)
                 VALUES (:name, :email, :phone, :subject, :message)',
                [
                    ':name' => $data['full_name'],
                    ':email' => $data['email'],
                    ':phone' => $data['phone'] ?? null,
                    ':subject' => $data['subject'] ?? null,
                    ':message' => $data['message'],
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to create contact message', $exception);
            return false;
        }
    }

    public function getAll(int $limit = 100): array
    {
        return $this->database->queryAll(
            'SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM contact_messages WHERE id = :id',
            [':id' => $id]
        );
    }

    public function markRead(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE contact_messages SET is_read = 1, read_at = NOW() WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to mark contact message as read', $exception);
            return false;
        }
    }

    public function getUnreadCount(): int
    {
        $result = $this->database->queryOne('SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0');
        return (int)($result['count'] ?? 0);
    }
}
