<?php declare(strict_types=1);

/**
 * Sidai Resort shared constants and helper functions.
 */

const BOOKING_TYPES = [
    'room' => 'Room Stay',
    'pool' => 'Pool Session',
    'hall' => 'Hall Hire',
    'dining' => 'Dining Reservation',
    'event' => 'Event Package',
    'spa' => 'Spa & Wellness',
    'music_shoot' => 'Music & Film Shoot',
];

const PAYMENT_METHODS = [
    'mpesa' => 'M-Pesa',
    'cash' => 'Cash',
    'bank' => 'Bank Transfer',
];

const STATUS_CONSTANTS = [
    'booking' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'checked_in' => 'Checked In',
        'checked_out' => 'Checked Out',
        'cancelled' => 'Cancelled',
    ],
    'payment' => [
        'unpaid' => 'Unpaid',
        'partial' => 'Partially Paid',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ],
    'order' => [
        'pending' => 'Pending',
        'preparing' => 'Preparing',
        'ready' => 'Ready',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ],
];

// Aliases used by public-facing pages
if (!defined('RESORT_PHONE')) {
    define('RESORT_PHONE', APP_PHONE);
}
if (!defined('RESORT_EMAIL')) {
    define('RESORT_EMAIL', APP_EMAIL);
}
if (!defined('RESORT_ADDRESS')) {
    define('RESORT_ADDRESS', APP_ADDRESS);
}
if (!defined('RESORT_WHATSAPP')) {
    define('RESORT_WHATSAPP', APP_WHATSAPP);
}

if (!function_exists('safe_html')) {
    function safe_html(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('sanitize_input')) {
    function sanitize_input(?string $value): string
    {
        return trim((string)filter_var($value ?? '', FILTER_UNSAFE_RAW));
    }
}

if (!function_exists('csrf_token_field')) {
    /**
     * Output a hidden input field with the CSRF token.
     */
    function csrf_token_field(): string
    {
        return \App\Core\CSRF::field();
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * Verify a submitted CSRF token.
     */
    function verify_csrf_token(?string $token): bool
    {
        return \App\Core\CSRF::verify($token);
    }
}

if (!function_exists('format_kes')) {
    function format_kes(float|int|string $amount): string
    {
        return 'Ksh ' . number_format((float)$amount, 2, '.', ',');
    }
}

if (!function_exists('format_eat')) {
    function format_eat(DateTimeInterface|int|string $timestamp, string $format = 'd M Y, H:i'): string
    {
        try {
            $tz = new DateTimeZone(APP_TIMEZONE);

            if ($timestamp instanceof DateTimeInterface) {
                $date = DateTimeImmutable::createFromInterface($timestamp)->setTimezone($tz);
            } elseif (is_numeric($timestamp)) {
                $date = (new DateTimeImmutable('@' . (int)$timestamp))->setTimezone($tz);
            } else {
                $date = new DateTimeImmutable((string)$timestamp, $tz);
            }

            return $date->format($format);
        } catch (Throwable) {
            return (string)$timestamp;
        }
    }
}

if (!function_exists('format_eat_date')) {
    /**
     * Alias for format_eat() with a date-only format (no time component).
     */
    function format_eat_date(DateTimeInterface|int|string $timestamp, string $format = 'd M Y'): string
    {
        return format_eat($timestamp, $format);
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip(): string
    {
        $candidates = [
            $_SERVER['HTTP_CF_CONNECTING_IP'] ?? '',
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
            $_SERVER['REMOTE_ADDR'] ?? '',
        ];

        foreach ($candidates as $candidate) {
            if ($candidate === '') {
                continue;
            }

            $ip = trim(explode(',', $candidate)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return '0.0.0.0';
    }
}

if (!function_exists('log_error')) {
    function log_error(string $message, ?Throwable $exception = null): void
    {
        $details = [
            'time' => format_eat('now', 'Y-m-d H:i:s'),
            'ip' => get_client_ip(),
            'message' => $message,
        ];

        if ($exception !== null) {
            $details['exception'] = $exception->getMessage();
            if (APP_DEBUG) {
                $details['trace'] = $exception->getTraceAsString();
            }
        }

        error_log(json_encode($details, JSON_UNESCAPED_SLASHES) . PHP_EOL, 3, LOG_ERROR_FILE);
    }
}

if (!function_exists('log_audit_action')) {
    function log_audit_action(string $action, string $entityType, ?int $entityId = null, array $context = []): void
    {
        $payload = [
            'time' => format_eat('now', 'Y-m-d H:i:s'),
            'staff_id' => $_SESSION['staff']['id'] ?? null,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip' => get_client_ip(),
            'user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'context' => $context,
        ];

        error_log(json_encode($payload, JSON_UNESCAPED_SLASHES) . PHP_EOL, 3, LOG_AUDIT_FILE);
    }
}

if (!function_exists('apply_security_headers')) {
    function apply_security_headers(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');
        header("Content-Security-Policy: default-src 'self'; base-uri 'self'; frame-ancestors 'none'; img-src 'self' data: https:; font-src 'self' https://fonts.gstatic.com data:; style-src 'self' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net 'unsafe-inline'; script-src 'self' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net 'unsafe-inline' 'unsafe-eval'; connect-src 'self' https://sandbox.safaricom.co.ke https://api.safaricom.co.ke;");
    }
}

if (!function_exists('generate_booking_ref')) {
    function generate_booking_ref(): string
    {
        $prefix = 'SDR';
        $date = date('ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));

        return "{$prefix}-{$date}-{$random}";
    }
}

if (!function_exists('generate_order_ref')) {
    function generate_order_ref(): string
    {
        $prefix = 'ORD';
        $date = date('ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));

        return "{$prefix}-{$date}-{$random}";
    }
}

if (!function_exists('generate_payment_ref')) {
    function generate_payment_ref(): string
    {
        $prefix = 'PAY';
        $date = date('ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));

        return "{$prefix}-{$date}-{$random}";
    }
}

if (!function_exists('json_response')) {
    function json_response(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_UNESCAPED_SLASHES);
        exit;
    }
}