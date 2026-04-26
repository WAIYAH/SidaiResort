<?php declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function login(string $email, string $password): bool
    {
        $email = strtolower(trim($email));

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        try {
            $staff = $this->database->queryOne(
                'SELECT id, full_name, email, role, password_hash, is_active
                 FROM staff
                 WHERE email = :email AND deleted_at IS NULL
                 LIMIT 1',
                [':email' => $email]
            );

            if ($staff === null || (int)$staff['is_active'] !== 1) {
                log_audit_action('auth.login_failed', 'staff', null, ['email' => $email]);
                return false;
            }

            if (!password_verify($password, (string)$staff['password_hash'])) {
                log_audit_action('auth.login_failed', 'staff', (int)$staff['id'], ['email' => $email]);
                return false;
            }

            session_regenerate_id(true);

            $_SESSION['staff'] = [
                'id' => (int)$staff['id'],
                'full_name' => (string)$staff['full_name'],
                'email' => (string)$staff['email'],
                'role' => (string)$staff['role'],
                'logged_in_at' => time(),
            ];

            $this->database->query(
                'UPDATE staff SET last_login = NOW(), login_attempts = 0, locked_until = NULL WHERE id = :id',
                [':id' => (int)$staff['id']]
            );

            log_audit_action('auth.login_success', 'staff', (int)$staff['id']);

            return true;
        } catch (\Throwable $exception) {
            log_error('Authentication failed due to a server error.', $exception);
            return false;
        }
    }

    public function logout(): void
    {
        $staffId = $_SESSION['staff']['id'] ?? null;
        if (is_int($staffId)) {
            log_audit_action('auth.logout', 'staff', $staffId);
        }

        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        setcookie(
            SESSION_NAME,
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => SESSION_COOKIE_SECURE,
                'httponly' => SESSION_COOKIE_HTTPONLY,
                'samesite' => SESSION_COOKIE_SAMESITE,
            ]
        );
    }

    public function check(): array|false
    {
        if (!isset($_SESSION['staff']) || !is_array($_SESSION['staff'])) {
            return false;
        }

        return $_SESSION['staff'];
    }

    public function requireAuth(): void
    {
        if ($this->check() === false) {
            header('Location: ' . WEB_ROOT . '/admin/login.php');
            exit;
        }
    }

    public function hasRole(string|array $role): bool
    {
        $staff = $this->check();
        if ($staff === false) {
            return false;
        }

        $roles = is_array($role) ? $role : [$role];

        return in_array((string)$staff['role'], $roles, true);
    }
}
