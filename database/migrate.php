<?php declare(strict_types=1);

/**
 * Sidai Resort — Database Migration Runner
 *
 * Usage (from project root):
 *   php database/migrate.php              Run all pending migrations
 *   php database/migrate.php --seed       Run schema + seed data
 *   php database/migrate.php --fresh      Drop all tables and re-run
 *   php database/migrate.php --status     Show migration status
 */

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\Database;

$command = $argv[1] ?? '--run';

echo "\n  ╔══════════════════════════════════════╗\n";
echo "  ║    Sidai Resort — Database Setup     ║\n";
echo "  ╚══════════════════════════════════════╝\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->connection();

    // Ensure migrations tracking table exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $migrationsDir = __DIR__ . '/migrations';

    if ($command === '--status') {
        $executed = $pdo->query('SELECT migration FROM migrations ORDER BY executed_at ASC')->fetchAll(PDO::FETCH_COLUMN);
        echo "  Executed migrations:\n";
        if (empty($executed)) {
            echo "    (none)\n";
        } else {
            foreach ($executed as $m) {
                echo "    ✅ {$m}\n";
            }
        }

        echo "\n";
        exit(0);
    }

    if ($command === '--fresh') {
        echo "  ⚠️  Dropping all tables...\n";
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            echo "    Dropped: {$table}\n";
        }
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        echo "\n";
    }

    // Run the main schema
    $schemaFile = __DIR__ . '/schema.sql';
    if (file_exists($schemaFile)) {
        echo "  📋 Running schema.sql...\n";
        $sql = file_get_contents($schemaFile);
        $pdo->exec($sql);
        echo "    ✅ Schema applied.\n\n";
    }

    // Ensure migrations tracking table exists (again after fresh)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Run pending migrations
    $executed = $pdo->query('SELECT migration FROM migrations')->fetchAll(PDO::FETCH_COLUMN);
    $files = is_dir($migrationsDir) ? glob($migrationsDir . '/*.sql') : [];
    sort($files);

    $pending = array_filter($files, function ($file) use ($executed) {
        return !in_array(basename($file), $executed, true);
    });

    if (empty($pending)) {
        echo "  ✅ No pending migrations.\n";
    } else {
        foreach ($pending as $file) {
            $name = basename($file);
            echo "  ▶ Running: {$name}...\n";
            $sql = file_get_contents($file);
            $pdo->exec($sql);
            $pdo->prepare('INSERT INTO migrations (migration) VALUES (:name)')->execute([':name' => $name]);
            echo "    ✅ Done.\n";
        }
    }

    // Seed data
    if ($command === '--seed' || $command === '--fresh') {
        $seedFile = $migrationsDir . '/seed_data.sql';
        if (file_exists($seedFile) && !in_array('seed_data.sql', $executed, true)) {
            echo "\n  🌱 Seeding data...\n";
            $pdo->exec(file_get_contents($seedFile));
            $pdo->prepare('INSERT IGNORE INTO migrations (migration) VALUES (:name)')->execute([':name' => 'seed_data.sql']);
            echo "    ✅ Seed data applied.\n";
        }
    }

    echo "\n  🎉 Database setup complete!\n\n";

} catch (\Throwable $e) {
    echo "\n  ❌ Error: " . $e->getMessage() . "\n";
    if (APP_DEBUG) {
        echo "  " . $e->getTraceAsString() . "\n";
    }
    exit(1);
}
