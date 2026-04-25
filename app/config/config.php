<?php declare(strict_types=1);

/**
 * Sidai Resort application configuration.
 * Keep this file outside public web root and source API keys from environment.
 */

// Load .env file into getenv() if present
$envPath = dirname(__DIR__, 2) . '/.env';
if (is_file($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (getenv($key) === false) {
                putenv("{$key}={$value}");
            }
        }
    }
    unset($lines, $line, $envPath);
}

if (!function_exists('env_value')) {
    function env_value(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}

if (!function_exists('env_bool')) {
    function env_bool(string $key, bool $default = false): bool
    {
        $value = env_value($key);

        if ($value === null) {
            return $default;
        }

        return in_array(strtolower((string)$value), ['1', 'true', 'yes', 'on'], true);
    }
}

// Core paths

define('APP_ROOT', dirname(__DIR__, 2));
define('APP_PATH', APP_ROOT . '/app');
define('WEB_ROOT', (string)env_value('WEB_ROOT', ''));
define('PUBLIC_ROOT', APP_ROOT . '/public');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('UPLOAD_PATH', STORAGE_PATH . '/uploads');
define('RECEIPT_PATH', STORAGE_PATH . '/receipts');
define('LOG_DIR', STORAGE_PATH . '/logs');
define('LOG_ERROR_FILE', LOG_DIR . '/error.log');
define('LOG_AUDIT_FILE', LOG_DIR . '/audit.log');
define('LOG_MPESA_FILE', LOG_DIR . '/mpesa.log');

// App settings

define('APP_NAME', 'Sidai Resort');
define('APP_ENV', (string)env_value('APP_ENV', 'production'));
define('APP_URL', (string)env_value('APP_URL', 'https://sidairesort.com'));
define('APP_DEBUG', env_bool('APP_DEBUG', false));
define('APP_TIMEZONE', (string)env_value('APP_TIMEZONE', 'Africa/Nairobi'));
define('APP_CURRENCY', 'KES');
define('APP_EMAIL', (string)env_value('APP_EMAIL', 'hello@sidairesort.com'));
define('APP_PHONE', (string)env_value('APP_PHONE', '+254720000000'));
define('APP_WHATSAPP', (string)env_value('APP_WHATSAPP', '254720000000'));
define('APP_ADDRESS', (string)env_value('APP_ADDRESS', 'Naroosura, Narok County, Kenya'));

// Database settings

define('DB_HOST', (string)env_value('DB_HOST', '127.0.0.1'));
define('DB_PORT', (int)env_value('DB_PORT', 3306));
define('DB_NAME', (string)env_value('DB_NAME', 'sidai_resort'));
define('DB_USER', (string)env_value('DB_USER', 'root'));
define('DB_PASSWORD', (string)env_value('DB_PASSWORD', ''));
define('DB_CHARSET', 'utf8mb4');
define('DB_DSN', sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET));

// Session security

define('SESSION_NAME', 'sidai_sid');
define('SESSION_LIFETIME', (int)env_value('SESSION_LIFETIME', 7200));
define('SESSION_COOKIE_SECURE', env_bool('SESSION_COOKIE_SECURE', true));
define('SESSION_COOKIE_HTTPONLY', true);
define('SESSION_COOKIE_SAMESITE', 'Strict');
define('SESSION_USE_STRICT_MODE', true);

// CSRF / rate limiting

define('CSRF_TOKEN_NAME', '_csrf');
define('CSRF_TOKEN_TTL', 7200);
define('RATE_LIMIT_BOOKINGS_PER_HOUR', 3);
define('RATE_LIMIT_CONTACT_PER_HOUR', 5);
define('RATE_LIMIT_LOGIN_ATTEMPTS', 5);

// Upload rules

define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024);
define('ALLOWED_UPLOAD_MIMES', [
    'image/jpeg',
    'image/png',
    'image/webp',
]);

// M-Pesa Daraja settings

define('MPESA_ENV', (string)env_value('MPESA_ENV', 'sandbox'));
define('MPESA_CONSUMER_KEY', (string)env_value('MPESA_CONSUMER_KEY', ''));
define('MPESA_CONSUMER_SECRET', (string)env_value('MPESA_CONSUMER_SECRET', ''));
define('MPESA_SHORTCODE', (string)env_value('MPESA_SHORTCODE', '174379'));
define('MPESA_PASSKEY', (string)env_value('MPESA_PASSKEY', ''));
define('MPESA_CALLBACK_URL', (string)env_value('MPESA_CALLBACK_URL', APP_URL . '/api/mpesa-callback.php'));

define(
    'MPESA_BASE_URL',
    MPESA_ENV === 'live' ? 'https://api.safaricom.co.ke' : 'https://sandbox.safaricom.co.ke'
);
define('MPESA_OAUTH_URL', MPESA_BASE_URL . '/oauth/v1/generate?grant_type=client_credentials');
define('MPESA_STK_PUSH_URL', MPESA_BASE_URL . '/mpesa/stkpush/v1/processrequest');
define('MPESA_STK_QUERY_URL', MPESA_BASE_URL . '/mpesa/stkpushquery/v1/query');

// SMTP (PHPMailer)

define('MAIL_HOST', (string)env_value('MAIL_HOST', 'smtp.mailtrap.io'));
define('MAIL_PORT', (int)env_value('MAIL_PORT', 587));
define('MAIL_USERNAME', (string)env_value('MAIL_USERNAME', ''));
define('MAIL_PASSWORD', (string)env_value('MAIL_PASSWORD', ''));
define('MAIL_ENCRYPTION', (string)env_value('MAIL_ENCRYPTION', 'tls'));
define('MAIL_FROM_ADDRESS', (string)env_value('MAIL_FROM_ADDRESS', 'hello@sidairesort.com'));
define('MAIL_FROM_NAME', (string)env_value('MAIL_FROM_NAME', 'Sidai Resort'));
define('MAIL_ADMIN_ADDRESS', (string)env_value('MAIL_ADMIN_ADDRESS', 'hello@sidairesort.com'));

// Social links

define('SOCIAL_INSTAGRAM', (string)env_value('SOCIAL_INSTAGRAM', 'https://instagram.com/sidairesort'));
define('SOCIAL_FACEBOOK', (string)env_value('SOCIAL_FACEBOOK', 'https://facebook.com/sidairesort'));
define('SOCIAL_WHATSAPP', 'https://wa.me/' . preg_replace('/\D+/', '', APP_WHATSAPP));
define('GOOGLE_MAPS_URL', (string)env_value('GOOGLE_MAPS_URL', 'https://maps.google.com/?q=Naroosura+Narok+County+Kenya'));

// Runtime setup

if (!is_dir(STORAGE_PATH)) {
    @mkdir(STORAGE_PATH, 0755, true);
}
if (!is_dir(UPLOAD_PATH)) {
    @mkdir(UPLOAD_PATH, 0755, true);
}
if (!is_dir(RECEIPT_PATH)) {
    @mkdir(RECEIPT_PATH, 0755, true);
}
if (!is_dir(LOG_DIR)) {
    @mkdir(LOG_DIR, 0755, true);
}

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}

ini_set('log_errors', '1');
ini_set('error_log', LOG_ERROR_FILE);

date_default_timezone_set(APP_TIMEZONE);

ini_set('session.cookie_httponly', SESSION_COOKIE_HTTPONLY ? '1' : '0');
ini_set('session.cookie_secure', SESSION_COOKIE_SECURE ? '1' : '0');
ini_set('session.cookie_samesite', SESSION_COOKIE_SAMESITE);
ini_set('session.use_strict_mode', SESSION_USE_STRICT_MODE ? '1' : '0');
ini_set('session.gc_maxlifetime', (string)SESSION_LIFETIME);