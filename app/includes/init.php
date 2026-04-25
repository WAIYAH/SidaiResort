<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/constants.php';
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/icons.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(SESSION_NAME);

    session_start([
        'cookie_lifetime' => SESSION_LIFETIME,
        'cookie_httponly' => SESSION_COOKIE_HTTPONLY,
        'cookie_secure' => SESSION_COOKIE_SECURE,
        'cookie_samesite' => SESSION_COOKIE_SAMESITE,
        'use_strict_mode' => SESSION_USE_STRICT_MODE,
        'gc_maxlifetime' => SESSION_LIFETIME,
    ]);
}

apply_security_headers();