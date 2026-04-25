<?php declare(strict_types=1);

namespace App\Core;

final class Logger
{
    private string $errorLogPath;
    private string $auditLogPath;
    private string $mpesaLogPath;

    public function __construct()
    {
        $this->errorLogPath = STORAGE_PATH . '/logs/error.log';
        $this->auditLogPath = STORAGE_PATH . '/logs/audit.log';
        $this->mpesaLogPath = STORAGE_PATH . '/logs/mpesa.log';
    }

    public function logError(string $message, ?\Throwable $exception = null): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => 'ERROR',
            'message' => $message,
            'file' => $exception?->getFile(),
            'line' => $exception?->getLine(),
            'trace' => $exception?->getTraceAsString(),
            'url' => $_SERVER['REQUEST_URI'] ?? 'N/A',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
        ];

        $this->write($this->errorLogPath, json_encode($logEntry));
    }

    public function logMpesa(string $action, array $data): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'data' => $this->maskSensitiveData($data),
        ];

        $this->write($this->mpesaLogPath, json_encode($logEntry));
    }

    public function logAudit(string $action, string $entityType, ?int $entityId, ?array $oldValues, ?array $newValues): void
    {
        $database = Database::getInstance();

        $staffId = $_SESSION['staff']['id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        try {
            $database->query(
                'INSERT INTO audit_log (staff_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent)
                 VALUES (:staff_id, :action, :entity_type, :entity_id, :old_values, :new_values, :ip_address, :user_agent)',
                [
                    ':staff_id' => $staffId,
                    ':action' => $action,
                    ':entity_type' => $entityType,
                    ':entity_id' => $entityId,
                    ':old_values' => $oldValues ? json_encode($oldValues) : null,
                    ':new_values' => $newValues ? json_encode($newValues) : null,
                    ':ip_address' => $ipAddress,
                    ':user_agent' => $userAgent,
                ]
            );
        } catch (\Throwable $exception) {
            $this->logError('Failed to log audit action', $exception);
        }
    }

    private function maskSensitiveData(array $data): array
    {
        $sensitiveKeys = ['password', 'pin', 'secret', 'token', 'phone', 'mpesa_phone'];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key]) && is_string($data[$key])) {
                $data[$key] = substr($data[$key], 0, 2) . '****' . substr($data[$key], -2);
            }
        }

        return $data;
    }

    private function write(string $logPath, string $entry): void
    {
        @file_put_contents($logPath, $entry . PHP_EOL, FILE_APPEND);
    }
}
