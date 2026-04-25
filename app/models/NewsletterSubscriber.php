<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class NewsletterSubscriber
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function subscribe(string $email): bool
    {
        try {
            $email = strtolower(trim($email));

            $existing = $this->database->queryOne(
                'SELECT id FROM newsletter_subscribers WHERE email = :email',
                [':email' => $email]
            );

            if ($existing) {
                $this->database->query(
                    'UPDATE newsletter_subscribers SET is_active = 1, unsubscribed_at = NULL WHERE email = :email',
                    [':email' => $email]
                );

                return true;
            }

            $this->database->query(
                'INSERT INTO newsletter_subscribers (email) VALUES (:email)',
                [':email' => $email]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to subscribe newsletter', $exception);
            return false;
        }
    }

    public function unsubscribe(string $email): bool
    {
        try {
            $this->database->query(
                'UPDATE newsletter_subscribers SET is_active = 0, unsubscribed_at = NOW() WHERE email = :email',
                [':email' => strtolower($email)]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to unsubscribe newsletter', $exception);
            return false;
        }
    }

    public function getAll(int $limit = 200): array
    {
        return $this->database->queryAll(
            'SELECT * FROM newsletter_subscribers ORDER BY created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getActiveCount(): int
    {
        $result = $this->database->queryOne('SELECT COUNT(*) as count FROM newsletter_subscribers WHERE is_active = 1');
        return (int)($result['count'] ?? 0);
    }
}
