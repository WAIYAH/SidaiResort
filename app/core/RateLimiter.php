<?php declare(strict_types=1);

namespace App\Core;

final class RateLimiter
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function isAllowed(string $key, int $maxAttempts = 5, int $windowSeconds = 300): bool
    {
        $cacheFile = STORAGE_PATH . "/cache/{$key}.json";
        $now = time();

        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            $windowStart = $data['window_start'] ?? $now;

            if ($now - $windowStart > $windowSeconds) {
                // Window expired, reset counter
                file_put_contents($cacheFile, json_encode(['count' => 1, 'window_start' => $now]));
                return true;
            }

            if ($data['count'] >= $maxAttempts) {
                return false;
            }

            // Increment counter
            file_put_contents($cacheFile, json_encode(['count' => $data['count'] + 1, 'window_start' => $windowStart]));
            return true;
        }

        // First attempt in this window
        file_put_contents($cacheFile, json_encode(['count' => 1, 'window_start' => $now]));
        return true;
    }

    public function getRemainingAttempts(string $key, int $maxAttempts = 5, int $windowSeconds = 300): int
    {
        $cacheFile = STORAGE_PATH . "/cache/{$key}.json";
        $now = time();

        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            $windowStart = $data['window_start'] ?? $now;

            if ($now - $windowStart > $windowSeconds) {
                return $maxAttempts;
            }

            return max(0, $maxAttempts - $data['count']);
        }

        return $maxAttempts;
    }

    public function reset(string $key): void
    {
        $cacheFile = STORAGE_PATH . "/cache/{$key}.json";
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}
