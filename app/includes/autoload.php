<?php declare(strict_types=1);

/**
 * Minimal PSR-4 style autoloader for the App namespace.
 */

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $segments = explode('\\', $relative);

    if (isset($segments[0])) {
        $segments[0] = strtolower($segments[0]);
    }

    $path = dirname(__DIR__) . '/' . implode('/', $segments) . '.php';

    if (is_file($path)) {
        require_once $path;
    }
});