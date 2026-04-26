<?php
/**
 * PHP Built-in Server Router
 *
 * Usage: php -S localhost:8080 -t public router.php
 *
 * Replicates URL rewriting for local development and supports
 * WEB_ROOT-prefixed paths (e.g. /project/public/...).
 */

/**
 * Read WEB_ROOT from .env without bootstrapping the full app.
 */
function readWebRootFromEnv(string $rootDir): string
{
    $envFile = $rootDir . '/.env';
    if (!is_file($envFile)) {
        return '';
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return '';
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        if (trim($key) !== 'WEB_ROOT') {
            continue;
        }

        $value = trim(trim($value), "\"'");
        if ($value === '' || $value === '/') {
            return '';
        }

        return '/' . trim($value, '/');
    }

    return '';
}

/**
 * Normalize incoming URI by stripping WEB_ROOT prefix if present.
 */
function normalizeUri(string $uri, string $webRoot): string
{
    if ($uri === '') {
        return '/';
    }

    if ($webRoot !== '' && ($uri === $webRoot || str_starts_with($uri, $webRoot . '/'))) {
        $stripped = substr($uri, strlen($webRoot));
        return $stripped === '' ? '/' : $stripped;
    }

    return $uri;
}

/**
 * Serve non-PHP files when we cannot delegate to the built-in server.
 */
function serveStaticFile(string $path): void
{
    $mime = mime_content_type($path);
    if ($mime !== false) {
        header('Content-Type: ' . $mime);
    }

    header('Content-Length: ' . (string)filesize($path));
    readfile($path);
}

$rawUri = urldecode((string)parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$webRoot = readWebRootFromEnv(__DIR__);
$uri = normalizeUri($rawUri, $webRoot);
$publicDir = __DIR__ . '/public';
$requested = $publicDir . $uri;
$usesPrefixedPath = $rawUri !== $uri;

// Serve existing files directly (CSS, JS, images, etc.)
if ($uri !== '/' && is_file($requested)) {
    if (!$usesPrefixedPath) {
        return false; // Let PHP's built-in server handle it
    }

    $extension = strtolower((string)pathinfo($requested, PATHINFO_EXTENSION));
    if ($extension === 'php') {
        $_SERVER['SCRIPT_NAME'] = $uri;
        require $requested;
        return true;
    }

    serveStaticFile($requested);
    return true;
}

// Serve directory index files
if (is_dir($requested)) {
    $indexFile = rtrim($requested, '/') . '/index.php';
    if (is_file($indexFile)) {
        $_SERVER['SCRIPT_NAME'] = rtrim($uri, '/') . '/index.php';
        require $indexFile;
        return true;
    }
}

// Try extensionless URL → .php file
$phpFile = $publicDir . $uri . '.php';
if (is_file($phpFile)) {
    $_SERVER['SCRIPT_NAME'] = $uri . '.php';
    require $phpFile;
    return true;
}

// Default to index.php (homepage)
if ($uri === '/') {
    require $publicDir . '/index.php';
    return true;
}

// 404
http_response_code(404);
$notFoundFile = $publicDir . '/404.php';
if (is_file($notFoundFile)) {
    require $notFoundFile;
} else {
    echo '<h1>404 Not Found</h1>';
}
return true;
