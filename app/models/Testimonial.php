<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Testimonial
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getApproved(int $limit = 20): array
    {
        return $this->database->queryAll(
            'SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getFeatured(int $limit = 6): array
    {
        return $this->database->queryAll(
            'SELECT * FROM testimonials WHERE is_featured = 1 AND is_approved = 1 ORDER BY created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getAll(int $limit = 100): array
    {
        return $this->database->queryAll(
            'SELECT * FROM testimonials ORDER BY created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM testimonials WHERE id = :id',
            [':id' => $id]
        );
    }

    public function create(array $data): ?int
    {
        try {
            $this->database->query(
                'INSERT INTO testimonials (guest_name, guest_email, rating, message, source)
                 VALUES (:name, :email, :rating, :message, :source)',
                [
                    ':name' => $data['guest_name'],
                    ':email' => $data['guest_email'] ?? null,
                    ':rating' => $data['rating'],
                    ':message' => $data['message'],
                    ':source' => $data['source'] ?? 'website',
                ]
            );

            return $this->database->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create testimonial', $exception);
            return null;
        }
    }

    public function approve(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE testimonials SET is_approved = 1 WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to approve testimonial', $exception);
            return false;
        }
    }

    public function toggleFeatured(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE testimonials SET is_featured = NOT is_featured WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to toggle testimonial featured status', $exception);
            return false;
        }
    }
}
