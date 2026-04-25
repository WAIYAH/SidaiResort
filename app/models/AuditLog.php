<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class AuditLog
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function getAll(int $limit = 200, int $offset = 0): array
    {
        return $this->database->queryAll(
            'SELECT al.*, s.full_name as staff_name
             FROM audit_log al
             LEFT JOIN staff s ON al.staff_id = s.id
             ORDER BY al.created_at DESC
             LIMIT :limit OFFSET :offset',
            [':limit' => $limit, ':offset' => $offset]
        );
    }

    public function getByEntity(string $entityType, ?int $entityId = null): array
    {
        if ($entityId !== null) {
            return $this->database->queryAll(
                'SELECT al.*, s.full_name as staff_name
                 FROM audit_log al
                 LEFT JOIN staff s ON al.staff_id = s.id
                 WHERE al.entity_type = :type AND al.entity_id = :id
                 ORDER BY al.created_at DESC',
                [':type' => $entityType, ':id' => $entityId]
            );
        }

        return $this->database->queryAll(
            'SELECT al.*, s.full_name as staff_name
             FROM audit_log al
             LEFT JOIN staff s ON al.staff_id = s.id
             WHERE al.entity_type = :type
             ORDER BY al.created_at DESC LIMIT 200',
            [':type' => $entityType]
        );
    }

    public function record(string $action, string $entityType, ?int $entityId = null, ?array $oldValues = null, ?array $newValues = null): bool
    {
        try {
            $this->database->query(
                'INSERT INTO audit_log (staff_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent)
                 VALUES (:staff_id, :action, :entity_type, :entity_id, :old_values, :new_values, :ip, :ua)',
                [
                    ':staff_id' => $_SESSION['staff']['id'] ?? null,
                    ':action' => $action,
                    ':entity_type' => $entityType,
                    ':entity_id' => $entityId,
                    ':old_values' => $oldValues !== null ? json_encode($oldValues) : null,
                    ':new_values' => $newValues !== null ? json_encode($newValues) : null,
                    ':ip' => get_client_ip(),
                    ':ua' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to record audit log', $exception);
            return false;
        }
    }

    public function getTotalCount(): int
    {
        $result = $this->database->queryOne('SELECT COUNT(*) as count FROM audit_log');
        return (int)($result['count'] ?? 0);
    }
}
