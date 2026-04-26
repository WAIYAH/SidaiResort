<?php declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?self $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                DB_DSN,
                DB_USER,
                DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            $this->pdo->exec("SET time_zone = '+03:00'");
        } catch (PDOException $exception) {
            log_error('Database connection failed.', $exception);
            throw new RuntimeException('A database connection error occurred.');
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function connection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Run a prepared statement.
     *
     * @throws PDOException
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $statement = $this->pdo->prepare($sql);
            foreach ($params as $key => $value) {
                $parameter = is_int($key) ? $key + 1 : $key;
                $type = match (true) {
                    is_int($value) => PDO::PARAM_INT,
                    is_bool($value) => PDO::PARAM_BOOL,
                    $value === null => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR,
                };
                $statement->bindValue($parameter, $value, $type);
            }
            $statement->execute();

            return $statement;
        } catch (PDOException $exception) {
            log_error('Database query failed.', $exception);
            throw $exception;
        }
    }

    public function queryOne(string $sql, array $params = []): ?array
    {
        $statement = $this->query($sql, $params);
        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    public function queryAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function beginTransaction(): void
    {
        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }

    public function commit(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    public function lastInsertId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }

    private function __clone()
    {
    }
}
