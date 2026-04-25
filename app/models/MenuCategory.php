<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class MenuCategory
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->database->queryAll(
            'SELECT * FROM menu_categories WHERE is_active = 1 ORDER BY sort_order ASC'
        );
    }

    public function getAllWithItems(): array
    {
        $categories = $this->getAll();
        $menuItem = new MenuItem($this->database);

        foreach ($categories as &$category) {
            $category['items'] = $menuItem->getByCategory((int)$category['id']);
        }

        return $categories;
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT * FROM menu_categories WHERE id = :id',
            [':id' => $id]
        );
    }

    public function create(array $data): ?int
    {
        try {
            $this->database->query(
                'INSERT INTO menu_categories (name, description, sort_order, is_active) VALUES (:name, :desc, :sort, :active)',
                [
                    ':name' => $data['name'],
                    ':desc' => $data['description'] ?? null,
                    ':sort' => $data['sort_order'] ?? 0,
                    ':active' => $data['is_active'] ?? 1,
                ]
            );

            return $this->database->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create menu category', $exception);
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->database->query(
                'UPDATE menu_categories SET name = :name, description = :desc, sort_order = :sort, is_active = :active WHERE id = :id',
                [
                    ':id' => $id,
                    ':name' => $data['name'],
                    ':desc' => $data['description'] ?? null,
                    ':sort' => $data['sort_order'] ?? 0,
                    ':active' => $data['is_active'] ?? 1,
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update menu category', $exception);
            return false;
        }
    }
}
