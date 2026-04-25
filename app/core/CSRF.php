<?php declare(strict_types=1);

namespace App\Core;

final class CSRF
{
    public static function generate(): string
    {
        $tokenKey = CSRF_TOKEN_NAME;
        $issuedKey = CSRF_TOKEN_NAME . '_issued';

        $expired = isset($_SESSION[$issuedKey]) && (time() - (int)$_SESSION[$issuedKey]) > CSRF_TOKEN_TTL;
        if (!isset($_SESSION[$tokenKey]) || $expired) {
            $_SESSION[$tokenKey] = bin2hex(random_bytes(32));
            $_SESSION[$issuedKey] = time();
        }

        return (string)$_SESSION[$tokenKey];
    }

    public static function verify(?string $token): bool
    {
        $submittedToken = $token ?? ($_POST[CSRF_TOKEN_NAME] ?? null);

        if (!is_string($submittedToken) || $submittedToken === '') {
            return false;
        }

        $sessionToken = $_SESSION[CSRF_TOKEN_NAME] ?? null;
        $issuedAt = (int)($_SESSION[CSRF_TOKEN_NAME . '_issued'] ?? 0);

        if (!is_string($sessionToken) || $sessionToken === '') {
            return false;
        }

        if ($issuedAt === 0 || (time() - $issuedAt) > CSRF_TOKEN_TTL) {
            unset($_SESSION[CSRF_TOKEN_NAME], $_SESSION[CSRF_TOKEN_NAME . '_issued']);
            return false;
        }

        return hash_equals($sessionToken, $submittedToken);
    }

    public static function field(): string
    {
        return '<input type="hidden" name="' . safe_html(CSRF_TOKEN_NAME) . '" value="' . safe_html(self::generate()) . '">';
    }

    public static function token(): string
    {
        return self::generate();
    }
}