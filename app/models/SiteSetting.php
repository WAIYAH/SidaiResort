<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class SiteSetting
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $row = $this->database->queryOne(
            'SELECT setting_value, setting_type FROM site_settings WHERE setting_key = :key',
            [':key' => $key]
        );

        if ($row === null) {
            return $default;
        }

        return $this->castValue($row['setting_value'], $row['setting_type']);
    }

    public function set(string $key, mixed $value, string $type = 'string', ?string $group = null): bool
    {
        try {
            $stringValue = is_array($value) ? json_encode($value) : (string)$value;

            $this->database->query(
                'INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, updated_by)
                 VALUES (:key, :value, :type, :grp, :staff_id)
                 ON DUPLICATE KEY UPDATE setting_value = :value2, setting_type = :type2, updated_by = :staff_id2',
                [
                    ':key' => $key,
                    ':value' => $stringValue,
                    ':type' => $type,
                    ':grp' => $group,
                    ':staff_id' => $_SESSION['staff']['id'] ?? null,
                    ':value2' => $stringValue,
                    ':type2' => $type,
                    ':staff_id2' => $_SESSION['staff']['id'] ?? null,
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to save site setting', $exception);
            return false;
        }
    }

    public function getByGroup(string $group): array
    {
        return $this->database->queryAll(
            'SELECT * FROM site_settings WHERE setting_group = :group ORDER BY setting_key ASC',
            [':group' => $group]
        );
    }

    public function getAll(): array
    {
        return $this->database->queryAll(
            'SELECT * FROM site_settings ORDER BY setting_group ASC, setting_key ASC'
        );
    }

    private function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer' => (int)$value,
            'boolean' => in_array(strtolower($value), ['1', 'true', 'yes'], true),
            'json' => json_decode($value, true) ?? [],
            default => $value,
        };
    }
}
