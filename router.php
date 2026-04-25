<?php
/**
 * PHP Built-in Server Router
 * 
 * Usage: php -S localhost:8080 -t public router.php
 * 
 * Replicates the .htaccess URL rewriting for local development.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$publicDir = __DIR__ . '/public';
$requested = $publicDir . $uri;

// Serve existing files directly (CSS, JS, images, etc.)
if ($uri !== '/' && is_file($requested)) {
    return false; // Let PHP's built-in server handle it
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
