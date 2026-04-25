<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class MenuItem
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(int $limit = 1000): array
    {
        return $this->database->queryAll(
            'SELECT m.*, c.name as category_name FROM menu_items m
             JOIN menu_categories c ON m.category_id = c.id
             WHERE m.is_available = 1 ORDER BY m.sort_order ASC LIMIT :limit',
            [':limit' => $limit]
        );
    }

    public function getByCategory(int $categoryId): array
    {
        return $this->database->queryAll(
            'SELECT * FROM menu_items WHERE category_id = :id AND is_available = 1 ORDER BY sort_order ASC',
            [':id' => $categoryId]
        );
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM menu_items WHERE id = :id',
            [':id' => $id]
        );
    }

    public function toggleAvailability(int $id): bool
    {
        try {
            $this->database->query(
                'UPDATE menu_items SET is_available = NOT is_available WHERE id = :id',
                [':id' => $id]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to toggle menu item availability', $exception);
            return false;
        }
    }

    public function create(array $data): bool
    {
        try {
            $this->database->query(
                'INSERT INTO menu_items (category_id, name, description, price, is_vegetarian, is_spicy, is_available) VALUES (:category_id, :name, :description, :price, :is_vegetarian, :is_spicy, :is_available)',
                [
                    ':category_id' => $data['category_id'],
                    ':name' => $data['name'],
                    ':description' => $data['description'] ?? '',
                    ':price' => $data['price'],
                    ':is_vegetarian' => $data['is_vegetarian'] ?? 0,
                    ':is_spicy' => $data['is_spicy'] ?? 0,
                    ':is_available' => $data['is_available'] ?? 1
                ]
            );
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to create menu item', $exception);
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->database->query(
                'UPDATE menu_items SET category_id = :category_id, name = :name, description = :description, price = :price, is_vegetarian = :is_vegetarian, is_spicy = :is_spicy WHERE id = :id',
                [
                    ':id' => $id,
                    ':category_id' => $data['category_id'],
                    ':name' => $data['name'],
                    ':description' => $data['description'] ?? '',
                    ':price' => $data['price'],
                    ':is_vegetarian' => $data['is_vegetarian'] ?? 0,
                    ':is_spicy' => $data['is_spicy'] ?? 0
                ]
            );
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update menu item', $exception);
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->database->query('DELETE FROM menu_items WHERE id = :id', [':id' => $id]);
            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to delete menu item', $exception);
            return false;
        }
    }
}

