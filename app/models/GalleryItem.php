<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class GalleryItem
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(int $limit = 100): array
    {
        return $this->database->queryAll(
            'SELECT * FROM gallery_items WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getByCategory(string $category): array
    {
        return $this->database->queryAll(
            'SELECT * FROM gallery_items WHERE category = :category AND is_active = 1 ORDER BY sort_order ASC',
            [':category' => $category]
        );
    }

    public function getFeatured(int $limit = 12): array
    {
        return $this->database->queryAll(
            'SELECT * FROM gallery_items WHERE is_featured = 1 AND is_active = 1 ORDER BY sort_order ASC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM gallery_items WHERE id = :id',
            [':id' => $id]
        );
    }

    public function create(array $data): ?int
    {
        try {
            $this->database->query(
                'INSERT INTO gallery_items (title, description, image_path, image_thumb, category, sort_order, is_featured, uploaded_by)
                 VALUES (:title, :desc, :path, :thumb, :category, :sort, :featured, :uploaded_by)',
                [
                    ':title' => $data['title'] ?? null,
                    ':desc' => $data['description'] ?? null,
                    ':path' => $data['image_path'],
                    ':thumb' => $data['image_thumb'] ?? null,
                    ':category' => $data['category'],
                    ':sort' => $data['sort_order'] ?? 0,
                    ':featured' => $data['is_featured'] ?? 0,
                    ':uploaded_by' => $data['uploaded_by'] ?? null,
                ]
            );

            return $this->database->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create gallery item', $exception);
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            foreach (['title', 'description', 'category', 'sort_order', 'is_featured', 'is_active'] as $field) {
                if (array_key_exists($field, $data)) {
                    $placeholder = ':' . $field;
                    $fields[] = "{$field} = {$placeholder}";
                    $params[$placeholder] = $data[$field];
                }
            }

            if (empty($fields)) {
                return false;
            }

            $this->database->query(
                'UPDATE gallery_items SET ' . implode(', ', $fields) . ' WHERE id = :id',
                $params
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update gallery item', $exception);
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE gallery_items SET is_active = 0 WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to delete gallery item', $exception);
            return false;
        }
    }

    public function getCategories(): array
    {
        return $this->database->queryAll(
            "SELECT category, COUNT(*) as count FROM gallery_items WHERE is_active = 1 GROUP BY category ORDER BY count DESC"
        );
    }
}
